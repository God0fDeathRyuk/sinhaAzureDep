<?php



namespace App\Controllers;



use CodeIgniter\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Style\Fill;



class BillingController extends BaseController

{

    public function __construct() {

        // $this->db = \config\database::connect();

       $db = $this->db = db_connect();

       $temp_db = $this->temp_db = db_connect('temp');

       $this->session = session();

       // $this->db_group = db_connect(['default', 'temp']);

    }

    

    /*********************************************************************************************/

    /***************************** Billing [Transactions] ***********************************/

    /*********************************************************************************************/



    public function bill_history($url = null) {

        $response = [];

        switch($url) {

            case 'draft-bill':

                $bill_serial_no = $_REQUEST['serial_no'];

                $tot_char        = 60 ;

                $tot_no_of_lines = 60 ;

                $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];

                $user_id        = session()->userId ;

                $curr_time      = $logdt_qry['current_time'];

                $curr_date      = $logdt_qry['current_dmydate'];

                $curr_day       = substr($curr_date,0,2) ;

                $curr_month     = substr($curr_date,3,2) ; 

                $curr_year      = substr($curr_date,6,4) ;

                $temp_id        = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);

                $temp_table     = $temp_id. "_db" ;

                $tbl_qry = $this->temp_db->query("drop table if exists $temp_table");

                $create_stmt = "create table if not exists $temp_table 

                            (srl_no              int(8) NOT NULL AUTO_INCREMENT PRIMARY KEY ,

                            row_no              int(4),

                            activity_date       date,

                            activity_desc       text,

                            io_ind              varchar(1),

                            billed_amount       double(12,2),

                            service_tax_ind     varchar(1),

                            service_tax_percent double(7,3),

                            service_tax_desc    varchar(50),

                            service_tax_amount  double(12,2))";

                $tbl_qry = $this->temp_db->query($create_stmt);

                $hdr_stmt = "select a.serial_no,date_format(a.bill_date,'%d-%m-%Y') bill_date,a.client_code,a.matter_code,a.subject_desc,a.other_case_desc,a.reference_desc,a.status_code,

                            ifnull(a.bill_amount_inpocket,'')  bill_amount_inpocket,

                            ifnull(a.bill_amount_outpocket,'') bill_amount_outpocket,

                            ifnull(a.bill_amount_counsel,'')   bill_amount_counsel,

                            ifnull(a.service_tax_amount,'')    service_tax_amount,

                            a.source_code

                            from billinfo_header a

                            where a.serial_no = '$bill_serial_no' ";

                $hdr_row = $this->db->query($hdr_stmt)->getResultArray()[0];

                $client_code = $hdr_row['client_code'];

                $matter_code = $hdr_row['matter_code'];

                $other_case_desc = stripslashes($hdr_row['other_case_desc']);

                $other_case_desc = str_replace(',','<br>',$other_case_desc);

                // fileinfo header

                $finh_stmt = "select matter_desc1,matter_desc2,billing_addr_code,billing_attn_code from fileinfo_header where matter_code = '".$matter_code."'";

                $finh_row = $this->db->query($finh_stmt)->getResultArray()[0];

                $matter_name    = $finh_row['matter_desc1'].'&nbsp;'.$finh_row['matter_desc2'];

                $bill_addr_code = $finh_row['billing_addr_code'];

                $bill_attn_code = $finh_row['billing_attn_code'];

                // client name

                $clnt_row = $this->db->query("select * from client_master where client_code = '".$client_code."'")->getResultArray()[0];

                $client_name = $clnt_row['client_name'];

                // client address

                $cadr_row = $this->db->query("select * from client_address where client_code = '".$client_code."' and address_code = '".$bill_addr_code."'")->getResultArray()[0];

                // client attention

                $catn_row = $this->db->query("select * from client_attention where client_code = '".$client_code."'and attention_code = '".$bill_attn_code."'")->getResultArray()[0];

                $attention_name = $catn_row['attention_name'];

                $designation    = $catn_row['designation'];

                $sex            = $catn_row['sex'];

                if ($sex == 'M') { $attention_name = 'Mr. '.$attention_name ; } 

                // billinfo detail

                $dtl_stmt = "select b.row_no,b.activity_date,b.activity_desc,b.io_ind,

                        ifnull(b.billed_amount,0) billed_amount,

                        ifnull(b.service_tax_ind,'N') service_tax_ind,

                        ifnull(b.service_tax_percent,'N') service_tax_percent,

                        if(ifnull(b.service_tax_ind,'N')='Y','(A) TAXABLE SERVICE','(B) NON TAXABLE SERVICE') service_tax_desc,

                        ifnull(b.service_tax_amount,0) service_tax_amount

                        from billinfo_detail b

                        where b.ref_billinfo_serial_no  = '$bill_serial_no'

                        and ifnull(b.printer_ind,'N') = 'Y'

                        order by ifnull(b.service_tax_ind,'N') desc, b.prn_seq_no ";

                $dtl_qry = $this->db->query($dtl_stmt)->getResultArray();

                // delete old record from temporary table

                $dele_qry = $this->temp_db->query("delete from $temp_table");

                foreach($dtl_qry as $dtl_row)

                {

                    $row_no            = $dtl_row['row_no'];

                    $activity_date     = $dtl_row['activity_date'];

                    $activity_desc     = $dtl_row['activity_desc'] . chr(13);

                    $io_ind            = $dtl_row['io_ind'];

                    $billed_amount     = $dtl_row['billed_amount'];

                    $serv_tax_ind      = $dtl_row['service_tax_ind'];

                    $serv_tax_per      = $dtl_row['service_tax_percent'];

                    $serv_tax_desc     = $dtl_row['service_tax_desc'];

                    $serv_tax_amount   = $dtl_row['service_tax_amount'];



                    $actvt_desc    = wordwrap($activity_desc, $tot_char, "\n");

                    $actvt_array   = explode("\n",$actvt_desc);

                    $row_cnt       = count($actvt_array);

                    // insertion of first line into temp table

                    $inst_stmt = "insert into $temp_table

                                (row_no,activity_date,activity_desc,io_ind,billed_amount,service_tax_ind,service_tax_percent,service_tax_desc,service_tax_amount)

                                values($row_no,'$activity_date','".addslashes($actvt_array[0])."','$io_ind',$billed_amount,'$serv_tax_ind','$serv_tax_per','$serv_tax_desc','$serv_tax_amount')";

                    $inst_qry = $this->temp_db->query($inst_stmt);

                    // insertion of rest lines into temp table

                    for($j=1;$j<$row_cnt;$j++)

                    {

                        $inst_stmt = "insert into $temp_table (row_no,activity_desc,service_tax_ind,service_tax_percent)

                        values($row_no,'".addslashes($actvt_array[$j])."','$serv_tax_ind','$serv_tax_per')";

                        $inst_qry = $this->temp_db->query($inst_stmt);

                    }

                }

                // end of temporary table insertion

                // selection record from temp table

                $sele_stmt = "select date_format(activity_date,'%d-%m-%Y') activity_date,activity_desc,io_ind,ifnull(billed_amount,0) billed_amount,service_tax_ind,service_tax_percent,service_tax_desc,ifnull(service_tax_amount,0) service_tax_amount 

                            from $temp_table

                            order by service_tax_ind desc, srl_no, row_no ";

                $sele_qry = $this->temp_db->query($sele_stmt)->getResultArray();

            

                $selecnt_qry = $this->temp_db->query("select * from $temp_table ")->getResultArray() ;

                $selecnt_nos = count($selecnt_qry) ;

                

                $bill_data = [

                    "client_name" => $client_name,

                    "attention_name" => $attention_name,

                    "matter_name" => $matter_name,

                    "other_case_desc" => $other_case_desc,

                    "selecnt_nos" => $selecnt_nos,

                    "tot_no_of_lines" => $tot_no_of_lines,

                    "tot_char" => $tot_char,

                ];

                

                if ($selecnt_nos == 0) $response['status'] = 'failed'; else {

                    $response['status'] = 'success';

                    $response["page"] = view('pages/Billing/bill_history', compact("hdr_row", "bill_data", "cadr_row", "sele_qry")); 

                } break;

        }

        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode($response));

    }



    public function bill_generation($option = null){

        $arr['leftMenus'] = menu_data(); 
        $arr['menuHead'] = [0];
        $data = branches('demo');
        $user_option = $option; 
        $permission = ($option == 'proceed') ? 'readonly' : '';

        $displayId   = ['matter_help_id' => '4212'] ; 
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $finsub = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
		// echo '<pre>';print_r($data['requested_url']);die;
        if($this->request->getMethod() == 'post') {
            $option             = isset($_POST['option'])?$_POST['option']:null; 
            $client_code        = isset($_POST['client_code'])?$_POST['client_code']:null; 
            $matter_code        = isset($_REQUEST['matter_code'])?$_REQUEST['matter_code']:null;
            $other_case_desc    = isset($_POST['other_case_desc'])?$_POST['other_case_desc']:null; 
            $court_fee_bill_ind = isset($_POST['court_fee_bill_ind'])?$_POST['court_fee_bill_ind']:null; 
            //$letter_date       = date_conv($letter_date);
            $row_no_case       = isset($_POST['row_no_case'])?$_POST['row_no_case']:null; 
            // echo '<pre>'; print_r($_POST['client_code']);die;
            $row_no_other_exp  = isset($_POST['row_no_other_exp'])?$_POST['row_no_other_exp']:null; 
            $other_case_ind         = isset($_POST['other_ind'])?$_POST['other_ind']:0; 
            $all_other_case_counter = isset($_POST['othcase_cnt'])?$_POST['othcase_cnt']:0; 
            $all_case               = isset($_POST['othcase_dtl'])?$_POST['othcase_dtl']:null;

            //-- billinfo_header
            $billinfo_header_table = $this->db->table("billinfo_header");
        
            // //-- billinfo_detail
            $billinfo_detail_table = $this->db->table("billinfo_detail");

            // //-- billinfo_cases
            $billinfo_cases_table = $this->db->table("billinfo_cases");

            // //-- case_detail_other_case
            $case_detail_other_case_table = $this->db->table("case_detail_other_case");

            if($finsub=="fsub")
            {

                    //------------------------ Temporary Table Creation 
                    $clerk_ind = 'N';
                    $id = session()->userId ;

                    $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];
                    $user_id        = session()->userId ;
                    $curr_sysdate   = $logdt_qry['current_date'];
                    $curr_time      = $logdt_qry['current_time'];
                    $curr_date      = $logdt_qry['current_dmydate'];
                    $curr_day       = substr($curr_date,0,2) ;
                    $curr_month     = substr($curr_date,3,2) ; 
                    $curr_year      = substr($curr_date,6,4) ;
                    $temp_id        = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);
                    $temp_table     = $temp_id.'zz_bill_gen' ;
                    $drop_sql       = $this->temp_db->query("drop table if exists $temp_table") ;
                    //
                    $tmp_table_sql = "CREATE table $temp_table (
                                                            serial_no       varchar(6),
                                                            activity_date   date,
                                                            type_code       char(1),
                                                            counsel_code    varchar(4),
                                                            activity_code   varchar(3),
                                                            activity_of     varchar(3),
                                                            activity_type   varchar(3),
                                                            header_desc     text,
                                                            activity_desc   varchar(200),
                                                            counsel_name    varchar(200),
                                                            clerk_ind       char(1),
                                                            bearer_fee      double(12,2),
                                                            rate_key        varchar(200),
                                                            rate_amount     double(13,2)
                                                            )";

                    $tmp_table = $this->temp_db->query($tmp_table_sql);
                    for($i=1; $i<=$row_no_case; $i++)
                    {
                        $case_check = isset($_POST['case_check'.$i]) ? $_POST['case_check'.$i] :'';
                        if($case_check == "Y")
                        {  
                            $serial_no      = $_POST['serial_no'.$i];
                            $activity_date  = date_conv($_POST['activity_date'.$i]);
                            $datails        = $_POST['datails'.$i];
                            $next_date      = date_conv($_POST['next_date'.$i]);
                            $next_fixed_for = $_POST['next_fixed_for'.$i];

                            $sql_txt = "SELECT a.ref_case_header_serial
                                                ,a.counsel_code
                                                ,a.billing_type
                                                ,a.activity_code
                                                ,a.activity_of
                                                ,b.activity_type 
                                                ,b.activity_desc
                                                ,c.associate_name
                                                ,c.bearer_fee
                                                ,d.code_desc
                                        FROM    activity_master b,
                                                case_detail_counsel a 
                                    LEFT JOIN    associate_master c 
                                            ON    (a.counsel_code = c.associate_code )
                                    LEFT JOIN    code_master d
                                            ON    (c.associate_type = d.code_code and d.type_code = '027')
                                        WHERE    a.ref_case_header_serial = '$serial_no'
                                        AND    a.activity_code = b.activity_code";

                            $res_noticeHdr = $this->db->query($sql_txt)->getResultArray();
                            $numRow = count($res_noticeHdr); 
                            // echo '<pre>';print_r($res_noticeHdr);die;

                            if($numRow > 0)
                            { 
                            $m = 0;
                            while($m < $numRow)
                            { 
                                $row = $res_noticeHdr[$m];
                                // echo '<pre>';print_r($ref_case_header_serial);die;
                                $ref_case_header_serial = $row['ref_case_header_serial'];
                                $counsel_code           = $row['counsel_code'];
                                $activity_code          = $row['activity_code'];
                                $activity_of            = $row['activity_of'];
                                $activity_type          = $row['activity_type'];
                                $activity_desc          = $row['activity_desc'];
                                $matter_code            = $_REQUEST['matter_code'];
                                $client_code            = $_REQUEST['client_code'];
                                $header_desc            = strtoupper($_POST['datails'.$i]);   
                                $counsel_name           = $row['associate_name'];
                                $activity_desc          = $row['activity_desc'];
                                $billing_type           = $row['billing_type'];
                                $bearer_fee             = $row['bearer_fee'];
                                $counsel_type           = $row['code_desc'];

                                if($counsel_type == 'CLERK') {$clerk_ind = 'Y';} else {$clerk_ind = 'N';}
                                $activity_date  = date_conv($_POST['activity_date'.$i]);
                                // echo $counsel_code; echo '<br>';
                                // echo $client_code; echo '<br>';
                                // echo $matter_code; echo '<br>';
                                // echo $activity_code; echo '<br>';
                                // die;
                                list($rate_amount,$rate_key) = billrate($counsel_code,$client_code,$matter_code,$activity_code);

                                $rate_key = addslashes($rate_key);

                                $sql_insert = "INSERT INTO $temp_table
                                                    (serial_no,activity_date,type_code,counsel_code,activity_code,activity_of,activity_type,header_desc,activity_desc,counsel_name,clerk_ind,bearer_fee,rate_key,rate_amount)
                                                values
                                                    ('$ref_case_header_serial','$activity_date','$billing_type','$counsel_code','$activity_code','$activity_of','$activity_type','$header_desc','$activity_desc','$counsel_name','$clerk_ind','$bearer_fee','$rate_key','$rate_amount')";

                                $insert_temp = $this->temp_db->query($sql_insert);  
                                $m++; 
                            }

                            }

                        }

                    }



                    //------------------------ Addition of record in BILLINFO_HEADER table  

                    $array =  array('serial_no'                => '',

                                    'branch_code'              => $data['branch_code']['branch_code'],//$global_branch_code,

                                    'bill_date'                => $curr_sysdate, //$global_curr_date2,

                                    'ref_bill_serial_no'       => NULL,

                                    'start_date'               => NULL,

                                    'end_date'                 => NULL,

                                    'client_code'              => $client_code,

                                    'matter_code'              => $matter_code,

                                    'subject_desc'             => NULL,

                                    'other_case_desc'          => $other_case_desc,

                                    'reference_desc'           => NULL,

                                    'ref_billinfo_serial_no'   => NULL,

                                    'bill_amount_inpocket'     => NULL,

                                    'bill_amount_outpocket'    => NULL,

                                    'bill_amount_counsel'      => NULL,

                                    'source_code'              => 'C',

                                    'court_fee_bill_ind'       => $court_fee_bill_ind,

                                    'status_code'              => 'A',

                                    'prepared_by'              => session()->userId, //$global_userid,

                                    'prepared_on'              => $curr_sysdate, //$global_curr_date2,

                                    );

                    $billHdr = $billinfo_header_table->insert($array);
                    //$billSerial1 = $this->db->query("SELECT MAX(`serial_no`) AS maxBillCount FROM `billinfo_header`")->getResultArray()[0];
                    $billSerial= $this->db->insertID();
                    //------------------------ Addition of record in BILLINFO_DETAIL table  
                    $qry_temp = "select * from $temp_table order by activity_date";
                    $tmp_res  = $this->temp_db->query($qry_temp)->getResultArray();
                    $totRow = count($tmp_res);
                    $k = 0;
                    while($k < $totRow)
                    {
                        $tempRow = $tmp_res[$k];
                        $serial_no     = $tempRow['serial_no'];               
                        $activity_date = $tempRow['activity_date'];        
                        $type_code     = $tempRow['type_code'];   
                        $counsel_code  = $tempRow['counsel_code'];     
                        $activity_code = $tempRow['activity_code'];   
                        $activity_of   = $tempRow['activity_of'];  
                        $activity_type = $tempRow['activity_type'];   
                        $header_desc   = $tempRow['header_desc']; 
                        $activity_desc = $tempRow['activity_desc']; 
                        $counsel_name  = $tempRow['counsel_name']; 
                        $clerk_ind     = $tempRow['clerk_ind']; 
                        $bearer_fee    = $tempRow['bearer_fee']; 
                        $rate_key      = $tempRow['rate_key']; 
                        $rate_amount   = $tempRow['rate_amount']; 
                        $clerk_fee     = 0.00;
                        $printer_ind   = 'Y'; 
                        $k++;

                        if($type_code == '1' && $activity_type == 'I')
                        {
                            $cnsl_code = '0000';
                            $io_ind    = $activity_type;
                            $narration = strtoupper($header_desc);
                            if($rate_amount > 0) { $printer_ind = 'Y'; } else { $printer_ind = 'N'; }
                        }

                        else if($type_code == '2' && $activity_type == 'I')
                        {
                            $cnsl_code = $counsel_code;
                            $io_ind    = $activity_type;
                            $narration = 'FEES OF '.strtoupper($counsel_name);
                            if($rate_amount > 0) { $printer_ind = 'Y'; } else { $printer_ind = 'N'; }
                        }

                        else if($type_code == '2' && $activity_type == 'O')
                        {
                            $cnsl_code = $counsel_code;
                            $io_ind    = $activity_type;
                            $narration = strtoupper($counsel_name).' CHARGES';
                        }

                        if($clerk_ind == 'Y')
                        {
                            $clerk_fee = round(($rate/17)+0.49,0);
                            $clerk_amount = $clerk_fee + $bearer_fee; 
                            if($clerk_amount > 0) { $printer_ind = 'Y'; } else { $printer_ind = 'N'; }
                        }

                        if($clerk_ind == 'N')
                        {
                            $clerk_amount = $clerk_fee + $bearer_fee; 
                        }

                        $array = array('ref_billinfo_serial_no'   => $billSerial,

                                        'row_no'                   => $k,

                                        'branch_code'              => $data['branch_code']['branch_code'], //$global_branch_code,

                                        'source_code'              => 'C',

                                        'activity_date'            => $activity_date,

                                        'activity_type'            => $type_code,

                                        'counsel_code'             => $cnsl_code,

                                        'activity_desc'            => $narration,

                                        'io_ind'                   => $io_ind,

                                        'amount'                   => $rate_amount,

                                        'billed_amount'            => $rate_amount,

                                        'printer_ind'              => $printer_ind,

                                        'prn_seq_no'               => $k

                                        );

                        $billrDtl = $billinfo_detail_table->insert($array);

                        if($clerk_amount > 0)

                        {

                            $k++;

                            $narration = 'CLEARKAGE AND BEARER';

                            $array1 = array( 'ref_billinfo_serial_no'  => $billSerial,

                                            'row_no'                   => $k,

                                            'branch_code'              => $data['branch_code']['branch_code'], //$global_branch_code,

                                            'source_code'              => 'C',

                                            'activity_date'            => $activity_date,

                                            'activity_type'            => $type_code,

                                            'counsel_code'             => $cnsl_code,

                                            'activity_desc'            => $narration,

                                            'io_ind'                   => $io_ind,

                                            'amount'                   => $clerk_amount,

                                            'billed_amount'            => $clerk_amount,

                                            'printer_ind'              => 'Y',

                                            'prn_seq_no'               => $k,

                                        );

                            $billrDtl_2 = $billinfo_detail_table->insert($array1);

                        }

                        

                    }



                    //------------------------ Addition of record in BILLINFO_CASES table (Cases)

                    $j=0;

                    for($i=1; $i<=$row_no_case; $i++)

                    {

                        $case_check = isset($_POST['case_check'.$i]) ? $_POST['case_check'.$i] : '';

                        if($case_check == "Y")

                        {

                            $j++ ;   

                            $array =  array('ref_billinfo_serial_no'   => $billSerial,

                                            'row_no'                   => $j,

                                            'branch_code'              => $data['branch_code']['branch_code'], //$global_branch_code,

                                            'ref_case_header_serial'   => $_POST['serial_no'.$i] ,

                                            'case_no'                  => $_POST['othcase_no'.$i],

                                            'subject_desc'             => NULL

                                        );

                            $billInfoCase = $billinfo_cases_table->insert($array);

                        }

                    }



                    //------------------------ Updation of record in CASE_HEADER / CASE_DETAIL_OTHER_CASE table  

                    // echo '<pre>'; print_r($billSerial);die;

                    for($i=1; $i<=$row_no_case; $i++)

                    {

                        $case_check = isset($_POST['case_check'.$i]) ? $_POST['case_check'.$i] : '';

                        if($case_check == "Y")

                        {  

                            $casehdr_sql = "UPDATE case_header SET ref_billinfo_serial_no = '$billSerial', status_code = 'B' WHERE serial_no  = '".$_POST['serial_no'.$i]."'" ; 

                            $casehdr_qry = $this->db->query($casehdr_sql);

                            if($_POST['othcase_no'.$i] == '')

                            { 

                    /*

                            $casehdr_sql = "UPDATE case_header SET ref_billinfo_serial_no = '$billSerial', status_code = 'B' WHERE serial_no  = '".$_POST['serial_no'.$i]."'" ; 

                            $casehdr_qry =& $mdb2->exec($casehdr_sql);

                            if (PEAR::isError($casehdr_qry)) {die($casehdr_qry->getMessage().' - Error in Updation of Record in CASE_HEADER Table ........ ');}

                    */

                            }

                            else

                            { 

                            $caseoth_sql = "UPDATE case_detail_other_case SET ref_billinfo_serial_no = '$billSerial' WHERE ref_case_header_serial  = '".$_POST['serial_no'.$i]."' and case_no = '".$_POST['othcase_no'.$i]."'" ; 

                            $caseoth_qry = $this->db->query($caseoth_sql)->getResultArray();

                            }

                        }  

                    }

                    

                    //------------------------ Addition of record in BILLINFO_CASES table (Other Expenses)

                    for($i=1; $i<= $row_no_other_exp; $i++)

                    {

                        $oe_check = isset($_POST['oe_check'.$i]) ? $_POST['oe_check'.$i] : '';

                        if($oe_check == "Y")
                        {
                            $activity_date = date_conv($_POST['date'.$i]);
                            $narration     = $_POST['details'.$i];
                            $amount        = $_POST['amount'.$i];
                            $expn_table    = $_POST['expn_table'.$i];
                            $matter_code   = $_REQUEST['matter_code'];

                            $k++;
                            $array = array( 'ref_billinfo_serial_no'   => $billSerial,
                                            'row_no'                   => $k,
                                            'branch_code'              => $data['branch_code']['branch_code'], //$global_branch_code,
                                            'source_code'              => 'M',
                                            'activity_date'            => $activity_date,
                                            'activity_type'            => '3',
                                            'counsel_code'             => '0000',
                                            'activity_desc'            => $narration,
                                            'io_ind'                   => 'O',
                                            'amount'                   => $amount,
                                            'billed_amount'            => $amount,
                                            'printer_ind'              => 'Y',
                                            'prn_seq_no'               => $k,
                                        );
                                        $billrDtl = $billinfo_detail_table->insert($array);
                                        // echo "<pre>"; print_r($array); die;

                            if($expn_table == 'COURT')
                            {
                                $updt_stmt_court      = "UPDATE    court_expense
                                                            SET    ref_billinfo_serial_no   = '$billSerial'
                                                        WHERE    matter_code    = '$matter_code'
                                                            AND    status_code = 'D'
                                                            AND    exp_date <= '$activity_date'
                                                            AND    (ref_billinfo_serial_no IS NULL or ref_billinfo_serial_no = 0)";

                                $updt_court   = $this->db->query($updt_stmt_court);
                            }

                            if($expn_table == 'POST')
                            {
                                $updt_stmt_courier    = "UPDATE    courier_expense
                                                            SET    ref_billinfo_serial_no   = '$billSerial'
                                                        WHERE    matter_code    = '$matter_code'
                                                            AND    status_code = 'D'
                                                            AND    consignment_note_date <= '$activity_date'
                                                            AND    (ref_billinfo_serial_no IS NULL or ref_billinfo_serial_no = 0)";

                                $updt_courier   =$this->db->query($updt_stmt_courier);

                            }

                            if($expn_table == 'PHHOTOCOPY')
                            {
                                $updt_stmt_photocopy  = "UPDATE    photocopy_expense
                                                            SET    ref_billinfo_serial_no   = '$billSerial'
                                                        WHERE    matter_code    = '$matter_code'
                                                            AND    status_code = 'D'
                                                            AND    exp_date <= '$activity_date'
                                                            AND    (ref_billinfo_serial_no IS NULL or ref_billinfo_serial_no = 0)";

                                $updt_photocopy   =$this->db->query($updt_stmt_photocopy);

                            }



                            if($expn_table == 'ARBITRATION')

                            {

                                $updt_stmt_arbitrator = "UPDATE    arbitrator_expense

                                                            SET    ref_billinfo_serial_no   = '$billSerial'

                                                        WHERE    matter_code    = '$matter_code'

                                                            AND    status_code = 'D'

                                                            AND    memo_date <= '$activity_date'

                                                            AND    (ref_billinfo_serial_no IS NULL or ref_billinfo_serial_no = 0)";

                                $updt_arbitrator   =$this->db->query($updt_stmt_arbitrator);

                            }



                            if($expn_table == 'STENO')

                            {

                                $updt_stmt_steno      = "UPDATE    steno_expense

                                                            SET    ref_billinfo_serial_no   = '$billSerial'

                                                        WHERE    matter_code    = '$matter_code'

                                                            AND    status_code = 'D'

                                                            AND    memo_date <= '$activity_date'

                                                            AND    (ref_billinfo_serial_no IS NULL or ref_billinfo_serial_no = 0)";

                                $updt_setno   =$this->db->query($updt_stmt_steno);

                            }



                            if($expn_table == 'MISCEXPN')

                            {

                                $updt_stmt_mexpn      = "UPDATE    expense_detail 

                                                            SET    ref_billinfo_serial_no   = '$billSerial'

                                                        WHERE    matter_code    = '$matter_code'

                                                            AND    doc_date      <= '$activity_date'

                                                            AND    (ref_billinfo_serial_no IS NULL or ref_billinfo_serial_no = 0)";

                                $updt_mexpn   =$this->db->query($updt_stmt_mexpn);

                            }

                        }

                    }



                    //------------------------ Calculation of Inpocket,Counsel,Outpocket Amount and Max,Min Date

                    $matter_code   = $_REQUEST['matter_code'];

                    $sql_txt = "SELECT  ifnull(SUM(billed_amount),0) amount,'' dt

                                    FROM  billinfo_detail 

                                    WHERE  ref_billinfo_serial_no = '$billSerial'

                                    AND  activity_type = '1'

                                UNION ALL

                                SELECT  ifnull(SUM(billed_amount),0) amount,'' dt

                                    FROM  billinfo_detail 

                                    WHERE  ref_billinfo_serial_no = '$billSerial'

                                    AND  activity_type = '2'

                                UNION ALL

                                SELECT  ifnull(SUM(billed_amount),0) amount,'' dt

                                    FROM  billinfo_detail 

                                    WHERE  ref_billinfo_serial_no = '$billSerial'

                                    AND  activity_type = '3'

                                UNION ALL

                                SELECT  '',MIN(activity_date) dt

                                    FROM  billinfo_detail 

                                    WHERE  ref_billinfo_serial_no = '$billSerial'

                                UNION ALL

                                SELECT  '',MAX(activity_date) dt

                                    FROM  billinfo_detail 

                                    WHERE  ref_billinfo_serial_no = '$billSerial'";

                    $response = $this->db->query($sql_txt)->getResultArray();
                    $numRow = count($response);
        
                    $total_inpocket_amount = $total_counsel_amount = $total_outpocket_amount = $start_date = $end_date = '';
                    if($numRow > 0) {
                        foreach($response as $res){
        
                            $total_inpocket_amount  = $res['amount'];
                            $total_counsel_amount   = $res['amount'];
                            $total_outpocket_amount = $res['amount'];
                            $start_date             = $res['dt'];
                            $end_date               = $res['dt'];
                        }
                    }

                    //------------------------ Updation of record in BILLINFO_HEADER table

                    $response = $this->db->query("SELECT reference_desc,subject_desc FROM fileinfo_header WHERE matter_code = '$matter_code'")->getResultArray();
                    $numRow = count($response);

                     $reference_desc = $subject_desc = '' ;
                    if($numRow > 0) {
                        foreach($response as $res){ 
                            
                            $reference_desc = stripslashes($res['reference_desc']);
                            $subject_desc   = stripslashes($res['subject_desc']);
                            
                        }
                    } 

                    $array =  array('start_date'               => $start_date,

                                    'end_date'                 => $end_date,

                                    'subject_desc'             => $subject_desc,

                                    'reference_desc'           => $reference_desc, 

                                    'bill_amount_inpocket'     => $total_inpocket_amount, 

                                    'bill_amount_outpocket'    => $total_outpocket_amount,

                                    'bill_amount_counsel'      => $total_counsel_amount, 

                                    );

                    $where = "serial_no = '".$billSerial."'";

                    $billHdr = $billinfo_header_table->update($array,$where);

                    //------------------------ Updation of record in FILEINFO_HEADER table

                    $updt_fileinfoHdr   = $this->db->query("UPDATE fileinfo_header SET last_bill_date = '$curr_sysdate' WHERE matter_code = '$matter_code'");

                    //--------------------------------------------------------------------

                    $msg      = "Please note the generated Bill Serial Number ..... : ".$billSerial ;

                    $drop_sql = $this->temp_db->query("drop table $temp_table"); 

                    session()->setFlashdata('noted_number', $msg);

                    return redirect()->to($data['requested_url']);
                        
        }
        if($finsub=="" || $finsub!="fsub")
            {
                

                $heading           = "Bill Generation";



                $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

                $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;

                $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;

                //$user_option       = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;

                $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

                $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

                $index             = isset($_REQUEST['index'])?$_REQUEST['index']:null;

                $ord               = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;

                $pg                = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;

                $search_val        = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;

                $serial_no         = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

                // echo '<pre>';print_r($user_option);die;

                $client_code = '' ;

        
                if ($user_option == 'Add' )     { $redk = 'readonly' ;  $redv = '';          $disv = ''         ; }

                if ($user_option == 'Edit')     { $redk = ''         ;  $redv = '';          $disv = ''         ; }

                if ($user_option == 'Delete')   { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; }

                if ($user_option == 'View')     { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; }

                if ($user_option == 'Print')    { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; }

                if ($user_option == 'Approve')  { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; }



                $matter_code      = isset($_REQUEST['matter_code'])    ? $_REQUEST['matter_code']:null;  

                $matter_desc      = isset($_REQUEST['matter_desc'])    ? $_REQUEST['matter_desc']:null;  

                $client_code      = isset($_REQUEST['client_code'])    ? $_REQUEST['client_code']:null;  

                $client_name      = isset($_REQUEST['client_name'])    ? $_REQUEST['client_name']:null;  

                $other_case_desc  = isset($_REQUEST['other_case_desc'])    ? $_REQUEST['other_case_desc']:null;  

                $state_name       = isset($_REQUEST['state_name'])    ? $_REQUEST['state_name']:null;  

                $subject_desc     = isset($_REQUEST['subject_desc'])    ? $_REQUEST['subject_desc']:null;  

                $reference_desc   = isset($_REQUEST['reference_desc'])    ? $_REQUEST['reference_desc']:null;  

                $last_remark      = isset($_REQUEST['last_remark'])    ? $_REQUEST['last_remark']:null;  

                $bill_date_upto   = isset($_REQUEST['bill_date_upto']) ? $_REQUEST['bill_date_upto']:null;  

                $bill_date_upto1  = date_conv($bill_date_upto);

                $othcase_cnt      = isset($_REQUEST['othcase_cnt'])    ? $_REQUEST['othcase_cnt']:null;  
                
                $court_fee_bill_ind = isset($_REQUEST['court_fee_bill_ind'])    ? $_REQUEST['court_fee_bill_ind']:null;

                $othcase_dtl      = str_replace('|and|','&', $_REQUEST['othcase_dtl']) ;

                $othcase_nos      = explode("|$|",$othcase_dtl) ;  

                $othcase_count    = count($othcase_nos) ;

                $othcase_str      = '';

                // echo"<pre>";print_r($client_code);die;



                for ($i=1; $i<=($othcase_count-2); $i++)

                {

                    if($i==1) { $othcase_str = "'".$othcase_nos[$i]."'" ; } else { $othcase_str .= ",'".$othcase_nos[$i]."'" ; }

                }



                if ($othcase_cnt == null) 

                {

                $txt = "SELECT  a.serial_no,a.matter_code,a.activity_date,a.next_date,a.next_fixed_for,a.header_desc,'' case_no 

                            FROM  case_header a

                            WHERE  a.matter_code    = '$matter_code'

                            AND  a.activity_date <= '$bill_date_upto1'

                            AND  a.billable_option = 'Y'

                            AND  a.status_code = 'A'

                            AND  (a.ref_billinfo_serial_no is NULL or a.ref_billinfo_serial_no = 0)

                            AND  (a.other_case_desc is NULL or a.other_case_desc = '')

                        ORDER BY  a.activity_date";

                //echo"<pre>";print_r($txt);die;

                }

                else

                {

                $txt = "SELECT  a.serial_no,a.matter_code,a.activity_date,a.next_date,a.next_fixed_for,a.header_desc,b.case_no 

                            FROM  case_header a, case_detail_other_case b

                            WHERE  a.matter_code    = '$matter_code'

                            AND  a.activity_date <= '$bill_date_upto1'

                            AND  a.billable_option = 'Y'

                            AND  a.status_code = 'A'

                            AND  a.serial_no = b.ref_case_header_serial 

                            AND  b.case_no in ('.$othcase_str.') 

                            AND (b.ref_billinfo_serial_no is NULL or b.ref_billinfo_serial_no = 0)

                        ORDER BY  a.activity_date" ;

                }



                    $txt1 = "SELECT a.date, a.details, a.amount, a.expn_table FROM (

                            SELECT     '$bill_date_upto' date

                                    , concat(upper(description),' ',upper(description)) details

                                    , ROUND(SUM(passed_amount)+0.49,0) amount

                                    , 'COURT' expn_table

                            FROM     court_expense

                            WHERE     matter_code = '$matter_code'

                            AND     status_code = 'D'

                            AND     exp_date   <= '$bill_date_upto1'

                            AND     (ref_billinfo_serial_no is NULL or ref_billinfo_serial_no = 0)

                            GROUP BY concat(upper(description),' ',upper(description))

                        UNION ALL

                            SELECT     '$bill_date_upto' date

                                    , 'COURIER EXPENSES UPTO $bill_date_upto' details

                                    , ROUND(SUM(passed_amount)+0.49,0) amount

                                    , 'POST' expn_table

                            FROM     courier_expense

                            WHERE     matter_code = '$matter_code'

                            AND     status_code = 'D'

                            AND     consignment_note_date   <= '$bill_date_upto1'

                            AND     (ref_billinfo_serial_no is NULL or ref_billinfo_serial_no = 0)

                        UNION ALL

                            SELECT     '$bill_date_upto' date

                                    , 'PHOTOCOPY EXPENSES UPTO $bill_date_upto' details

                                    , ROUND(SUM(passed_amount)+0.49,0) amount

                                    , 'PHHOTOCOPY' expn_table

                            FROM     photocopy_expense

                            WHERE     matter_code = '$matter_code'

                            AND     status_code = 'D'

                            AND     exp_date   <= '$bill_date_upto1'

                            AND     (ref_billinfo_serial_no is NULL or ref_billinfo_serial_no = 0)

                        UNION ALL

                            SELECT     '$bill_date_upto' date

                                    , 'ARBITRATION EXPENSES UPTO $bill_date_upto' details

                                    , ROUND(SUM(passed_amount)+0.49,0) amount

                                    , 'ARBITRATION' expn_table

                            FROM     arbitrator_expense

                            WHERE     matter_code = '$matter_code'

                            AND     status_code = 'D'

                            AND     memo_date   <= '$bill_date_upto1'

                            AND     (ref_billinfo_serial_no is NULL or ref_billinfo_serial_no = 0)

                        UNION ALL

                            SELECT     '$bill_date_upto' date

                                    , 'STENOGRAPHER EXPENSES UPTO $bill_date_upto' details

                                    , ROUND(SUM(passed_amount)+0.49,0) amount

                                    , 'STENO' expn_table

                            FROM     steno_expense

                            WHERE     matter_code = '$matter_code'

                            AND     status_code = 'D'

                            AND     memo_date   <= '$bill_date_upto1'

                            AND     (ref_billinfo_serial_no is NULL or ref_billinfo_serial_no = 0)

                        UNION ALL

                            SELECT     '$bill_date_upto' date

                                    , concat(upper(y.expense_desc),' UPTO ','$bill_date_upto') details

                                    , sum(ROUND((x.amount)+0.49,0)) amount

                                    , 'MISCEXPN' expn_table

                            FROM     expense_detail x, expense_master y

                            WHERE     x.matter_code   = '$matter_code'

                            AND     x.doc_date     <= '$bill_date_upto1'

                            AND     x.billable_ind  = 'Y'

                            AND     (x.ref_billinfo_serial_no is NULL or x.ref_billinfo_serial_no = 0)

                            AND     x.expense_type  = y.expense_type

                            AND     x.expense_code  = y.expense_code

                            AND     (x.expense_code != 'A11' AND x.expense_code != 'B04')

                            GROUP BY   y.expense_desc   

                        UNION ALL

                            SELECT     '$bill_date_upto' date

                                    , upper(y.narration) details

                                    , ROUND((x.amount)+0.49,0) amount

                                    , 'MISCEXPN' expn_table

                            FROM     expense_detail x, ledger_trans_dtl y

                            WHERE     x.matter_code   = '$matter_code'

                            AND     x.matter_code   = y.matter_code

                            AND     x.doc_date     <= '$bill_date_upto1'

                            AND     x.billable_ind  = 'Y'

                            AND     (x.ref_billinfo_serial_no is NULL or x.ref_billinfo_serial_no = 0)

                            AND     x.ref_ledger_serial_no = y.ref_ledger_serial_no

                            AND     x.expense_code = y.expense_code

                            AND     (x.expense_code = 'A11' OR x.expense_code = 'B04')

                            GROUP BY  y.narration   

                                ) AS a  

                                    WHERE a.amount > 0";

                        // echo '<pre>';print_r($txt1);die;

                        $report = $this->db->query($txt)->getResultArray() ;

                        $bill_cnt = count($report);

        

                        $report1 = $this->db->query($txt1)->getResultArray() ;

                        $bill_cnt1 = count($report1);

        

                        // try {

                        // $report1[0];

                        // if($params['bill_cnt1'] == 0)  throw new \Exception('No Records Found !!');

                

                        // } catch (\Exception $e) {

                        //     session()->setFlashdata('message', 'No Records Found !!');

                        //     return redirect()->to($_SERVER['REQUEST_URI']);

                        // }

    

                    $params = [

                        "matter_code" => $matter_code,

                        "matter_desc" => $matter_desc,

                        "client_code" => $client_code,

                        "client_name" => $client_name,

                        "other_case_desc" => $other_case_desc,

                        "state_name" => $state_name,

                        "subject_desc" => $subject_desc,

                        "reference_desc" => $reference_desc,

                        "last_remark" => $last_remark,
                        
                        "court_fee_bill_ind" => $court_fee_bill_ind,

                        "bill_cnt" => $bill_cnt,

                        "bill_cnt1" => $bill_cnt1,

                        "requested_url" => $data['requested_url'],

                    ];
$option=$user_option;
                    return view("pages/Billing/bill_generation_matter", compact("report", "report1", "params", "displayId", "data", "option", "permission"));

        
            }
        
        } else{

            if ($user_option == null) {

                return view("pages/Billing/bill_generation_matter", compact("data", "displayId", "option", "permission"));

            } 

        }

    }

    

    public function bill_editing($option = null){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches('demo');

        $user_option = $option; $permission = ($option != 'edit') ? 'readonly disabled' : '';

        $displayId   = ['billsrl_help_id' => '4531'] ; 

        $data['requested_url'] = $this->session->requested_end_menu_url;
        $finsub         = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;

        

        if($this->request->getMethod() == 'post') {

            $row_counter1      = isset($_POST['row_no1'])?$_POST['row_no1']:null; 

            $row_counter2      = isset($_POST['row_no2'])?$_POST['row_no2']:null; 

            $serial_no         = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;



            //object : billinfo_header

            //------------------------------------------------------------------------------

            $billinfo_header_table = $this->db->table("billinfo_header");

            //-------------------------------------------------------------------------------

            // object : billinfo_detail

            //------------------------------------------------------------------------------

            $billinfo_detail_table = $this->db->table("billinfo_detail");
            if($finsub=="fsub")
            {
            $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];

            $user_id        = session()->userId ;

            $curr_sysdate   = $logdt_qry['current_date'];



            $tot_inpocket  = 0;

            $tot_outpocket = 0;

            $tot_counsel   = 0;



            for($i =1; $i <=  $row_counter1; $i++)

            {

                if($_POST['inp_ok_ind'.$i] == "Y")

                {

                    $ind = $_POST['io_ind'.$i];   //echo $_POST['prn_seq_no'.$i].' ';//echo $ind.'<br><br>';

                    if($ind == 'I')

                    {

                        if(!empty($_POST['activity_date'.$i]) && !empty($_POST['counsel_code'.$i]) && !empty($_POST['io_ind'.$i]) && !empty($_POST['billed_amount'.$i]))

                        {

                            $tot_inpocket  = $tot_inpocket  + $_POST['billed_amount'.$i];

                        }

                    }

                    else if($ind == 'C')

                    {

                        if(!empty($_POST['activity_date'.$i]) && !empty($_POST['counsel_code'.$i]) && !empty($_POST['io_ind'.$i]) && !empty($_POST['billed_amount'.$i]))

                        {

                            $tot_counsel  = $tot_counsel  + $_POST['billed_amount'.$i];

                        }

                    }

                }

            }



            for($i =1; $i <=  $row_counter2; $i++)

            {

                if($_POST['out_ok_ind'.$i] == "Y")

                {  //echo $_POST['prn_seq'.$i].' ';

                    if(!empty($_POST['date'.$i]) && !empty($_POST['amount'.$i]))

                    {

                        $tot_outpocket  = $tot_outpocket  + $_POST['amount'.$i];

                    }

                }

            }

            //exit;

            //================================================== WHEN : EDIT ===========================================================



            if($user_option == 'edit')

            {

                $array =  array( 'branch_code'                => $_POST['branch_code'],

                                'end_date'                   => date_conv($_POST['end_date']),

                                'subject_desc'               => strtoupper(stripslashes($_POST['subject_desc'])),

                                'reference_desc'             => strtoupper(stripslashes($_POST['reference_desc'])),

                                'court_fee_bill_ind'         => $_POST['court_fee_bill_ind'],

                                'no_fee_bill_ind'            => $_POST['no_fee_bill_ind'],

                                'direct_counsel_ind'         => $_POST['direct_counsel_ind'],

                                'bill_amount_inpocket_stax'  => $_POST['bill_amount_inpocket_stax'],

                                'bill_amount_outpocket_stax' => $_POST['bill_amount_outpocket_stax'],

                                'bill_amount_counsel_stax'   => $_POST['bill_amount_counsel_stax'],

                                'bill_amount_inpocket_ntax'  => $_POST['bill_amount_inpocket_ntax'],

                                'bill_amount_outpocket_ntax' => $_POST['bill_amount_outpocket_ntax'],

                                'bill_amount_counsel_ntax'   => $_POST['bill_amount_counsel_ntax'],		 

                                'service_tax_inpocket'       => $_POST['service_tax_inpocket'],

                                'service_tax_outpocket'      => $_POST['service_tax_outpocket'],

                                'service_tax_counsel'        => $_POST['service_tax_counsel'],

                                'bill_amount_inpocket'       => $tot_inpocket,

                                'bill_amount_outpocket'      => $tot_outpocket,

                                'bill_amount_counsel'        => $tot_counsel,

                                'service_tax_amount'         => $_POST['total_service_tax'],

                                'updated_by'                 => session()->userId, //$global_userid,

                                'updated_on'                 => $curr_sysdate, //$global_curr_date2,

                            );

            /*

                                'bill_amount_inpocket'       => $_POST['bill_amount_inpocket'],

                                'bill_amount_outpocket'      => $_POST['bill_amount_outpocket'],

                                'bill_amount_counsel'        => $_POST['bill_amount_counsel'],

            */



                        $where = "serial_no = '".$_POST['serial_no']."'";

                        //  print_r($array);



                        $billHdr = $billinfo_header_table->update($array,$where);

                        // if (PEAR::isError($billHdr)) 

                        // {

                        //     die($billHdr->getMessage().' -- billHdr in Edit mode');

                        // }



                //------------------------------------------- MODIFY RECORD :  BILL DETAIL  ---------------------------------------

                $where = "billinfo_detail.ref_billinfo_serial_no = '".$_POST['serial_no']."'";

                $billDtl_del = $billinfo_detail_table->delete($where);

                

                $row_count = $k = 1;

                for($i =1; $row_count <= $row_counter1; $i++)

                {

                    //echo "$i";die;

                    // if($_POST['inp_ok_ind'.$i] == "Y")

                    if(isset($_REQUEST['activity_date'.$i], $_REQUEST['counsel_code'.$i], $_REQUEST['io_ind'.$i])) {

                        if(!empty($_POST['activity_date'.$i]) && !empty($_POST['counsel_code'.$i]) && !empty($_POST['io_ind'.$i])) { //&& !empty($_POST['billed_amount'.$i]){

                            $ind = $_POST['io_ind'.$i];

                            if($ind == 'I') { $activity_type = '1'; } else if($ind == 'C') { $activity_type = '2'; } else {  $activity_type = ''; }

                            if(!empty($_POST['source_code'.$i])) { $source_code = $_POST['source_code'.$i]; } else { $source_code = 'C'; }

                            if($_POST['io_ind'.$i] == 'C')

                                $io_ind = 'O' ;

                            else

                                $io_ind = 'I' ;

                                // echo '<pre>';print_r( $_POST['service_tax_ind'.$i]);die;

                            $array = array( 'ref_billinfo_serial_no'   => $_POST['serial_no'],

                                            'row_no'                   => $k,

                                            'branch_code'              => $_POST['branch_code'],

                                            'source_code'              => $source_code,

                                            'activity_date'            => date_conv($_POST['activity_date'.$i]),

                                            'activity_type'            => $activity_type,

                                            'counsel_code'             => $_POST['counsel_code'.$i],

                                            'activity_desc'            => stripslashes(strtoupper($_POST['activity_desc'.$i])),

                                            'io_ind'                   => $io_ind,

                                            'amount'                   => $_POST['in_amt'.$i],

                                            'billed_amount'            => $_POST['billed_amount'.$i],

                                            'service_tax_ind'          => isset($_POST['service_tax_ind'.$i]) ? $_POST['service_tax_ind'.$i]: '',

                                            'service_tax_percent'      => $_POST['service_tax_percent'.$i],

                                            'service_tax_amount'       => $_POST['service_tax_amount'.$i],

                                            'printer_ind'              => isset($_POST['printer_ind'.$i]) ? $_POST['printer_ind'.$i]: '',

                                            'prn_seq_no'               => $_POST['prn_seq_no'.$i],

                                            );



                            $where = "billinfo_detail.ref_billinfo_serial_no = '".$_POST['serial_no']."'";

                            $billrDtl = $billinfo_detail_table->insert($array,$where);

                            $k++;

                        }

                        $row_count++;

                    }

                }



                for($i =1; $row_count <= $row_counter1 + $row_counter2; $i++)

                {

                    // if($_POST['out_ok_ind'.$i] == "Y")

                    if(isset($_REQUEST['date'.$i], $_REQUEST['amount'.$i])) {

                        if(!empty($_POST['date'.$i]) && !empty($_POST['amount'.$i])) {

                            if(!empty($_POST['out_source_code'.$i])) { $source_code = $_POST['out_source_code'.$i]; } else { $source_code = 'M'; }

                            $array = array( 'ref_billinfo_serial_no'   => $_POST['serial_no'],

                                            'row_no'                   => $k,

                                            'branch_code'              => $_POST['branch_code'],

                                            'source_code'              => $source_code,

                                            'activity_date'            => date_conv($_POST['date'.$i]),

                                            'activity_type'            => '3',

                                            'counsel_code'             => isset($_REQUEST['counsel_code'.$i]) ? $_REQUEST['counsel_code'.$i] : '',

                                            'activity_desc'            => stripslashes(strtoupper($_POST['details'.$i])),

                                            'io_ind'                   => 'O',

                                            'amount'                   => $_POST['out_amt'.$i],

                                            'billed_amount'            => $_POST['amount'.$i],

                                            'service_tax_ind'          => isset($_POST['tax_ind'.$i]) ? $_POST['tax_ind'.$i] : '',

                                            'service_tax_percent'      => $_POST['tax_percent'.$i],

                                            'service_tax_amount'       => $_POST['tax_amount'.$i],

                                            'printer_ind'              => isset($_POST['printer'.$i]) ? $_POST['printer'.$i] : '',

                                            'prn_seq_no'               => $_POST['prn_seq'.$i],

                                            );



                            $where = "billinfo_detail.ref_billinfo_serial_no = '".$_POST['serial_no']."'";

                            $billrDtl2 = $billinfo_detail_table->insert($array,$where);

                            $k++;

                        }

                        $row_count++;

                    }

                }

            //--------------------------------------------------------------------------------------------------------------

            }

            return redirect()->to($data['requested_url']);
        }

        if($finsub=="" || $finsub!="fsub")
        {




            $heading           = "Bill Editing";



            $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

            $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;

            $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;

            //$user_option       = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;

            

            $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

            $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

            $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

            $index             = isset($_REQUEST['index'])?$_REQUEST['index']:null;

            $ord               = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;

            $pg                = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;

            $search_val        = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;



            $serial_no         = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

            $matter_code       = isset($_REQUEST['matter_code'])?$_REQUEST['matter_code']:null;

            $matter_desc       = isset($_REQUEST['matter_desc'])?$_REQUEST['matter_desc']:null;

            $client_code       = isset($_REQUEST['client_code'])?$_REQUEST['client_code']:null;

            $client_name       = isset($_REQUEST['client_name'])?$_REQUEST['client_name']:null;



            if ($user_option == 'Edit')     { $redk = ''         ;  $redv = '';          $disv = ''         ; }

            if ($user_option == 'View')     { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; }



            // echo '<pre>';print_r($serial_no);die;

            $reports = $this->db->query("SELECT * FROM billinfo_header WHERE serial_no = '$serial_no'")->getResultArray()[0];

            $serial_no                   = $reports['serial_no'];

            $branch_code                 = $reports['branch_code'];

            $bill_date                   = $reports['bill_date'];

            $ref_bill_serial_no          = $reports['ref_bill_serial_no'];

            $client_code                 = $reports['client_code'];

            $matter_code                 = $reports['matter_code'];

            $subject_desc                = $reports['subject_desc'];

            $reference_desc              = $reports['reference_desc'];

            $ref_billinfo_serial_no      = $reports['ref_billinfo_serial_no'];

            $bill_amount_inpocket_stax   = $reports['bill_amount_inpocket_stax'];      if(!empty($bill_amount_inpocket_stax))  {$bill_amount_inpocket_stax   = number_format($bill_amount_inpocket_stax,2,'.','')  ; }

            $bill_amount_outpocket_stax  = $reports['bill_amount_outpocket_stax'];     if(!empty($bill_amount_outpocket_stax)) {$bill_amount_outpocket_stax  = number_format($bill_amount_outpocket_stax,2,'.','')  ; }

            $bill_amount_counsel_stax    = $reports['bill_amount_counsel_stax'];       if(!empty($bill_amount_counsel_stax))   {$bill_amount_counsel_stax    = number_format($bill_amount_counsel_stax,2,'.','')  ; }

            $service_tax_inpocket        = $reports['service_tax_inpocket'] ;          if(!empty($service_tax_inpocket))       {$service_tax_inpocket        = number_format($service_tax_inpocket,2,'.','')  ; }

            $service_tax_outpocket       = $reports['service_tax_outpocket'] ;         if(!empty($service_tax_outpocket))      {$service_tax_outpocket       = number_format($service_tax_outpocket,2,'.','')  ; }   

            $service_tax_counsel         = $reports['service_tax_counsel'] ;           if(!empty($service_tax_counsel))        {$service_tax_counsel         = number_format($service_tax_counsel,2,'.','')  ; }

            $bill_amount_inpocket_ntax   = $reports['bill_amount_inpocket_ntax'];      if(!empty($bill_amount_inpocket_ntax))  {$bill_amount_inpocket_ntax   = number_format($bill_amount_inpocket_ntax,2,'.','')  ; }

            $bill_amount_outpocket_ntax  = $reports['bill_amount_outpocket_ntax'];     if(!empty($bill_amount_outpocket_ntax)) {$bill_amount_outpocket_ntax  = number_format($bill_amount_outpocket_ntax,2,'.','')  ; }

            $bill_amount_counsel_ntax    = $reports['bill_amount_counsel_ntax'];       if(!empty($bill_amount_counsel_ntax))   {$bill_amount_counsel_ntax    = number_format($bill_amount_counsel_ntax,2,'.','')  ; } 

            $bill_amount_inpocket        = $reports['bill_amount_inpocket'];           if(!empty($bill_amount_inpocket))       {$bill_amount_inpocket        = number_format($bill_amount_inpocket,2,'.','')  ; }

            $bill_amount_outpocket       = $reports['bill_amount_outpocket'];          if(!empty($bill_amount_outpocket))      {$bill_amount_outpocket       = number_format($bill_amount_outpocket,2,'.','')  ; }  

            $bill_amount_counsel         = $reports['bill_amount_counsel'];            if(!empty($bill_amount_counsel))        {$bill_amount_counsel         = number_format($bill_amount_counsel,2,'.','')  ; }

            $service_tax_amount          = $reports['service_tax_amount'] ;            if(!empty($service_tax_amount))         {$service_tax_amount          = number_format($service_tax_amount,2,'.','')  ; }  

            $source_code                 = $reports['source_code'];

            $status_code                 = $reports['status_code'];

            $prepared_by                 = $reports['prepared_by'];

            $prepared_on                 = $reports['prepared_on'];

            $updated_by                  = $reports['updated_by'];

            $updated_on                  = $reports['updated_on'];

            $approved_by                 = $reports['approved_by'];

            $approved_on                 = $reports['approved_on'];

            

            if($user_option == 'edit' && $status_code != 'A') {

                session()->setFlashdata('message_not_editable', 'Sorry !! This is not EDITABLE');

                return redirect()->to($data['requested_url']);

            }



            $bill_amount_inpocket_ntax   = $bill_amount_inpocket      - $bill_amount_inpocket_stax ;                               

            if(!empty($bill_amount_inpocket_ntax))  {$bill_amount_inpocket_ntax  = number_format($bill_amount_inpocket_ntax,2,'.','')  ; }



            $bill_amount_outpocket_ntax  = $bill_amount_outpocket     - $bill_amount_outpocket_stax ;                              

            if(!empty($bill_amount_outpocket_ntax)) {$bill_amount_outpocket_ntax = number_format($bill_amount_outpocket_ntax,2,'.','')  ; }



            $bill_amount_counsel_ntax    = $bill_amount_counsel       - $bill_amount_counsel_stax ;                                

            if(!empty($bill_amount_counsel_ntax))   {$bill_amount_counsel_ntax   = number_format($bill_amount_counsel_ntax,2,'.','')  ; }



            $total_bill_amount_stax      = $bill_amount_inpocket_stax + $bill_amount_outpocket_stax + $bill_amount_counsel_stax ;  

            if(!empty($total_bill_amount_stax))     {$total_bill_amount_stax     = number_format($total_bill_amount_stax,2,'.','')  ; }



            $total_bill_amount_ntax      = $bill_amount_inpocket_ntax + $bill_amount_outpocket_ntax + $bill_amount_counsel_ntax ;  

            if(!empty($total_bill_amount_ntax))     {$total_bill_amount_ntax     = number_format($total_bill_amount_ntax,2,'.','')  ; }



            $total_amount                = $bill_amount_inpocket      + $bill_amount_outpocket      + $bill_amount_counsel ;       

            if(!empty($total_amount))               {$total_amount               = number_format($total_amount,2,'.','')  ; }



            $total_service_tax           = $service_tax_inpocket      + $service_tax_outpocket      + $service_tax_counsel ;       

            if(!empty($total_service_tax))          {$total_service_tax          = round(number_format($total_service_tax,2,'.',''),0)  ; }



            $net_bill_amount             = $total_amount + number_format(round($total_service_tax,0),2,'.','') ; 

            if(!empty($net_bill_amount)) {$net_bill_amount = number_format($net_bill_amount,2,'.','') ; }

            //  $net_bill_amount             = $total_amount              + $service_tax_inpocket       + $service_tax_outpocket      + $service_tax_counsel ; 



            $client_qry  = $this->db->query("select client_name from client_master where client_code = '$client_code' ")->getResultArray()[0] ;

            $client_name = $client_qry['client_name'] ;



            $qry2 = $this->db->query("SELECT * FROM billinfo_detail WHERE ref_billinfo_serial_no = '$serial_no' and activity_type in('1','2') ORDER BY prn_seq_no,row_no")->getResultArray();

            $qry3 = $this->db->query("SELECT * FROM billinfo_detail WHERE ref_billinfo_serial_no = '$serial_no' and activity_type in('3')     ORDER BY prn_seq_no,row_no")->getResultArray();

            $qry_count2 = count($qry2);

            $qry_count3 = count($qry3);

            // echo '<pre>';print_r($qry2);die;

            

            $stat_qry = $this->db->query("select status_desc from status_master where table_name='billinfo_header' and status_code = '$status_code' ")->getResultArray()[0] ; 

            $status_desc = $stat_qry['status_desc'] ;



            $fin_year   = session()->financialYear ; 

            // echo '<pre>';print_r($fin_year);die;

            $taxper_qry = $this->db->query("select service_tax_percent from params where fin_year = '$fin_year' ")->getResultArray()[0] ; 

            $tax_per    = $taxper_qry['service_tax_percent'] ;

            

            $params = [

                "bill_amount_inpocket_stax"     => $bill_amount_inpocket_stax,

                "client_name"                   => $client_name,

                "matter_desc"                   => $matter_desc,

                "bill_amount_inpocket_ntax"     => $bill_amount_inpocket_ntax,

                "bill_amount_inpocket"          => $bill_amount_inpocket,

                "service_tax_inpocket"          => $service_tax_inpocket,

                "bill_amount_outpocket_stax"    => $bill_amount_outpocket_stax,

                "bill_amount_outpocket_ntax"    => $bill_amount_outpocket_ntax,

                "bill_amount_outpocket"         => $bill_amount_outpocket,

                "service_tax_outpocket"         => $service_tax_outpocket,

                "bill_amount_counsel_stax"      => $bill_amount_counsel_stax,

                "bill_amount_counsel_ntax"      => $bill_amount_counsel_ntax,

                "bill_amount_counsel"           => $bill_amount_counsel,

                "service_tax_counsel"           => $service_tax_counsel,

                "total_bill_amount_stax"        => $total_bill_amount_stax,

                "total_bill_amount_ntax"        => $total_bill_amount_ntax,

                "total_amount"                  => $total_amount,

                "total_service_tax"             => $total_service_tax,

                "net_bill_amount"               => $net_bill_amount,

                "requested_url"                 => base_url($_SERVER['REQUEST_URI']),

                "tax_per"                       => $tax_per,

                "qry_count2"                    => $qry_count2,

                "qry_count3"                    => $qry_count3,

                "status_desc"                   => $status_desc,

                "requested_url"                 => $data['requested_url'],

            ];

// echo "<pre>"; print_r($params); die;

            return view("pages/Billing/bill_editing", compact("reports", "data", "params", "qry2", "qry3", "permission", "option"));

        }





        }else{

            if ($user_option == null) {



                return view("pages/Billing/bill_editing", compact("data", "displayId"));



            }

        }

        

    }



    public function bill_copying($option = null){
        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches('demo');

        $user_option = $option; $permission = ($option == 'view') ? 'readonly disabled' : '';

        $displayId   = ['casesrl_help_id' => '4527','matter_help_id' => '4219'] ;

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $finsub        = isset($_POST['finsub'])?$_POST['finsub']:null;

        if($this->request->getMethod() == 'post') {

            $logdt_qry    = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];

            $user_id      = session()->userId ;

            $fin_year     = session()->financialYear ;

            $curr_sysdate = $logdt_qry['current_date'];

            //

            $row_counter1        = isset($_POST['row_counter1'])?$_POST['row_counter1']:null; 

            // echo "<pre>";print_r($_REQUEST);die;

            $row_counter2        = isset($_POST['row_counter2'])?$_POST['row_counter2']:null; 

            $serial_no           = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

            $matter_code         = isset($_REQUEST['matter_code'])?$_REQUEST['matter_code']:null;

            $other_case_desc     = isset($_REQUEST['other_case_desc'])?$_REQUEST['other_case_desc']:null;

            $court_fee_bill_ind  = isset($_REQUEST['court_fee_bill_ind'])?$_REQUEST['court_fee_bill_ind']:null;

            $other_case_count    = isset($_REQUEST['other_case_count'])?$_REQUEST['other_case_count']:'N';

            //---------------------------------------- OBJECT CREATION ----------------------------------------------------------//

            $billinfo_header_table = $this->db->table("billinfo_header");

            $billinfo_detail_table = $this->db->table("billinfo_detail");

            //---------------------------------------- END OF OBJECT CREATION -----------------------------------------------//

            $tot_inpocket  = 0;

            $tot_counsel   = 0;

            $tot_outpocket = 0;

            //---------------------------------------- INSERT DATA INTO BILLINFO HEADER ---------------------------------------//

            $matqry = $this->db->query("select reference_desc,subject_desc from fileinfo_header where matter_code = '$matter_code' ")->getResultArray()[0] ;

            $new_subject_desc   = $matqry['subject_desc'] ;

            $new_reference_desc = $matqry['reference_desc'] ;

            

          

            $array =  array(   'serial_no'                => '',

                               'branch_code'              => $data['branch_code']['branch_code'], //$global_branch_code,

                               'bill_date'                => $curr_sysdate, //$global_curr_date2,

                               'ref_bill_serial_no'       => NULL,

                               'start_date'               => NULL,

                               'end_date'                 => date_conv($_POST['bill_date_upto']),

                               'client_code'              => $_POST['client_code'],

                               'matter_code'              => $_POST['matter_code'],

                               'subject_desc'             => $new_subject_desc,

                               'other_case_desc'          => strtoupper(stripslashes($other_case_desc)),

                               'reference_desc'           => $new_reference_desc,

                               'ref_billinfo_serial_no'   => NULL,

                               'bill_amount_inpocket'     => NULL,

                               'bill_amount_outpocket'    => NULL,

                               'bill_amount_counsel'      => NULL,

                               'source_code'              => NULL,

                               'court_fee_bill_ind'       => $court_fee_bill_ind,

                               'status_code'              => 'A',

                               'prepared_by'              => $user_id, //$global_userid,

                               'prepared_on'              => $curr_sysdate, //$global_curr_date2,

                               'bill_copied_by'           => $user_id, //$global_userid,

                               'bill_copied_on'           => $curr_sysdate, //$global_curr_date2,

                               'bill_copied_from_sl_no'   => $_POST['serial_no'],

                               'bill_copied_from_bill_no' => $_POST['bill_no'],

                             );

          

                        $billHdr = $billinfo_header_table->insert($array);

                        //echo $this->db->insertID();die;

            //---------------------------------------- LAST INSERT ID FOR BILLINFO HEADER ------------------------------------//

                   //-----------------------------------------------------------------------------

                        // $billSerial1 = $this->db->query("SELECT MAX(`serial_no`) AS maxBillCount FROM `billinfo_header`")->getResultArray()[0];

                        // $billSerial=$billSerial1['maxBillCount'];

                        $billSerial = $this->db->insertID();

            //---------------------------------------------------------------------------------------------------------------//

          

                 //---------------------------- WRITING DATA INTO BILLINFO DETAIL TABLE ---------------------

                 $prnseq = 0 ;

                 $k=0;

                 for($i =1; $i <= $row_counter1; $i++)

                 {

                    $copy_ind = isset($_POST['copy_ind'.$i]) ? $_POST['copy_ind'.$i] :'';

                    if($copy_ind == "Y")

                    {

                            if($_POST['activity_type'.$i] == '1')

                            {

                                $tot_inpocket  = $tot_inpocket   + $_POST['billed_amount'.$i];

                            }

                            if($_POST['activity_type'.$i] == '2')

                            {

                                $tot_counsel   = $tot_counsel    + $_POST['billed_amount'.$i];

                            }

                            if($_POST['activity_type'.$i] == '3')

                            {

                                $tot_outpocket = $tot_outpocket  + $_POST['billed_amount'.$i];

                            }

                            //-----------

                            $prnseq = $_POST['prn_seq_no'.$i] ; 

                            $k++;

                            $array = array( 'ref_billinfo_serial_no'   => $billSerial,

                                            'row_no'                   => $k,

                                            'branch_code'              =>  $data['branch_code']['branch_code'], //$global_branch_code,

                                            'source_code'              => $_POST['source_code'.$i],

                                            'activity_date'            => date_conv($_POST['activity_date'.$i]),

                                            'activity_type'            => $_POST['activity_type'.$i],

                                            'counsel_code'             => $_POST['counsel_code'.$i],

                                            'activity_desc'            => $_POST['activity_desc'.$i],

                                            'io_ind'                   => $_POST['io_ind'.$i],

                                            'amount'                   => $_POST['billed_amount'.$i],

                                            'billed_amount'            => $_POST['billed_amount'.$i],

                                            'printer_ind'              => $_POST['printer_ind'.$i],

                                            'prn_seq_no'               => $_POST['prn_seq_no'.$i],

                                          );

                     // echo '<pre>';print_r($array);die;

                            $billrDtl = $billinfo_detail_table->insert($array);

                    }

                 }

          

                 for($i =1; $i <=  $row_counter2; $i++)

                 {

                    $new_copy_ind_i = isset($_POST['new_copy_ind_i'.$i]) ? $_POST['new_copy_ind_i'.$i] :'';

                    if($new_copy_ind_i == "Y")

                    {

                            if(!empty($_POST['srl_no'.$i]))

                            {

                                $source_code   = 'C' ;

                                $activity_type = '1';

                                $io_ind        = 'I' ;

                                $tot_inpocket  = $tot_inpocket + $_POST['amount'.$i];

          

                                $matter_code = $_POST['matter_code'];

                                $bill_date   = date_conv($_POST['bill_date_upto']);

          

                                $updt_stmt_case = "UPDATE case_header

                                                      SET ref_billinfo_serial_no   = '$billSerial', status_code = 'B'

                                                    WHERE matter_code    = '$matter_code'

                                                      AND activity_date <= '$bill_date'

                                                      AND billable_option = 'Y'

                                                      AND ref_billinfo_serial_no IS NULL";

                                $updt_case = $this->db->query($updt_stmt_case);

                            }

                            else

                            {

                                $source_code   = 'M' ;

                                $activity_type = '3';

                                $io_ind        = 'O' ;

                                $tot_outpocket = $tot_outpocket  + $_POST['amount'.$i];

                            }

          

                            $prnseq = $prnseq + 1 ; 

                            $k++;

                            $array = array( 'ref_billinfo_serial_no'   => $billSerial,

                                            'row_no'                   => $k,

                                            'branch_code'              => $global_branch_code,

                                            'source_code'              => $source_code,

                                            'activity_date'            => date_conv($_POST['date'.$i]),

                                            'activity_type'            => $activity_type,

                                            'counsel_code'             => '0000',

                                            'activity_desc'            => $_POST['details'.$i],

                                            'io_ind'                   => $io_ind,

                                            'amount'                   => $_POST['amount'.$i],

                                            'billed_amount'            => $_POST['amount'.$i],

                                            'printer_ind'              => 'Y' ,

                                            'prn_seq_no'               => $prnseq,

                                          );

                            $billrDtl = $billinfo_detail_table->insert($array);

                    }

                 }

          

              //------------------------------------ UPDATING BILLINFO HEADER TABLE ------------------------------------------

              $array =  array( 'bill_amount_inpocket'     => $tot_inpocket,

                               'bill_amount_outpocket'    => $tot_outpocket,

                               'bill_amount_counsel'      => $tot_counsel,

                             );

          

          

                        $where = "serial_no = '".$billSerial."'";

                        $billHdr = $billinfo_header_table->update($array,$where);



              //------------------------------------------- UPDATE SQL STATEMENT --------------------------------------------

                $matter_code = $_POST['matter_code'];

                $bill_date   = date_conv($_POST['bill_date_upto']);



                $updt_stmt_court      = "UPDATE court_expense

                                            SET ref_billinfo_serial_no   = '$billSerial'

                                          WHERE    matter_code    = '$matter_code'

                                            AND    status_code = 'C'

                                            AND    exp_date <= '$bill_date'

                                            AND    ref_billinfo_serial_no IS NULL";

          

                $updt_stmt_courier    = "UPDATE courier_expense

                                            SET ref_billinfo_serial_no   = '$billSerial'

                                          WHERE    matter_code    = '$matter_code'

                                            AND    status_code = 'C'

                                            AND    consignment_note_date <= '$bill_date'

                                            AND    ref_billinfo_serial_no IS NULL";

          

                $updt_stmt_photocopy  = "UPDATE photocopy_expense

                                            SET ref_billinfo_serial_no   = '$billSerial'

                                          WHERE    matter_code    = '$matter_code'

                                            AND    status_code = 'C'

                                            AND    exp_date <= '$bill_date'

                                            AND    ref_billinfo_serial_no IS NULL";

          

                $updt_stmt_arbitrator = "UPDATE arbitrator_expense

                                            SET ref_billinfo_serial_no   = '$billSerial'

                                          WHERE    matter_code    = '$matter_code'

                                            AND    status_code = 'C'

                                            AND    memo_date <= '$bill_date'

                                            AND    ref_billinfo_serial_no IS NULL";

          

                $updt_stmt_steno      = "UPDATE steno_expense

                                            SET ref_billinfo_serial_no   = '$billSerial'

                                          WHERE    matter_code    = '$matter_code'

                                            AND    status_code = 'C'

                                            AND    memo_date <= '$bill_date'

                                            AND    ref_billinfo_serial_no IS NULL";

          

                $updt_court   = $this->db->query($updt_stmt_court);

          

                $updt_courier   = $this->db->query($updt_stmt_courier);

          

                $updt_photocopy   = $this->db->query($updt_stmt_photocopy);

          

                $updt_arbitrator   = $this->db->query($updt_stmt_arbitrator);

          

                $updt_setno   = $this->db->query($updt_stmt_steno);

                // echo '<pre>'; print_r ($_REQUEST['oth_case_row1']); die;

              //--------------------------- OTHER CASE TAGGED : INSERT INTO BILLINFO_CASES ------------------------------------------------

              $oth_row_no = $oth_case_no = $oth_subj_desc = '';

              for($k=1;$k<=$other_case_count;$k++)

              {

                $oth_case_row = isset($_REQUEST['oth_case_row'.$k]) ? $_REQUEST['oth_case_row'.$k] : false;

                if($oth_case_row)

                {

                  $oth_row_no    .= $_REQUEST['oth_row_no'.$k];

                  $oth_case_no   .= $_REQUEST['oth_case_no'.$k];

                  $oth_subj_desc .= $_REQUEST['oth_subj_desc'.$k];

                  $inst_stmt = "INSERT INTO billinfo_cases (ref_billinfo_serial_no, row_no, branch_code, ref_case_header_serial, case_no, subject_desc)

                                    select  '$billSerial', row_no, branch_code, ref_case_header_serial, '$oth_case_no', '$oth_subj_desc'

                                    from billinfo_cases

                                    where ref_billinfo_serial_no = '$serial_no'

                                    and row_no = '$oth_row_no'";

                  $isnt_case   = $this->db->query($inst_stmt);

                }

              }

          

                $message = 'Please note New Bill Serial No : ' . $billSerial ;



                session()->setFlashdata('noted_number', $message);

                return redirect()->to($data['requested_url']);





                // echo '<script type="text/javascript">

                // alert("' . $message . '");

                // window.location.href = "'.$data['requested_url'].'"; ; 

                // </script>'; 



        }else{

            if ($user_option == null) {

                return view("pages/Billing/bill_copying", compact("displayId", "data", "option", "permission"));

            } else if ($user_option == 'proceed') {



                $serial_no    = $_REQUEST['serial_no'];

    

                $txt = "SELECT  * FROM  billinfo_detail WHERE  ref_billinfo_serial_no = '$serial_no' ORDER BY  prn_seq_no" ;

    

                $res = $this->db->query($txt)->getResultArray();

                $count = count($res);

                $cur_date = date('d-m-Y');

                

                $bill_response = [

                    "displayId" => $displayId,

                    "data" => $data,

                    "option" => $option,

                    "permission" => $permission,

                    "records" => $res,

                    "count" => $count,

                    "cur_date" => $cur_date,

                ];

                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode($bill_response));

                // return view("pages/Billing/bill_copying", compact("displayId", "data", "option", "count", "res", "count", "cur_date"));

            } else if ($user_option == 'nextproceed') {

                $matter_code     = $_REQUEST['matter_code'];

                $bill_date_upto  = $_REQUEST['bill_date_upto'];

                $bill_date_upto1 = date_conv($bill_date_upto);

                //

                $txt = "SELECT a.serial_no,a.date, a.details, a.amount FROM (

                            SELECT    serial_no

                                    ,date_format(activity_date,'%d-%m-%Y') date

                                    ,header_desc details

                                    ,'0.00' amount  

                            FROM    case_header 

                            WHERE    matter_code    = '$matter_code'

                            AND    activity_date <= '$bill_date_upto1'

                            AND    billable_option = 'Y'

                            AND    (ref_billinfo_serial_no is NULL or ref_billinfo_serial_no = 0)

                        UNION ALL

                            SELECT    '' serial_no

                                    ,'$bill_date_upto' date

                                    , concat(upper(description),' ',upper(description2)) details

                                    , ROUND(SUM(ifnull(passed_amount,0))+0.49) amount

                            FROM     court_expense

                            WHERE     matter_code = '$matter_code'

                            AND     status_code = 'D'

                            AND     exp_date   <= '$bill_date_upto1'

                            AND    (ref_billinfo_serial_no is NULL or ref_billinfo_serial_no = 0)

                            GROUP BY concat(upper(description),' ',upper(description2))  

                        UNION ALL

                            SELECT     '' serial_no

                                    ,'$bill_date_upto' date

                                    , 'COURIER EXPENSES UPTO $bill_date_upto' details

                                    , ROUND(SUM(ifnull(passed_amount,0))+0.49) amount

                            FROM     courier_expense

                            WHERE     matter_code = '$matter_code'

                            AND     status_code = 'D'

                            AND     consignment_note_date   <= '$bill_date_upto1'

                            AND    (ref_billinfo_serial_no is NULL or ref_billinfo_serial_no = 0)

                        UNION ALL

                            SELECT     '' serial_no

                                    ,'$bill_date_upto' date

                                    , 'PHOTOCOPY EXPENSES UPTO $bill_date_upto' details

                                    , ROUND(SUM(ifnull(passed_amount,0))+0.49) amount

                            FROM     photocopy_expense

                            WHERE     matter_code = '$matter_code'

                            AND     status_code = 'D'

                            AND     exp_date   <= '$bill_date_upto1'

                            AND    (ref_billinfo_serial_no is NULL or ref_billinfo_serial_no = 0)

                        UNION ALL

                            SELECT     '' serial_no

                                    ,'$bill_date_upto' date

                                    , 'ARBITRATION EXPENSES UPTO $bill_date_upto' details

                                    , ROUND(SUM(ifnull(passed_amount,0))+0.49) amount

                            FROM     arbitrator_expense

                            WHERE     matter_code = '$matter_code'

                            AND     status_code = 'D'

                            AND     memo_date   <= '$bill_date_upto1'

                            AND    (ref_billinfo_serial_no is NULL or ref_billinfo_serial_no = 0)

                        UNION ALL

                            SELECT     '' serial_no

                                    ,'$bill_date_upto' date

                                    , 'STENOGRAPHER EXPENSES UPTO $bill_date_upto' details

                                    , ROUND(SUM(ifnull(passed_amount,0))+0.49) amount

                            FROM     steno_expense

                            WHERE     matter_code = '$matter_code'

                            AND     status_code = 'D'

                            AND     memo_date   <= '$bill_date_upto1'

                            AND    (ref_billinfo_serial_no is NULL or ref_billinfo_serial_no = 0)

                                ) AS a

                            WHERE a.amount IS NOT NULL 

                            AND a.serial_no IS NOT NULL" ;



                $res2 = $this->db->query($txt)->getResultArray();

                $count2 = count($res2);



                $bill_response = [

                    "displayId" => $displayId,

                    "data" => $data,

                    "option" => $option,

                    "permission" => $permission,

                    "records" => $res2,

                    "count" => $count2,

                ];

                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode($bill_response));



                // return view("pages/Billing/bill_copying", compact("displayId", "data", "option", "permission", "res2", "count2"));

                // return view("pages/Billing/bill_copying", ["displayId" => $displayId, "data" => $data, "option" => $option, "permission" => $permission, "res2" => $res2, "count2" => $count2]);

            }

        }

    }



    public function bill_approval($option = null){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches('demo');

        $user_option = $option; $permission = ($option == 'view') ? 'readonly disabled' : '';

        $displayId   = ['billsrl_help_id' => '4531'] ; 

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $finsub  = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;

        if($this->request->getMethod() == 'post') {



            $logdt_qry    = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];

            $user_id      = session()->userId ;

            $fin_year     = session()->financialYear ;

            $curr_sysdate = $logdt_qry['current_date'];



            $row_no     = isset($_POST['row_no'])?$_POST['row_no']:null; 

            $serial_no  = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

            

            // object : billinfo_header

            //------------------------------------------------------------------------------

            $billinfo_header_table = $this->db->table("billinfo_header");



            //-------------------------------------------------------------------------------

            // object : billinfo_detail

            //------------------------------------------------------------------------------

            $billinfo_detail_table = $this->db->table("billinfo_detail");

            //------------------------------------------------------------------------------

            // object : bill_detail

            //------------------------------------------------------------------------------

            $bill_detail_table = $this->db->table("bill_detail");

            if($finsub=="fsub")
            {

            $res = $this->db->query("SELECT * from bill_serial WHERE fin_year = '$fin_year'")->getResultArray(); 



            //$res =& $mdb2->query($txt);

            

            $cnt = count($res);



            $first_chr = '';

            $second_chr = '';

            $no = 0;

            $bill_no = '';

            if($cnt < 1)

            {

                $first_chr = 'A';

                $second_chr = 'A';

                $no = 1;

                $bill_no = $first_chr.$second_chr.str_pad($no,2,'0',STR_PAD_LEFT);



                $insert_stmt = "INSERT INTO bill_serial(fin_year,bill_1st_char_code,bill_2nd_char_code,bill_3rd4th_char)

                                        VALUES('$fin_year','$first_chr','$second_chr','$no')"; 



                $insert_bill   = $this->db->query($insert_stmt);



            }

            else

            {

                $row = $res[0];

                

                $first_chr  = $row['bill_1st_char_code'];

                $second_chr = $row['bill_2nd_char_code'];

                $no         = $row['bill_3rd4th_char'];

                

                if($no < 99)

                {

                $no = $no + 1;

                }

                else

                {

                $no = 1;

                if($second_chr != 'Z')

                {

                    $tmp_second_chr = $second_chr;

                    $second_chr     = chr(ord($tmp_second_chr)+1);

                }

                else

                {

                    $second_chr     = 'A';

                    if($first_chr != 'Z')

                    {

                        $tmp_first_chr = $first_chr;

                        $first_chr     = chr(ord($tmp_first_chr)+1);

                    }

                    else

                    {

                        $first_chr = 'A';

                    }

                }

                }



                $bill_no = $first_chr.$second_chr.str_pad($no,2,'0',STR_PAD_LEFT);

                

                $updt_stmt = "UPDATE bill_serial

                                SET fin_year           = '$fin_year',

                                    bill_1st_char_code = '$first_chr',

                                    bill_2nd_char_code = '$second_chr',

                                    bill_3rd4th_char   = '$no'

                                WHERE fin_year   = '$fin_year'";



                $update_bill   = $this->db->query($updt_stmt);

            }



            //------------------------------------------------------//

            //======================= END ==========================//

            //------------------------------------------------------//

            $my_qry1    = $this->db->query("select if(matter_desc1 != '', concat(matter_desc1,' : ',matter_desc2), matter_desc2) matter_desc from fileinfo_header where matter_code = '$_POST[matter_code]' ")->getResultArray()[0] ;

            $bill_cause = $my_qry1['matter_desc'] ;

            

            $my_qry_crt    = $this->db->query("select court_code, reference_type_code from fileinfo_header where matter_code = '$_POST[matter_code]' ")->getResultArray()[0]  ;

            $court_code = $my_qry_crt['court_code'] ;

            $reference_type_code = $my_qry_crt['reference_type_code'] ;

            

                

                // $global_curr_date2 ;

                

                $inst_mm1   =  substr($curr_sysdate,5,1) ; 

                $inst_mm2   =  substr($curr_sysdate,6,1) ; 

            // 2016-12-12

                $month_code =  $inst_mm1.''.''.$inst_mm2 ; 

                

                $my_qry_mnth    = $this->db->query("SELECT month_descs FROM months where month_no = '$month_code' ")->getResultArray()[0] ;

                $month_name = $my_qry_mnth['month_descs'] ;

                

            // Bill Detail



            $array =   array( 'serial_no'                => '',

                                'branch_code'              => $_POST['branch_code_copy'],

                                'fin_year'                 =>$fin_year,

                                'bill_no'                  => $bill_no,

                                'bill_date'                => $curr_sysdate,

                                'month_code'               => $month_code, 

                                'month_name'               => $month_name,

                                'initial_code'             => $_POST['initial_code'],

                                'bill_cause'               => $bill_cause,

                                'client_code'              => $_POST['client_code'],

                                'matter_code'              => $_POST['matter_code'],

                                'court_code'               => $court_code,

                                'address_code'             => $_POST['billing_addr_code'],

                                'attention_code'           => $_POST['billing_attn_code'],

                                'reference_type_code'      => $reference_type_code,

                                'advance_amount_inpocket'  => NULL,

                                'advance_amount_outpocket' => NULL,

                                'advance_amount_counsel'   => NULL,

                                'bill_amount_inpocket_stax'  => $_POST['bill_amount_inpocket_stax'],

                                'service_tax_inpocket'       => $_POST['service_tax_inpocket'],

                                'bill_amount_outpocket_stax' => $_POST['bill_amount_outpocket_stax'],

                                'service_tax_outpocket'      => $_POST['service_tax_outpocket'],

                                'bill_amount_counsel_stax'   => $_POST['bill_amount_counsel_stax'],

                                'service_tax_counsel'        => $_POST['service_tax_counsel'],

                                'bill_amount_inpocket_ntax'  => $_POST['bill_amount_inpocket_ntax'],

                                'bill_amount_outpocket_ntax' => $_POST['bill_amount_outpocket_ntax'],

                                'bill_amount_counsel_ntax'   => $_POST['bill_amount_counsel_ntax'],

                                'bill_amount_inpocket'       => $_POST['bill_amount_inpocket'],

                                'bill_amount_outpocket'      => $_POST['bill_amount_outpocket'],

                                'bill_amount_counsel'        => $_POST['bill_amount_counsel'],

                                'service_tax_amount'         => $_POST['total_service_tax'],

                                'realise_amount_inpocket'  => NULL,

                                'realise_amount_outpocket' => NULL,

                                'realise_amount_counsel'   => NULL,

                                'tds_amount'               => NULL,

                                'deficit_amount_inpocket'  => NULL,

                                'deficit_amount_outpocket' => NULL,

                                'deficit_amount_counsel'   => NULL,

                                'part_full_ind'            => NULL,

                                'received_date'            => NULL,

                                'booked_amount_inpocket'   => NULL,

                                'booked_amount_outpocket'  => NULL,

                                'booked_amount_counsel'    => NULL,

                                'booked_pljv_inpocket'     => NULL,

                                'booked_pljv_outpocket'    => NULL,

                                'booked_pljv_counsel'      => NULL,

                                'cancel_ind'               => NULL,

                                'pl_transfer_ind'          => NULL,

                                'ref_doc_serial_no'        => NULL,

                                'collectable_ind'          => 'C',

                                'court_fee_bill_ind'       => $_POST['court_fee_bill_ind'] ,

                            );

                        //print_r($array);



                        $bill = $bill_detail_table->insert($array);



                        //-----------------------------------------------------------------------------

                        // $billSerial1 = $this->db->query("SELECT MAX(`serial_no`) AS maxBillCount FROM `bill_detail`")->getResultArray()[0];

                        // $billSerial=$billSerial1['maxBillCount'];

                        $billSerial = $this->db->insertID();

                        

            //---------------------------------------- UPDATE DATA INTO BILLINFO HEADER ---------------------------------------//

            $serial_no = $_POST['serial_no'];

            $array     =  array('ref_bill_serial_no' => $billSerial,

                                'status_code'        => 'B',

                                'approved_by'        => $user_id,

                                'approved_on'        => $curr_sysdate,

                                );

            $where   = "serial_no = '".$serial_no."'";

            $billHdr = $billinfo_header_table->update($array,$where);



            $message = 'Please note the Bill No : ' . $bill_no ;



            session()->setFlashdata('noted_number', $message);

            return redirect()->to($data['requested_url']);

            

                // echo '<script type="text/javascript">

                // alert("' . $message . '");

                // window.location.href = "'.$data['requested_url'].'"; ; 

                // </script>'; 

                            }
            if($finsub=="" || $finsub!="fsub")
            {
                

                $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

                $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;

                $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;

                $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

                $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

                $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

                $index             = isset($_REQUEST['index'])?$_REQUEST['index']:null;

                $ord               = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;

                $pg                = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;

                $search_val        = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;

                //$user_option       = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;

                $serial_no         = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

                $matter_desc       = isset($_REQUEST['matter_desc'])?$_REQUEST['matter_desc']:null;

                $client_name       = isset($_REQUEST['client_name'])?$_REQUEST['client_name']:null;



                // echo '<pre>';print_r($matter_desc);die;

                $reports = $this->db->query("SELECT * FROM billinfo_header WHERE serial_no = '$serial_no'")->getResultArray()[0];

                //$client_name = $this->db->query("SELECT client_name FROM billinfo_header WHERE serial_no = '$serial_no'")->getResultArray()[0]



                $serial_no              = $reports['serial_no'];

                $branch_code            = $reports['branch_code'];

                $bill_date              = $reports['bill_date'];

                $ref_bill_serial_no     = $reports['ref_bill_serial_no'];

                $start_date             = $reports['start_date'];

                $end_date               = $reports['end_date'];

                $client_code            = $reports['client_code'];

                $matter_code            = $reports['matter_code'];

                $subject_desc           = $reports['subject_desc'];

                $other_case_desc        = $reports['other_case_desc'];

                $reference_desc         = $reports['reference_desc'];

                $ref_billinfo_serial_no = $reports['ref_billinfo_serial_no'];

                $bill_amount_inpocket_stax   = $reports['bill_amount_inpocket_stax'];      if(!empty($bill_amount_inpocket_stax))  {$bill_amount_inpocket_stax   = number_format($bill_amount_inpocket_stax,2,'.','')  ; }

                $bill_amount_outpocket_stax  = $reports['bill_amount_outpocket_stax'];     if(!empty($bill_amount_outpocket_stax)) {$bill_amount_outpocket_stax  = number_format($bill_amount_outpocket_stax,2,'.','')  ; }

                $bill_amount_counsel_stax    = $reports['bill_amount_counsel_stax'];       if(!empty($bill_amount_counsel_stax))   {$bill_amount_counsel_stax    = number_format($bill_amount_counsel_stax,2,'.','')  ; }

                $service_tax_inpocket        = $reports['service_tax_inpocket'] ;          if(!empty($service_tax_inpocket))       {$service_tax_inpocket        = number_format($service_tax_inpocket,2,'.','')  ; }

                $service_tax_outpocket       = $reports['service_tax_outpocket'] ;         if(!empty($service_tax_outpocket))      {$service_tax_outpocket       = number_format($service_tax_outpocket,2,'.','')  ; }   

                $service_tax_counsel         = $reports['service_tax_counsel'] ;           if(!empty($service_tax_counsel))        {$service_tax_counsel         = number_format($service_tax_counsel,2,'.','')  ; }

                $bill_amount_inpocket_ntax   = $reports['bill_amount_inpocket_ntax'];      if(!empty($bill_amount_inpocket_ntax))  {$bill_amount_inpocket_ntax   = number_format($bill_amount_inpocket_ntax,2,'.','')  ; }

                $bill_amount_outpocket_ntax  = $reports['bill_amount_outpocket_ntax'];     if(!empty($bill_amount_outpocket_ntax)) {$bill_amount_outpocket_ntax  = number_format($bill_amount_outpocket_ntax,2,'.','')  ; }

                $bill_amount_counsel_ntax    = $reports['bill_amount_counsel_ntax'];       if(!empty($bill_amount_counsel_ntax))   {$bill_amount_counsel_ntax    = number_format($bill_amount_counsel_ntax,2,'.','')  ; } 

                $bill_amount_inpocket        = $reports['bill_amount_inpocket'];           if(!empty($bill_amount_inpocket))       {$bill_amount_inpocket        = number_format($bill_amount_inpocket,2,'.','')  ; }

                $bill_amount_outpocket       = $reports['bill_amount_outpocket'];          if(!empty($bill_amount_outpocket))      {$bill_amount_outpocket       = number_format($bill_amount_outpocket,2,'.','')  ; }  

                $bill_amount_counsel         = $reports['bill_amount_counsel'];            if(!empty($bill_amount_counsel))        {$bill_amount_counsel         = number_format($bill_amount_counsel,2,'.','')  ; }

                $service_tax_amount          = $reports['service_tax_amount'] ;            if(!empty($service_tax_amount))         {$service_tax_amount          = number_format(round($service_tax_amount,0),2,'.','')  ; }  

                $source_code            = $reports['source_code'];

                $court_fee_bill_ind     = $reports['court_fee_bill_ind'];

                $status_code            = $reports['status_code'];

                $message = 'Sorry !!! ..... This is not APPROVABLE .......';

                if($user_option == 'approve' && $status_code != 'A') {



                    session()->setFlashdata('valid_message', $message);

                    return redirect()->to($data['requested_url']);

                    // echo 'abd';die;

                    // echo '<script type="text/javascript">

                    //     alert("' . $message . '");

                    //     window.location.href = "'.$data['requested_url'].'"; 

                    // </script>';   

                }

                $prepared_by            = $reports['prepared_by'];

                $prepared_on            = $reports['prepared_on'];

                $updated_by             = $reports['updated_by'];

                $updated_on             = $reports['updated_on'];

                $approved_by            = $reports['approved_by'];

                $approved_on            = $reports['approved_on'];



                $bill_amount_inpocket_ntax   = $bill_amount_inpocket      - $bill_amount_inpocket_stax ;                               if(!empty($bill_amount_inpocket_ntax))  {$bill_amount_inpocket_ntax  = number_format($bill_amount_inpocket_ntax,2,'.','')  ; }

                $bill_amount_outpocket_ntax  = $bill_amount_outpocket     - $bill_amount_outpocket_stax ;                              if(!empty($bill_amount_outpocket_ntax)) {$bill_amount_outpocket_ntax = number_format($bill_amount_outpocket_ntax,2,'.','')  ; }

                $bill_amount_counsel_ntax    = $bill_amount_counsel       - $bill_amount_counsel_stax ;                                if(!empty($bill_amount_counsel_ntax))   {$bill_amount_counsel_ntax   = number_format($bill_amount_counsel_ntax,2,'.','')  ; }

                $total_bill_amount_stax      = $bill_amount_inpocket_stax + $bill_amount_outpocket_stax + $bill_amount_counsel_stax ;  if(!empty($total_bill_amount_stax))     {$total_bill_amount_stax     = number_format($total_bill_amount_stax,2,'.','')  ; }

                $total_bill_amount_ntax      = $bill_amount_inpocket_ntax + $bill_amount_outpocket_ntax + $bill_amount_counsel_ntax ;  if(!empty($total_bill_amount_ntax))     {$total_bill_amount_ntax     = number_format($total_bill_amount_ntax,2,'.','')  ; }

                $total_amount                = $bill_amount_inpocket      + $bill_amount_outpocket      + $bill_amount_counsel ;       if(!empty($total_amount))               {$total_amount               = number_format($total_amount,2,'.','')  ; }

                $total_service_tax           = $service_tax_inpocket      + $service_tax_outpocket      + $service_tax_counsel ;       if(!empty($total_service_tax))          {$total_service_tax          = number_format(round($total_service_tax,0),2,'.','')  ; }

                $net_bill_amount             = $total_amount              + $total_service_tax ;                                       if(!empty($net_bill_amount))            {$net_bill_amount            = number_format($net_bill_amount,2,'.','')  ; }



                //----

                $matter_qry        = $this->db->query("select * from fileinfo_header where matter_code = '$matter_code' ")->getResultArray()[0];



                $initial_code      = $matter_qry['initial_code'] ;

                $billing_addr_code = $matter_qry['billing_addr_code'] ;

                $billing_attn_code = $matter_qry['billing_attn_code'] ;



                //----

                $qry2 = $this->db->query("SELECT * FROM billinfo_detail WHERE ref_billinfo_serial_no = '$serial_no' ORDER BY row_no")->getResultArray();

                // echo '<pre>';print_r($qry2);die;

                $qry_count = count($qry2);

                

                //----

                $stat_qry    = $this->db->query("select status_desc from status_master where table_name = 'billinfo_header' and status_code = '$status_code' ")->getResultArray()[0] ;

                $status_desc = $stat_qry['status_desc'] ;



                $params = [

                    "matter_desc" => $matter_desc,

                    "client_name" => $client_name,

                    "total_bill_amount_stax" => $total_bill_amount_stax,

                    "total_bill_amount_ntax" => $total_bill_amount_ntax,

                    "total_amount" => $total_amount,

                    "total_service_tax" => $total_service_tax,

                    "net_bill_amount" => $net_bill_amount,

                    "qry_count" => $qry_count,

                    "status_desc" => $status_desc,

                    "bill_amount_inpocket_stax" => $bill_amount_inpocket_stax,

                    "bill_amount_outpocket_stax" => $bill_amount_outpocket_stax,

                    "bill_amount_counsel_stax" => $bill_amount_counsel_stax,

                    "service_tax_inpocket" => $service_tax_inpocket,

                    "service_tax_outpocket" => $service_tax_outpocket,

                    "service_tax_counsel" => $service_tax_counsel,

                    "bill_amount_inpocket_ntax" => $bill_amount_inpocket_ntax,

                    "bill_amount_outpocket_ntax" => $bill_amount_outpocket_ntax,

                    "bill_amount_counsel_ntax" => $bill_amount_counsel_ntax,

                    "bill_amount_inpocket" => $bill_amount_inpocket,

                    "bill_amount_outpocket" => $bill_amount_outpocket,

                    "bill_amount_counsel" => $bill_amount_counsel,

                    "service_tax_amount" => $service_tax_amount,

                    "initial_code" => $initial_code,

                    "billing_addr_code" => $billing_addr_code,

                    "billing_attn_code" => $billing_attn_code,

                ];



                return view("pages/Billing/bill_approval", compact("reports", "params", "qry2", "permission", "data", "option"));

            }

       
       
            }
        else{

            if ($user_option == null) {



                return view("pages/Billing/bill_approval", compact("data", "displayId"));



            }

        }



    }



    public function bill_cancellation_draft($option = null){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches('demo');

        $user_option = $option; 

        $displayId   = ['billsrl_help_id' => '4531'] ; 

        $data['requested_url'] = $this->session->requested_end_menu_url;
        $finsub            = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;


        if($this->request->getMethod() == 'post') {

            $row_no            = isset($_POST['row_no'])?$_POST['row_no']:null; 

            $serial_no         = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

            $option            = isset($_REQUEST['option'])?$_REQUEST['option']:null;



            //--- billinfo_header

            $billinfo_header_table = $this->db->table("billinfo_header");

            

            //--- billinfo_detail

            $billinfo_detail_table = $this->db->table("billinfo_detail");



            //--- billinfo_cases

            $billinfo_cases_table = $this->db->table("billinfo_cases");



            //--- bill_detail

            $bill_detail_table = $this->db->table("bill_detail");


            if($finsub=="fsub")
            {
            //-------------------------------------- UPDATION OF RECORD(s)

            if($option == 1)

            {

                $updt_stmt   = "UPDATE billinfo_header SET status_code = 'X' WHERE serial_no = '$serial_no'";

                $update_bill = $this->db->query($updt_stmt);

                

            }

            

            else if($option == 2)

            {

                $delete_stmt2 = "DELETE FROM billinfo_header WHERE serial_no = '$serial_no'";

                $delete_bill_info = $this->db->query($delete_stmt2);

            

                $delete_stmt3 = "DELETE FROM billinfo_detail WHERE ref_billinfo_serial_no = '$serial_no'";

                $delete_bill_info_dtl = $this->db->query($delete_stmt3);



                $delete_stmt4 = "DELETE FROM billinfo_cases  WHERE ref_billinfo_serial_no = '$serial_no'";

                $delete_bill_info_case = $this->db->query($delete_stmt4);



                $qry_table = $this->db->query("SELECT source_code FROM billinfo_header WHERE serial_no = '$serial_no'")->getResultArray();

                // echo '<pre>';print_r($qry_table);die;

                $totRow = count($qry_table);

                $row = isset($qry_table) ? $qry_table : NULL ;

                $source_code = isset($row['source_code']) ? $row['source_code'] : NULL;



                if($source_code == 'N')

                {

                $update_notice = "UPDATE notice_header SET ref_billinfo_serial_no = NULL WHERE ref_billinfo_serial_no    = '$serial_no'";

                $updt_notice   = $this->db->query($update_notice);

                }

                else

                {

                //-------------------------------------- ALL Expense & Case header Table

                $updt_stmt_case       = "UPDATE case_header             SET ref_billinfo_serial_no = NULL, status_code = 'A' WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_case_other = "UPDATE case_detail_other_case  SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_court      = "UPDATE court_expense           SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_courier    = "UPDATE courier_expense         SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_photocopy  = "UPDATE photocopy_expense       SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_arbitrator = "UPDATE arbitrator_expense      SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_steno      = "UPDATE steno_expense           SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_mexpn      = "UPDATE expense_detail          SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";



                //--

                $updt_case = $this->db->query($updt_stmt_case);



                $updt_case_other = $this->db->query($updt_stmt_case_other);



                $updt_court = $this->db->query($updt_stmt_court);



                $updt_courier = $this->db->query($updt_stmt_courier);



                $updt_photocopy = $this->db->query($updt_stmt_photocopy);



                $updt_arbitrator = $this->db->query($updt_stmt_arbitrator);



                $updt_setno = $this->db->query($updt_stmt_steno);



                $updt_mexpn = $this->db->query($updt_stmt_mexpn);

                }

            }

            return redirect()->to($data['requested_url']);
        }
        if($finsub=="" || $finsub!="fsub")
        {


            $heading           = "Bill Cancellation (Draft)";

            $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

            $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;

            $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;

            //$user_option       = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;

            $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

            $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

            $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

            $index             = isset($_REQUEST['index'])?$_REQUEST['index']:null;

            $ord               = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;

            $pg                = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;

            $search_val        = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;

            $serial_no         = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

            $branch_code       = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null;

            $matter_desc       = isset($_REQUEST['matter_desc'])?$_REQUEST['matter_desc']:null;

            $client_name       = isset($_REQUEST['client_name'])?$_REQUEST['client_name']:null;

            $initial_code      = isset($_REQUEST['initial_code'])?$_REQUEST['initial_code']:null;

            $billing_attn_code = isset($_REQUEST['billing_attn_code'])?$_REQUEST['billing_attn_code']:null;

            $billing_addr_code = isset($_REQUEST['billing_addr_code'])?$_REQUEST['billing_addr_code']:null;



            $qry1 = "SELECT a.*,trim(concat(b.matter_desc1,' ',matter_desc2)) matter_desc,c.client_name, b.initial_code,b.billing_addr_code,b.billing_attn_code

                FROM billinfo_header a, fileinfo_header b, client_master c

                WHERE a.serial_no = '$serial_no' 

                and a.branch_code = '$branch_code' 

                and a.matter_code = b.matter_code 

                and a.client_code = c.client_code";

            $row = $this->db->query($qry1)->getResultArray()[0];   

            // echo '<pre>';print_r($row);die; 



            $serial_no              = $row['serial_no'];

            $branch_code            = $row['branch_code'];

            $bill_date              = $row['bill_date'];

            $ref_bill_serial_no     = $row['ref_bill_serial_no'];

            $start_date             = $row['start_date'];

            $end_date               = $row['end_date'];

            $client_code            = $row['client_code'];

            $matter_code            = $row['matter_code'];

            $subject_desc           = $row['subject_desc'];

            $other_case_desc        = $row['other_case_desc'];

            $reference_desc         = $row['reference_desc'];

            $ref_billinfo_serial_no = $row['ref_billinfo_serial_no'];

            $bill_amount_inpocket   = $row['bill_amount_inpocket'];

            $bill_amount_outpocket  = $row['bill_amount_outpocket'];

            $bill_amount_counsel    = $row['bill_amount_counsel'];

            $source_code            = $row['source_code'];

            $status_code            = $row['status_code'];

            $message = "Sorry !!! ..... Please Check the Status ......";

            if($user_option == 'proceed' && $status_code != 'A') {



                session()->setFlashdata('valid_message', $message);

                return redirect()->to($data['requested_url']);

                // echo '<script type="text/javascript">

                //     alert("' . $message . '");

                //     window.location.href = "'.$data['requested_url'].'"; 

                // </script>'; 

            } 

            $prepared_by            = $row['prepared_by'];

            $prepared_on            = $row['prepared_on'];

            $updated_by             = $row['updated_by'];

            $updated_on             = $row['updated_on'];

            $approved_by            = $row['approved_by'];

            $approved_on            = $row['approved_on'];

            $matter_desc            = $row['matter_desc'];

            $client_name            = $row['client_name'];

            $initial_code           = $row['initial_code'];

            $billing_addr_code      = $row['billing_addr_code'];

            $billing_attn_code      = $row['billing_attn_code'];



            $total_amount = $bill_amount_inpocket + $bill_amount_outpocket + $bill_amount_counsel ; if(!empty($total_amount))  { $total_amount = number_format($total_amount,2,'.','')  ; }

            //-------------------------------------------------------------------

            $qry2 = $this->db->query("SELECT * FROM billinfo_detail WHERE ref_billinfo_serial_no = '$serial_no' ORDER BY row_no")->getResultArray();

            $qry_count = count($qry2);

            // echo '<pre>';print_r($qry2);die; 

            

            

            //------------------------

            $stat_qry = $this->db->query("select status_desc from status_master where table_name='billinfo_header' and status_code = '$status_code' ")->getResultArray()[0] ; 

            $status_desc = $stat_qry['status_desc'] ;



            $params = [

              'status_desc' => $status_desc,

              'total_amount' => $total_amount,

              'qry_count' => $qry_count,

            ];



                return view("pages/Billing/bill_cancellation_draft", compact("row", "params", "qry2", "data"));

        }

        }else{ 

            if ($user_option == null) {

                return view("pages/Billing/bill_cancellation_draft", compact("data", "displayId"));

            } 

        }

    }



    public function bill_cancellation_final($option = null){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches('demo');

        $user_option = $option;

        $displayId   = ['billsrl_help_id' => '4532'] ; 

        $data['requested_url'] = $this->session->requested_end_menu_url;
        $finsub         = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;


        if($this->request->getMethod() == 'post') {

        	

            $serial_no         = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

            $bill_year         = isset($_REQUEST['bill_year'])?$_REQUEST['bill_year']:null;

            $bill_no           = isset($_REQUEST['bill_no'])?$_REQUEST['bill_no']:null;

            $ref_bill_no       = $bill_year.'/'.$bill_no;

            $option            = isset($_REQUEST['option'])?$_REQUEST['option']:null;

            $user_id           = session()->userId;

            $branch_code       = $data['branch_code']['branch_code'];

         

            //------------------------- OBJECT CREATION

          // echo $serial_no;die;

            //--- billinfo_header

            $billinfo_header_table = $this->db->table("billinfo_header");

          

            //--- billinfo_detail

            $billinfo_detail_table = $this->db->table("billinfo_detail");

         

            //--- billinfo_cases

            $billinfo_cases_table = $this->db->table("billinfo_cases");



            //--- bill_detail

            $bill_detail_table = $this->db->table("bill_detail");

          

          

            //------------------------- Updation of Records

            if($finsub=="fsub")
            {

            if($option == 1)

            {

              $inst_stmt = "insert into billinfo_user_activity (branch_code,ref_bill_serial_no,ref_bill_no,user_action,updated_by,updated_on)

                                  values('$branch_code', '$serial_no', '$ref_bill_no', 'Cancel Fully', '$user_id', now())";

              $inst_activity = $this->db->query($inst_stmt);

              

              $updt_stmt = "UPDATE bill_detail SET cancel_ind = 'Y' WHERE serial_no = (select ref_bill_serial_no from billinfo_header where serial_no = '$serial_no')";

              $update_bill = $this->db->query($updt_stmt);

         

              $updt_stmt = "UPDATE billinfo_header SET status_code = 'X' WHERE serial_no = '$serial_no'";

              $update_bill = $this->db->query($updt_stmt);

            }

            else if($option == 2)

            {

              $inst_stmt = "insert into billinfo_user_activity (branch_code,ref_bill_serial_no,ref_bill_no,user_action,updated_by,updated_on)

                                  values('$branch_code', '$serial_no', '$ref_bill_no','Cancel and Back To Draft Status','$user_id',now())";

              $inst_activity = $this->db->query($inst_stmt);

         

              $delete_stmt = "DELETE FROM bill_detail WHERE serial_no = (select ref_bill_serial_no from billinfo_header where serial_no = '$serial_no')";

              $delete_bill = $this->db->query($delete_stmt);

         

              $updt_stmt   = "UPDATE billinfo_header SET status_code = 'A', ref_bill_serial_no = NULL WHERE serial_no = '$serial_no'";

              $update_bill = $this->db->query($updt_stmt);

         

            }

           

            else if($option == 3)

            {

              $inst_stmt = "insert into billinfo_user_activity (branch_code,ref_bill_serial_no,ref_bill_no,user_action,updated_by,updated_on)

                                  values('$branch_code', '$serial_no', '$ref_bill_no','Cancel and Leave for further Billing','$user_id',now())";

              $inst_activity = $this->db->query($inst_stmt);

         

              $qry_table = $this->db->query("SELECT source_code FROM billinfo_header WHERE serial_no = '$serial_no'")->getResultArray();

              $totRow = count($qry_table);

              $row = $qry_table[0];

              $source_code = $row['source_code'];

              //

              $delete_stmt = "DELETE FROM bill_detail WHERE serial_no = (select ref_bill_serial_no from billinfo_header where serial_no = '$serial_no')";

              $delete_bill = $this->db->query($delete_stmt);

         

              $delete_stmt2 = "DELETE FROM billinfo_header WHERE serial_no = '$serial_no'";

              $delete_bill_info = $this->db->query($delete_stmt2);

          

              $delete_stmt3 = "DELETE FROM billinfo_detail WHERE ref_billinfo_serial_no = '$serial_no'";

              $delete_bill_info_dtl = $this->db->query($delete_stmt3);

         

              $delete_stmt4 = "DELETE FROM billinfo_cases WHERE ref_billinfo_serial_no = '$serial_no'";

              $delete_bill_info_case = $this->db->query($delete_stmt4);

         

              if($source_code == 'N')

              {

                $update_notice = "UPDATE notice_header SET ref_billinfo_serial_no = NULL, status_code = 'A' WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_notice   = $this->db->query($update_notice);

              }

              else

              {

                //---------------------------- ALL Expense & Case header Table ----------------------------------------------------//

                $updt_stmt_case       = "UPDATE case_header            SET ref_billinfo_serial_no = NULL, status_code = 'A' WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_case_other = "UPDATE case_detail_other_case SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_court      = "UPDATE court_expense          SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_courier    = "UPDATE courier_expense        SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_photocopy  = "UPDATE photocopy_expense      SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_arbitrator = "UPDATE arbitrator_expense     SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_steno      = "UPDATE steno_expense          SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";

                $updt_stmt_mexpn      = "UPDATE expense_detail         SET ref_billinfo_serial_no = NULL                    WHERE ref_billinfo_serial_no = '$serial_no'";

          

                //

                $updt_case = $this->db->query($updt_stmt_case);

         

                $updt_case_other = $this->db->query($updt_stmt_case_other);

         

                $updt_court = $this->db->query($updt_stmt_court);

         

                $updt_courier = $this->db->query($updt_stmt_courier);

         

                $updt_photocopy = $this->db->query($updt_stmt_photocopy);

         

                $updt_arbitrator = $this->db->query($updt_stmt_arbitrator);

         

                $updt_setno = $this->db->query($updt_stmt_steno);

         

                $updt_mexpn = $this->db->query($updt_stmt_mexpn);

              }

            }

            return redirect()->to($data['requested_url']);
        }

        if($finsub=="" || $finsub!="fsub")
        {

            $heading           = "Bill Cancellation (Final)";



            $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

            $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;

            $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;

            //$user_option       = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;

            

            $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

            $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

            $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

            $index             = isset($_REQUEST['index'])?$_REQUEST['index']:null;

            $ord               = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;

            $pg                = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;

            $search_val        = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;



            $branch_code        = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null;

            $bill_year          = isset($_REQUEST['bill_year'])?$_REQUEST['bill_year']:null;

            $bill_no            = isset($_REQUEST['bill_no'])?$_REQUEST['bill_no']:null;

            $ref_bill_serial_no = isset($_REQUEST['ref_bill_serial_no'])?$_REQUEST['ref_bill_serial_no']:null;

            $serial_no          = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

            $matter_code        = isset($_REQUEST['matter_code'])?$_REQUEST['matter_code']:null;

            $matter_desc        = isset($_REQUEST['matter_desc'])?$_REQUEST['matter_desc']:null;

            $client_code        = isset($_REQUEST['client_code'])?$_REQUEST['client_code']:null;

            $client_name        = isset($_REQUEST['client_name'])?$_REQUEST['client_name']:null;

            $final_bill_no      = $bill_year.'/'.$bill_no ;



            $qry1 = "SELECT a.*,trim(concat(b.matter_desc1,' ',matter_desc2)) matter_desc,c.client_name, b.initial_code,b.billing_addr_code,b.billing_attn_code

                FROM billinfo_header a, fileinfo_header b, client_master c

                WHERE a.serial_no = '$serial_no' 

                and a.branch_code = '$branch_code' 

                and a.matter_code = b.matter_code 

                and a.client_code = c.client_code";

            $record = $this->db->query($qry1)->getResultArray()[0];

            // echo'<pre>';print_r($record);die;



            $serial_no              = $record['serial_no'];

            $bill_amount_inpocket   = $record['bill_amount_inpocket'];

            $bill_amount_outpocket  = $record['bill_amount_outpocket'];

            $bill_amount_counsel    = $record['bill_amount_counsel'];

            $status_code            = $record['status_code'];

            $message = "Sorry !!! ..... Please Check the Status ......";

            if($user_option == 'proceed' && $status_code != 'B' && $status_code != 'C') { 



                session()->setFlashdata('valid_message', $message);

                return redirect()->to($data['requested_url']);

                // echo '<script type="text/javascript">

                //         alert("' . $message . '");

                //         window.location.href = "'.$data['requested_url'].'"; 

                //     </script>'; 

            }



            $total_amount = $bill_amount_inpocket + $bill_amount_outpocket + $bill_amount_counsel ; if(!empty($total_amount))  { $total_amount = number_format($total_amount,2,'.','')  ; }



            //------------------------

            $qry2 = $this->db->query("SELECT * FROM billinfo_detail WHERE ref_billinfo_serial_no = '$serial_no' ORDER BY row_no")->getResultArray();

            $qry_count = count($qry2);



            //------------------------

            $stat_qry = $this->db->query("select status_desc from status_master where table_name='billinfo_header' and status_code = '$status_code' ")->getResultArray()[0] ; 

            $status_desc = $stat_qry['status_desc'] ;



            $params = [

                'qry_count' => $qry_count,

                'status_desc' => $status_desc,

                'bill_year' => $bill_year,

                'bill_no' => $bill_no,

                'total_amount' => $total_amount,



            ];



                return view("pages/Billing/bill_cancellation_final", compact("record", "params", "qry2", "data"));

        }

        }else {

            if($user_option == null){

             return view("pages/Billing/bill_cancellation_final", compact("data", "displayId"));

            }
        }

    }



    public function bill_collection_status($option = null){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches('demo');

        $user_option = $option; $permission = ($option == 'proceed') ? 'readonly disabled' : '';

        $displayId   = ['client_help_id' => '4079', 'matter_help_id' => '4213'] ;

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $status_desc = 'Maintenance';

        $colour_s = "#0000FF";



        if($this->request->getMethod() == 'post'){



        }else {

            if($user_option == null){

                return view("pages/Billing/bill_collection_status", compact("data", "displayId", "option", "permission"));

            } else {

                $date_from     = isset($_REQUEST['date_from'])  ? $_REQUEST['date_from'] : NULL; 

                $date_to       = isset($_REQUEST['date_to'])  ? $_REQUEST['date_to'] : NULL; 

                $client_matter = isset($_REQUEST['client_matter'])  ? $_REQUEST['client_matter'] : NULL; 

                $input_code    = isset($_REQUEST['input_code'])  ? $_REQUEST['input_code'] : NULL; 

                $input_name    = isset($_REQUEST['input_name'])  ? $_REQUEST['input_name'] : NULL; 

                if(empty($date_from))

                {

                  $date_to = substr($date_to,6,4).'-'.substr($date_to,3,2).'-'.substr($date_to,0,2);

                  $bill_date_stmt = " bill_date <= '".$date_to."'" ;

                  //echo "<pre>";print_r($bill_date_stmt);die;

                }

                else

                {

                  $date_from = substr($date_from,6,4).'-'.substr($date_from,3,2).'-'.substr($date_from,0,2);

                  $date_to   = substr($date_to,6,4).'-'.substr($date_to,3,2).'-'.substr($date_to,0,2);

                  $bill_date_stmt = " bill_date >= '".$date_from."' and bill_date <= '".$date_to."'" ;

                }

                if($client_matter == 'Client')

                {              

                  $input_stmt = " and client_code = '".$input_code."' and ". $bill_date_stmt."" ;

                  //echo "<pre>";print_r($input_stmt);die;

                }

                else

                {

                  $input_stmt = " and matter_code = '".$input_code."' and ". $bill_date_stmt."" ;

                }

                $stmt = "select serial_no,fin_year,bill_no,date_format(bill_date,'%d-%m-%Y') bill_date,client_code,matter_code,

                    ifnull(bill_amount_inpocket,0)    + ifnull(bill_amount_outpocket,0)    + ifnull(bill_amount_counsel,0)    -

                    ifnull(advance_amount_inpocket,0) - ifnull(advance_amount_outpocket,0) - ifnull(advance_amount_counsel,0) -

                    ifnull(realise_amount_inpocket,0) - ifnull(realise_amount_outpocket,0) - ifnull(realise_amount_counsel,0) -

                    ifnull(deficit_amount_inpocket,0) - ifnull(deficit_amount_outpocket,0) - ifnull(deficit_amount_counsel,0) -

                    ifnull(booked_amount_inpocket,0)  - ifnull(booked_amount_outpocket,0)  - ifnull(booked_amount_counsel,0) realise_amount,collectable_ind

                from bill_detail

                where ifnull(bill_amount_inpocket,0) + ifnull(bill_amount_outpocket,0) + ifnull(bill_amount_counsel,0) -

                    ifnull(advance_amount_inpocket,0) - ifnull(advance_amount_outpocket,0) - ifnull(advance_amount_counsel,0) -

                    ifnull(realise_amount_inpocket,0) - ifnull(realise_amount_outpocket,0) - ifnull(realise_amount_counsel,0) -

                    ifnull(deficit_amount_inpocket,0) - ifnull(deficit_amount_outpocket,0) - ifnull(deficit_amount_counsel,0) -

                    ifnull(booked_amount_inpocket,0)  - ifnull(booked_amount_outpocket,0)  - ifnull(booked_amount_counsel,0) > 0 "

                    . $input_stmt . " order by fin_year,bill_date,bill_no" ;

                $report = $this->db->query($stmt)->getResultArray();
                $report_count = count($report);

                try {

                $report[0];

                if($report_count == 0)  throw new \Exception('No Records Found !!');

        

                } catch (\Exception $e) {

                    session()->setFlashdata('message', 'No Records Found !!');

                    return redirect()->to($data['requested_url']);

                }

                foreach( $report as $key => $reports){

                    $cl_res = $this->db->query("select client_name from client_master where client_code = '".$reports['client_code']."'")->getResultArray()[0];
                    
                    $params['client_name'][$key] = $cl_res['client_name'];
                    // echo '<pre>';print_r($params['client_name'][$key]);die;

    

                    $mt_res = $this->db->query("select concat(matter_desc1,matter_desc2) matter_name from fileinfo_header where matter_code = '".$reports['matter_code']."'")->getResultArray();

                    try {

                        $mt_res = $mt_res[0];

                        $params['matter_name'][$key] = $mt_res['matter_name'];

                        

                    } catch (\Exception $e) {

                        $params['matter_name'][$key] = '';

                    }

                    }


                $arr_params = [

                    'date_from' => $date_from,

                    'date_to' => $date_to,

                    'client_matter' => $client_matter,

                    'input_code' => $input_code,

                    'input_name' => $input_name,

                ];
                
                $params = array_merge($params, $arr_params);

                return view("pages/Billing/bill_collection_status", compact("report", "params", "displayId", "data", "option", "permission"));

                

            }

        }



    }



    public function bill_settlement($option = null){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches('demo');

        $user_option = $option; $permission = ($option == 'show') ? 'readonly disabled' : '';

        $displayId   = ['billsrl_help_id' => '4530', 'billsrl_am_help_id' => '4524'] ; 

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $finyr_qry   = $this->db->query("select fin_year from params order by fin_year desc ")->getResultArray() ;
        $finsub = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;


        if($this->request->getMethod() == 'post') {

            if($finsub=="fsub")
            {
            $logdt_qry    = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];

            $user_id      = session()->userId ;

            $curr_sysdate = $logdt_qry['current_date'];

            

            $bill_serial_no                           = isset($_REQUEST['bill_serial_no'])?$_REQUEST['bill_serial_no']:null;

            $backlog_ind                              = isset($_REQUEST['backlog_ind'])?$_REQUEST['backlog_ind']:null;

            $backlog_date                             = isset($_REQUEST['backlog_date'])?$_REQUEST['backlog_date']:null;

            $backlog_cheque_no                        = isset($_REQUEST['backlog_cheque_no'])?$_REQUEST['backlog_cheque_no']:null;

            $backlog_cheque_date                      = isset($_REQUEST['backlog_cheque_date'])?$_REQUEST['backlog_cheque_date']:null;

            $backlog_cheque_bank                      = isset($_REQUEST['backlog_cheque_bank'])?$_REQUEST['backlog_cheque_bank']:null;

            $backlog_realise_amount_inpocket          = isset($_REQUEST['backlog_realise_amount_inpocket'])?$_REQUEST['backlog_realise_amount_inpocket']:null;

            $backlog_realise_amount_outpocket         = isset($_REQUEST['backlog_realise_amount_outpocket'])?$_REQUEST['backlog_realise_amount_outpocket']:null;

            $backlog_realise_amount_counsel           = isset($_REQUEST['backlog_realise_amount_counsel'])?$_REQUEST['backlog_realise_amount_counsel']:null;

            $backlog_realise_amount_service_tax       = isset($_REQUEST['backlog_realise_amount_service_tax'])?$_REQUEST['backlog_realise_amount_service_tax']:null;

            $old_backlog_realise_amount_inpocket      = isset($_REQUEST['old_backlog_realise_amount_inpocket'])?$_REQUEST['old_backlog_realise_amount_inpocket']:null;

            $old_backlog_realise_amount_outpocket     = isset($_REQUEST['old_backlog_realise_amount_outpocket'])?$_REQUEST['old_backlog_realise_amount_outpocket']:null;

            $old_backlog_realise_amount_counsel       = isset($_REQUEST['old_backlog_realise_amount_counsel'])?$_REQUEST['old_backlog_realise_amount_counsel']:null;

            $old_backlog_realise_amount_service_tax   = isset($_REQUEST['old_backlog_realise_amount_service_tax'])?$_REQUEST['old_backlog_realise_amount_service_tax']:null;

            $backlog_date_ymd                         = date_conv($backlog_date) ;

            $backlog_cheque_date_ymd                  = date_conv($backlog_cheque_date) ;

            $prepared_by                              = $user_id ; //$global_userid ;

            $prepared_on                              = $curr_sysdate ; //$global_curr_date2 ;

         

         

            //---- Data Updation

            if ($backlog_ind == 'S') 

            {

              $bill_sql = "update bill_detail

                              set deficit_amount_inpocket             = ifnull(deficit_amount_inpocket,0)     + ifnull('$backlog_realise_amount_inpocket',0) ,

                                  deficit_amount_outpocket            = ifnull(deficit_amount_outpocket,0)    + ifnull('$backlog_realise_amount_outpocket',0) ,

                                  deficit_amount_counsel              = ifnull(deficit_amount_counsel,0)      + ifnull('$backlog_realise_amount_counsel',0) ,

                                  deficit_amount_service_tax          = ifnull(deficit_amount_service_tax,0)  + ifnull('$backlog_realise_amount_service_tax',0) ,

                                  backlog_realise_amount_inpocket     = '$backlog_realise_amount_inpocket'  ,

                                  backlog_realise_amount_outpocket    = '$backlog_realise_amount_outpocket' ,

                                  backlog_realise_amount_counsel      = '$backlog_realise_amount_counsel'   ,

                                  backlog_realise_amount_service_tax  = '$backlog_realise_amount_service_tax' ,

                                  backlog_ind                         = '$backlog_ind', 

                                  backlog_date                        = '$backlog_date_ymd', 

                                  backlog_cheque_no                   = '$backlog_cheque_no', 

                                  backlog_cheque_date                 = '$backlog_cheque_date_ymd', 

                                  backlog_cheque_bank                 = '$backlog_cheque_bank', 

                                  part_full_ind                       = 'F',

                                  prepared_by                         = '$prepared_by',

                                  prepared_on                         = '$prepared_on'

                            where serial_no = '$bill_serial_no' " ;

              $this->db->query($bill_sql) ; 

            }

            else

            {

                //echo 'mnc';die;

              $bill_sql = "update bill_detail

                              set deficit_amount_inpocket              = ifnull(deficit_amount_inpocket,0)    - ifnull('$old_backlog_realise_amount_inpocket',0) ,

                                  deficit_amount_outpocket             = ifnull(deficit_amount_outpocket,0)   - ifnull('$old_backlog_realise_amount_outpocket',0) ,

                                  deficit_amount_counsel               = ifnull(deficit_amount_counsel,0)     - ifnull('$old_backlog_realise_amount_counsel',0) ,

                                  deficit_amount_service_tax           = ifnull(deficit_amount_service_tax,0) - ifnull('$old_backlog_realise_amount_service_tax',0) ,

                                  backlog_realise_amount_inpocket      = 0.00  ,

                                  backlog_realise_amount_outpocket     = 0.00 ,

                                  backlog_realise_amount_counsel       = 0.00   ,

                                  backlog_realise_amount_service_tax   = 0.00   ,

                                  backlog_ind                          = '$backlog_ind', 

                                  backlog_date                         = '$backlog_date_ymd', 

                                  backlog_cheque_no                    = '$backlog_cheque_no', 

                                  backlog_cheque_date                  = '$backlog_cheque_date_ymd', 

                                  backlog_cheque_bank                  = '$backlog_cheque_bank', 

                                  part_full_ind                        = 'P',

                                  prepared_by                          = '$prepared_by',

                                  prepared_on                          = '$prepared_on'

                            where serial_no = '$bill_serial_no' " ;

                $this->db->query($bill_sql) ; 

            }

        	session()->setFlashdata('message', 'Record Updated Successfully !!');

            return redirect()->to($data['requested_url']);
        }

        if($finsub=="" || $finsub!="fsub")
        {


            $billyr = isset($_REQUEST['fin_year'])?$_REQUEST['fin_year']:null ;

            $billno = isset($_REQUEST['bill_no'])?$_REQUEST['bill_no']:null ;



            $my_sql1  = $this->db->query("select * from bill_detail where fin_year = '$billyr' and bill_no = '$billno' " )->getResultArray();

            //$my_arr1  = $this->db->query($my_sql1)->getResultArray();

            $my_cnt1  = count($my_sql1) ;

            try {

                $my_arr1 = $my_sql1[0];

                //

                $client_qry                   = $this->db->query("select client_name from client_master where client_code = '$my_arr1[client_code]' ")->getResultArray()[0] ;

                $client_name                  = $client_qry['client_name'] ;

                $matter_qry                   = $this->db->query("select if(matter_desc1 != '', concat(matter_desc1,':',matter_desc2), matter_desc2) matter_desc from fileinfo_header where matter_code = '$my_arr1[matter_code]' ")->getResultArray()[0] ;

                $matter_desc                  = $matter_qry['matter_desc'] ;

                $initial_qry                  = $this->db->query("select initial_name from initial_master where initial_code = '$my_arr1[initial_code]' ")->getResultArray()[0] ;

                $initial_name                 = $initial_qry['initial_name'] ;

                $address_qry                  = $this->db->query("select * from client_address where address_code = '$my_arr1[address_code]' ")->getResultArray()[0] ;

                $address_line_1               = $address_qry['address_line_1'] ;

                $address_line_2               = $address_qry['address_line_2'] ;

                $address_line_3               = $address_qry['address_line_3'] ;

                $address_line_4               = $address_qry['address_line_4'] ;

                $attention_qry                = $this->db->query("select * from client_attention where attention_code = '$my_arr1[attention_code]' ")->getResultArray()[0] ;

                $attention_name               = $attention_qry['attention_name'] ;

                $advance_amount_service_tax   = !empty($my_arr1['advance_amount_service_tax']) ? $my_arr1['advance_amount_service_tax'] : 0;

                $service_tax_amount           = !empty($my_arr1['service_tax_amount']) ? $my_arr1['service_tax_amount'] : 0;

                $realise_amount_service_tax   = !empty($my_arr1['realise_amount_service_tax']) ? $my_arr1['realise_amount_service_tax'] : 0;

                $deficit_amount_service_tax   = !empty($my_arr1['deficit_amount_service_tax']) ? $my_arr1['deficit_amount_service_tax'] : 0;

                $bill_total_amount            = ($my_arr1['bill_amount_inpocket']    + $my_arr1['bill_amount_outpocket']       + $my_arr1['bill_amount_counsel']          + $my_arr1['service_tax_amount']) ;

                $adv_total_amount             = ($my_arr1['advance_amount_inpocket'] + $my_arr1['advance_amount_outpocket']    + $my_arr1['advance_amount_counsel']       + $my_arr1['advance_amount_service_tax']) ;

                $real_total_amount            = ($my_arr1['realise_amount_inpocket'] + $my_arr1['realise_amount_outpocket']    + $my_arr1['realise_amount_counsel']       + $my_arr1['realise_amount_service_tax']) ;

                $defc_total_amount            = ($my_arr1['deficit_amount_inpocket'] + $my_arr1['deficit_amount_outpocket']    + $my_arr1['deficit_amount_counsel']       + $my_arr1['deficit_amount_service_tax']) ;

                $baln_amount_inpocket         = ($my_arr1['bill_amount_inpocket']    - $my_arr1['advance_amount_inpocket']     - $my_arr1['realise_amount_inpocket']      - $my_arr1['deficit_amount_inpocket']) ;

                $baln_amount_outpocket        = ($my_arr1['bill_amount_outpocket']   - $my_arr1['advance_amount_outpocket']    - $my_arr1['realise_amount_outpocket']     - $my_arr1['deficit_amount_outpocket']) ;

                $baln_amount_counsel          = ($my_arr1['bill_amount_counsel']     - $my_arr1['advance_amount_counsel']      - $my_arr1['realise_amount_counsel']       - $my_arr1['deficit_amount_counsel']) ;

                $baln_amount_service_tax      = ($my_arr1['service_tax_amount']      - $my_arr1['advance_amount_service_tax']  - $my_arr1['realise_amount_service_tax']   - $my_arr1['deficit_amount_service_tax']) ;

                $baln_total_amount            = ($baln_amount_inpocket + $baln_amount_outpocket + $baln_amount_counsel + $baln_amount_service_tax) ;

                $backlog_ind                  = $my_arr1['backlog_ind'] ;

                $backlog_date                 = $my_arr1['backlog_date'] ;

                $backlog_cheque_no            = $my_arr1['backlog_cheque_no'] ;

                $backlog_cheque_date          = $my_arr1['backlog_cheque_date'] ;    

                $backlog_cheque_bank          = $my_arr1['backlog_cheque_bank'] ;

                $backlog_real_total_amount    = ($my_arr1['backlog_realise_amount_inpocket'] + $my_arr1['backlog_realise_amount_outpocket'] + $my_arr1['backlog_realise_amount_counsel'] + $my_arr1['backlog_realise_amount_service_tax']) ;

                $bill_serial_no               = $my_arr1['serial_no'] ;



                if($backlog_date        != '' && $backlog_date        != '0000-00-00') { $backlog_date        = date_conv($backlog_date)        ; } else { $backlog_date        = '' ; }

                if($backlog_cheque_date != '' && $backlog_cheque_date != '0000-00-00') { $backlog_cheque_date = date_conv($backlog_cheque_date) ; } else { $backlog_cheque_date = '' ; }

                

                if ($my_arr1['cancel_ind'] == 'Y') 

                    { $stat_desc = 'CANCELLED ..' ; } 

                else if ($baln_total_amount <= 0) 

                    { $stat_desc = 'SETTLED ..' ; } 

                else 

                    { $stat_desc = 'OUTSTANDING ..' ; }



                if($my_cnt1 == 0)  throw new \Exception('No Records Found !!');

    

            } catch (\Exception $e) {

                session()->setFlashdata('message', 'No Records Found !!');

                return redirect()->to($data['requested_url']);

            }

                $params = [

                    'client_name' => $client_name,

                    'matter_desc' => $matter_desc,

                    'initial_name' => $initial_name,

                    'address_line_1' => $address_line_1,

                    'address_line_2' => $address_line_2,

                    'address_line_3' => $address_line_3,

                    'address_line_4' => $address_line_4,

                    'attention_name' => $attention_name,

                    'advance_amount_service_tax' => $advance_amount_service_tax,

                    'service_tax_amount' => $service_tax_amount,

                    'realise_amount_service_tax' => $realise_amount_service_tax,

                    'deficit_amount_service_tax' => $deficit_amount_service_tax,

                    'bill_total_amount' => $bill_total_amount,

                    'adv_total_amount' => $adv_total_amount,

                    'real_total_amount' => $real_total_amount,

                    'defc_total_amount' => $defc_total_amount,

                    'baln_amount_inpocket' => $baln_amount_inpocket,

                    'baln_amount_outpocket' => $baln_amount_outpocket,

                    'baln_amount_counsel' => $baln_amount_counsel,

                    'baln_amount_service_tax' => $baln_amount_service_tax,

                    'baln_total_amount' => $baln_total_amount,

                    'backlog_real_total_amount' => $backlog_real_total_amount,

                    'baln_total_amount' => $baln_total_amount,

                    'backlog_date' => $backlog_date,

                    'backlog_cheque_date' => $backlog_cheque_date,

                    'stat_desc' => $stat_desc,

                    'bill_serial_no' => $bill_serial_no,

                    'requested_url' => $data['requested_url'] 

                ];

                return view("pages/Billing/bill_settlement", compact("my_arr1", "data", "displayId", "finyr_qry", "option", "params", "permission"));
        }

        }else {

            if($user_option == null){

                $param_id           = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;

                $my_menuid          = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;

                $query_id           = isset($_REQUEST['query_id'])?$_REQUEST['query_id']:null;

                $query_module_code  = isset($_REQUEST['query_module_code'])?$_REQUEST['query_module_code']:null; 

                $query_name         = isset($_REQUEST['query_name'])?$_REQUEST['query_name']:null; 

                $query_program_name = isset($_REQUEST['query_program_name'])?$_REQUEST['query_program_name']:null; 



                return view("pages/Billing/bill_settlement", compact("data", "displayId", "finyr_qry", "option", "permission"));

            }
        }

    }



    public function final_bill_open($option = null){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches('demo');

        $user_option = $option;

        $displayId   = ['billsrl_help_id' => '4532'] ; 

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $finsub         = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;

        if($this->request->getMethod() == 'post') { 

            if($finsub=="fsub")
            {
            $row_no            = isset($_POST['row_no'])?$_POST['row_no']:null; 



            $serial_no         = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

            $bill_year         = isset($_REQUEST['bill_year'])?$_REQUEST['bill_year']:null;

            $bill_no           = isset($_REQUEST['bill_no'])?$_REQUEST['bill_no']:null;

            $ref_bill_no       = $bill_year.'/'.$bill_no;

            $option            = isset($_REQUEST['option'])?$_REQUEST['option']:null;

            $user_id           = session()->userId;

            $branch_code       = $data['branch_code']['branch_code'];

          

            //--- billinfo_header

            $billinfo_header_table = $this->db->table("billinfo_header");

          

            //--- billinfo_detail

            $billinfo_detail_table = $this->db->table("billinfo_detail");

         

            //--- billinfo_cases

            $billinfo_cases_table = $this->db->table("billinfo_cases");



            //--- bill_detail

            $bill_detail_table = $this->db->table("bill_detail");

          

            //------------------------- Updation of Records

         

            if($option == 1)

            {

                if ($user_id != 'abhijit') { 

                $inst_stmt = "insert into bill_open_user_activity (branch_code,ref_bill_serial_no,ref_bill_no,user_action,opened_by,opened_on)

                            values('$branch_code',$serial_no,'$ref_bill_no','Open for Bill Alteration','$user_id',now())";

                $inst_activity = $this->db->query($inst_stmt);



                $updt_stmt = "UPDATE billinfo_header SET status_code = 'E' WHERE serial_no = '$serial_no'";

                $update_bill = $this->db->query($updt_stmt);

                }

              

                if ($user_id == 'abhijit') { 

             

                $updt_stmt = "UPDATE billinfo_header SET status_code = 'Z' WHERE serial_no = '$serial_no'";

                $update_bill = $this->db->query($updt_stmt);

                }

            }



            session()->setFlashdata('success_message', 'Bill Open Successfully');

            return redirect()->to($data['requested_url']);
        }
        if($finsub=="" || $finsub!="fsub")
        {

            $heading           = "Final Bill Open";



            $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

            $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;

            $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;

            //$user_option       = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;

             

            $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

            $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

            $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

            $index             = isset($_REQUEST['index'])?$_REQUEST['index']:null;

            $ord               = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;

            $pg                = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;

            $search_val        = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;

          

            $branch_code        = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null;

            $bill_year          = isset($_REQUEST['bill_year'])?$_REQUEST['bill_year']:null;

            $bill_no            = isset($_REQUEST['bill_no'])?$_REQUEST['bill_no']:null;

            $ref_bill_serial_no = isset($_REQUEST['ref_bill_serial_no'])?$_REQUEST['ref_bill_serial_no']:null;

            $prepared_by        = isset($_REQUEST['prepared_by'])?$_REQUEST['prepared_by']:null;

            $serial_no          = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

            $matter_code        = isset($_REQUEST['matter_code'])?$_REQUEST['matter_code']:null;

            $matter_desc        = isset($_REQUEST['matter_desc'])?$_REQUEST['matter_desc']:null;

            $client_code        = isset($_REQUEST['client_code'])?$_REQUEST['client_code']:null;

            $client_name        = isset($_REQUEST['client_name'])?$_REQUEST['client_name']:null;

            $final_bill_no      = $bill_year.'/'.$bill_no ;

            //

          

            $qry1 = "SELECT a.*,trim(concat(b.matter_desc1,' ',matter_desc2)) matter_desc,c.client_name

                               , b.initial_code,b.billing_addr_code,b.billing_attn_code

                       FROM billinfo_header a, fileinfo_header b, client_master c

                      WHERE a.serial_no = '$serial_no' 

                        and a.branch_code = '$branch_code' 

                        and a.matter_code = b.matter_code 

                        and a.client_code = c.client_code";

          

            $record = $this->db->query($qry1)->getRowArray();



           

            $serial_no              = $record['serial_no'];

            $branch_code            = $record['branch_code'];

            $bill_date              = $record['bill_date'];

            $ref_bill_serial_no     = $record['ref_bill_serial_no'];

            $start_date             = $record['start_date'];

            $end_date               = $record['end_date'];

            $client_code            = $record['client_code'];

            $matter_code            = $record['matter_code'];

            $subject_desc           = $record['subject_desc'];

            $other_case_desc        = $record['other_case_desc'];

            $reference_desc         = $record['reference_desc'];

            $ref_billinfo_serial_no = $record['ref_billinfo_serial_no'];

            $bill_amount_inpocket   = $record['bill_amount_inpocket'];

            $bill_amount_outpocket  = $record['bill_amount_outpocket'];

            $bill_amount_counsel    = $record['bill_amount_counsel'];

            $source_code            = $record['source_code'];

            $status_code            = $record['status_code'];

            $message = "Sorry !!! ..... Please Check the Status ......";

            if($user_option == 'proceed' && $status_code != 'B' && $status_code != 'C') { 



                session()->setFlashdata('valid_message', $message);

                return redirect()->to($data['requested_url']);

                // echo '<script type="text/javascript">

                //         alert("' . $message . '");

                //         window.location.href = "'.$data['requested_url'].'"; 

                //     </script>'; 

            }

            $prepared_by            = $record['prepared_by'];

            $prepared_on            = $record['prepared_on'];

            $updated_by             = $record['updated_by'];

            $updated_on             = $record['updated_on'];

            $approved_by            = $record['approved_by'];

            $approved_on            = $record['approved_on'];

            $matter_desc            = $record['matter_desc'];

            $client_name            = $record['client_name'];

            $initial_code           = $record['initial_code'];

            $billing_addr_code      = $record['billing_addr_code'];

            $billing_attn_code      = $record['billing_attn_code'];

            

            $total_amount = $bill_amount_inpocket + $bill_amount_outpocket + $bill_amount_counsel ; if(!empty($total_amount))  { $total_amount = number_format($total_amount,2,'.','')  ; }



            //------------------------

            $qry2 = $this->db->query("SELECT * FROM billinfo_detail WHERE ref_billinfo_serial_no = '$serial_no' ORDER BY row_no")->getResultArray();

            $qry_count = count($qry2);

            //------------------------

            $stat_qry = $this->db->query("select status_desc from status_master where table_name='billinfo_header' and status_code = '$status_code' ")->getResultArray()[0] ; 

            $status_desc = $stat_qry['status_desc'] ;

        



            $params = [

                'qry_count' => $qry_count,

                'status_desc' => $status_desc,

                'bill_year' => $bill_year,

                'bill_no' => $bill_no,

                'total_amount' => $total_amount,



            ];



                return view("pages/Billing/final_bill_open", compact("record", "params", "qry2", "data"));

        }


        }else {

            if($user_option == null){

             return view("pages/Billing/final_bill_open", compact("data", "displayId"));

            }
        }

    }



    public function final_bill_editing($option = null){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches('demo');

        $user_option = $option; $permission = ($option != 'edit') ? 'readonly disabled' : '';

        $displayId   = ['billsrl_help_id' => '4530', 'billsrl_am_help_id' => '4524'] ; 

        $data['requested_url'] = $this->session->requested_end_menu_url;
        $finsub         = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;


        if($this->request->getMethod() == 'post') {

            if($finsub=="fsub") 
            {
            $row_counter1                = isset($_POST['row_no1'])?$_POST['row_no1']:null; 

            $row_counter2                = isset($_POST['row_no2'])?$_POST['row_no2']:null; 

            $serial_no                   = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

            $ref_bill_serial_no          = isset($_REQUEST['ref_bill_serial_no'])?trim($_REQUEST['ref_bill_serial_no']):null;

            //echo '<pre>';print_r($ref_bill_serial_no);die;

            $branch_code                 = isset($_REQUEST['branch_code'])?trim($_REQUEST['branch_code']):null;

            $subject_desc                = isset($_REQUEST['subject_desc'])?trim($_REQUEST['subject_desc']):null;

            $reference_desc              = isset($_REQUEST['reference_desc'])?trim($_REQUEST['reference_desc']):null;

            $court_fee_bill_ind          = isset($_REQUEST['court_fee_bill_ind'])?trim($_REQUEST['court_fee_bill_ind']):null;

            $no_fee_bill_ind             = isset($_REQUEST['no_fee_bill_ind'])?trim($_REQUEST['no_fee_bill_ind']):null;

            $direct_counsel_ind          = isset($_REQUEST['direct_counsel_ind'])?trim($_REQUEST['direct_counsel_ind']):null;

            $bill_amount_inpocket_stax   = isset($_REQUEST['bill_amount_inpocket_stax'])?trim($_REQUEST['bill_amount_inpocket_stax']):null;

            $bill_amount_outpocket_stax  = isset($_REQUEST['bill_amount_outpocket_stax'])?trim($_REQUEST['bill_amount_outpocket_stax']):null;

            $bill_amount_counsel_stax    = isset($_REQUEST['bill_amount_counsel_stax'])?trim($_REQUEST['bill_amount_counsel_stax']):null;

            $bill_amount_inpocket_ntax   = isset($_REQUEST['bill_amount_inpocket_ntax'])?trim($_REQUEST['bill_amount_inpocket_ntax']):null;

            $bill_amount_outpocket_ntax  = isset($_REQUEST['bill_amount_outpocket_ntax'])?trim($_REQUEST['bill_amount_outpocket_ntax']):null;

            $bill_amount_counsel_ntax    = isset($_REQUEST['bill_amount_counsel_ntax'])?trim($_REQUEST['bill_amount_counsel_ntax']):null;	 

            $service_tax_inpocket        = isset($_REQUEST['service_tax_inpocket'])?trim($_REQUEST['service_tax_inpocket']):null;

            $service_tax_outpocket       = isset($_REQUEST['service_tax_outpocket'])?trim($_REQUEST['service_tax_outpocket']):null;

            $service_tax_counsel         = isset($_REQUEST['service_tax_counsel'])?trim($_REQUEST['service_tax_counsel']):null;

            $bill_amount_inpocket        = isset($_REQUEST['bill_amount_inpocket'])?trim($_REQUEST['bill_amount_inpocket']):null;

            $bill_amount_outpocket       = isset($_REQUEST['bill_amount_outpocket'])?trim($_REQUEST['bill_amount_outpocket']):null;

            $bill_amount_counsel         = isset($_REQUEST['bill_amount_counsel'])?trim($_REQUEST['bill_amount_counsel']):null;

            $service_tax_amount          = isset($_REQUEST['service_tax_amount'])?trim($_REQUEST['service_tax_amount']):null;

            $approved_by                 = isset($_REQUEST['approved_by'])?trim($_REQUEST['approved_by']):null;

            $status_code                 = isset($_REQUEST['status_code'])?trim($_REQUEST['status_code']):null;

            $updated_on                  = isset($_REQUEST['updated_on'])?trim($_REQUEST['updated_on']):null;

            $updated_by                  = isset($_REQUEST['updated_by'])?trim($_REQUEST['updated_by']):null;

          

            

            $branch_code                = stripslashes(strtoupper($branch_code));

            $subject_desc               = stripslashes(strtoupper($subject_desc));

            $reference_desc             = stripslashes(strtoupper($reference_desc));



            // object : billinfo_header

            //------------------------------------------------------------------------------

            $billinfo_header_table = $this->db->table("billinfo_header");

            //-------------------------------------------------------------------------------

            // object : billinfo_detail

            //------------------------------------------------------------------------------

            $billinfo_detail_table = $this->db->table("billinfo_detail");

            //------------------------------------------------------------------------------

                // object : bill_detail

            //------------------------------------------------------------------------------

            $bill_detail_table = $this->db->table("bill_detail");



            $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];

            $user_id        = session()->userId ;

            $curr_sysdate   = $logdt_qry['current_date'];



            $tot_inpocket  = 0;

            $tot_outpocket = 0;

            $tot_counsel   = 0;



            for($i =1; $i <=  $row_counter1; $i++)

            {

                if($_POST['inp_ok_ind'.$i] == "Y")

                {

                    $ind = $_POST['io_ind'.$i];   //echo $_POST['prn_seq_no'.$i].' ';//echo $ind.'<br><br>';

                    if($ind == 'I')

                    {

                        if(!empty($_POST['activity_date'.$i]) && !empty($_POST['counsel_code'.$i]) && !empty($_POST['io_ind'.$i]) && !empty($_POST['billed_amount'.$i]))

                        {

                            $tot_inpocket  = $tot_inpocket  + $_POST['billed_amount'.$i];

                        }

                    }

                    else if($ind == 'C')

                    {

                        if(!empty($_POST['activity_date'.$i]) && !empty($_POST['counsel_code'.$i]) && !empty($_POST['io_ind'.$i]) && !empty($_POST['billed_amount'.$i]))

                        {

                            $tot_counsel  = $tot_counsel  + $_POST['billed_amount'.$i];

                        }

                    }

                }

            }



            for($i =1; $i <=  $row_counter2; $i++)

            {

                if($_POST['out_ok_ind'.$i] == "Y")

                {  //echo $_POST['prn_seq'.$i].' ';

                    if(!empty($_POST['date'.$i]) && !empty($_POST['amount'.$i]))

                    {

                        $tot_outpocket  = $tot_outpocket  + $_POST['amount'.$i];

                    }

                }

            }

            if($user_option == 'edit')

            {

                        

                if($user_id  == 'abhijit') {

                    

                    $array =  array( 'branch_code'                => $_POST['branch_code'],

                                    'end_date'                   => date_conv($_POST['end_date']),

                                    'subject_desc'               => strtoupper(stripslashes($_POST['subject_desc'])),

                                    'reference_desc'             => stripslashes($_POST['reference_desc']),

                                    'court_fee_bill_ind'         => $_POST['court_fee_bill_ind'],

                                    'no_fee_bill_ind'            => $_POST['no_fee_bill_ind'],

                                    'direct_counsel_ind'         => $_POST['direct_counsel_ind'],

                                    'bill_amount_inpocket_stax'  => $_POST['bill_amount_inpocket_stax'],

                                    'bill_amount_outpocket_stax' => $_POST['bill_amount_outpocket_stax'],

                                    'bill_amount_counsel_stax'   => $_POST['bill_amount_counsel_stax'],

                                    'bill_amount_inpocket_ntax'  => $_POST['bill_amount_inpocket_ntax'],

                                    'bill_amount_outpocket_ntax' => $_POST['bill_amount_outpocket_ntax'],

                                    'bill_amount_counsel_ntax'   => $_POST['bill_amount_counsel_ntax'],		 

                                    'service_tax_inpocket'       => $_POST['service_tax_inpocket'],

                                    'service_tax_outpocket'      => $_POST['service_tax_outpocket'],

                                    'service_tax_counsel'        => $_POST['service_tax_counsel'],

                                    'bill_amount_inpocket'       => $tot_inpocket,

                                    'bill_amount_outpocket'      => $tot_outpocket,

                                    'bill_amount_counsel'        => $tot_counsel,

                                    'service_tax_amount'         => $_POST['total_service_tax'],

                                    'status_code'                => 'B',

                                ); 



                }

                if($user_id  != 'abhijit') {

                    $array =  array( 'branch_code'                => $_POST['branch_code'],

                                    'subject_desc'               => strtoupper(stripslashes($_POST['subject_desc'])),

                                    'reference_desc'             => stripslashes($_POST['reference_desc']),

                                    'court_fee_bill_ind'         => $_POST['court_fee_bill_ind'],

                                    'no_fee_bill_ind'            => $_POST['no_fee_bill_ind'],

                                    'direct_counsel_ind'         => $_POST['direct_counsel_ind'],

                                    'bill_amount_inpocket_stax'  => $_POST['bill_amount_inpocket_stax'],

                                    'bill_amount_outpocket_stax' => $_POST['bill_amount_outpocket_stax'],

                                    'bill_amount_counsel_stax'   => $_POST['bill_amount_counsel_stax'],

                                    'bill_amount_inpocket_ntax'  => $_POST['bill_amount_inpocket_ntax'],

                                    'bill_amount_outpocket_ntax' => $_POST['bill_amount_outpocket_ntax'],

                                    'bill_amount_counsel_ntax'   => $_POST['bill_amount_counsel_ntax'],		 

                                    'service_tax_inpocket'       => $_POST['service_tax_inpocket'],

                                    'service_tax_outpocket'      => $_POST['service_tax_outpocket'],

                                    'service_tax_counsel'        => $_POST['service_tax_counsel'],

                                    'bill_amount_inpocket'       => $tot_inpocket,

                                    'bill_amount_outpocket'      => $tot_outpocket,

                                    'bill_amount_counsel'        => $tot_counsel,

                                    'service_tax_amount'         => $_POST['total_service_tax'],

                                    'status_code'                => 'B',

                                    'updated_by'                 => $user_id,

                                    'updated_on'                 => $curr_sysdate,

                                );



                }



                            $where = "serial_no = '".$_POST['serial_no']."'";

                            //print_r($array);



                            $billHdr = $billinfo_header_table->update($array,$where);

                            

                    //------------------------------------------- MODIFY RECORD :  BILL DETAIL  ---------------------------------------

                    $where = "billinfo_detail.ref_billinfo_serial_no = '".$_POST['serial_no']."'";

                    $billDtl_del = $billinfo_detail_table->delete($where);

                    

                    $row_count = $k = 1;

                    for($i =1; $row_count <= $row_counter1; $i++)

                    {

                        if(isset($_REQUEST['activity_date'.$i], $_REQUEST['counsel_code'.$i], $_REQUEST['io_ind'.$i])) {

                            if(!empty($_POST['activity_date'.$i]) && !empty($_POST['counsel_code'.$i]) && !empty($_POST['io_ind'.$i]))  //&& !empty($_POST['billed_amount'.$i])

                            {

                                $ind = $_POST['io_ind'.$i];

                                if($ind == 'I') { $activity_type = '1'; } else if($ind == 'C') { $activity_type = '2'; } else {  $activity_type = ''; }

                                if(!empty($_POST['source_code'.$i])) { $source_code = $_POST['source_code'.$i]; } else { $source_code = 'C'; }

                                if($_POST['io_ind'.$i] == 'C')

                                    $io_ind = 'O' ;

                                else

                                    $io_ind = 'I' ;

                                $array = array( 'ref_billinfo_serial_no'   => $_POST['serial_no'],

                                                'row_no'                   => $k,

                                                'branch_code'              => $_POST['branch_code'],

                                                'source_code'              => $source_code,

                                                'activity_date'            => date_conv($_POST['activity_date'.$i]),

                                                'activity_type'            => $activity_type,

                                                'counsel_code'             => isset($_REQUEST['counsel_code'.$i]) ? $_REQUEST['counsel_code'.$i] : '',

                                                'activity_desc'            => stripslashes(strtoupper($_POST['activity_desc'.$i])),

                                                'io_ind'                   => $io_ind,

                                                'amount'                   => $_POST['in_amt'.$i],

                                                'billed_amount'            => $_POST['billed_amount'.$i],

                                                'service_tax_ind'          => isset($_POST['service_tax_ind'.$i]) ? $_POST['service_tax_ind'.$i] : '',

                                                'service_tax_percent'      => $_POST['service_tax_percent'.$i],

                                                'service_tax_amount'       => $_POST['service_tax_amount'.$i],

                                                'printer_ind'              => isset($_POST['printer_ind'.$i]) ? $_POST['printer_ind'.$i] : '',

                                                'prn_seq_no'               => $_POST['prn_seq_no'.$i],

                                                );



                                $where = "billinfo_detail.ref_billinfo_serial_no = '".$_POST['serial_no']."'";

                                $billrDtl = $billinfo_detail_table->insert($array,$where);

                                $k++;

                            }

                            $row_count++;

                        }

                    }



                    

                    for($i =1; $row_count <= $row_counter1 + $row_counter2; $i++)

                    {

                        if(isset($_REQUEST['date'.$i], $_REQUEST['amount'.$i])) {

                            if(!empty($_POST['date'.$i]) && !empty($_POST['amount'.$i]))

                            {

                                if(!empty($_POST['out_source_code'.$i])) { $source_code = $_POST['out_source_code'.$i]; } else { $source_code = 'M'; }

                                $array = array( 'ref_billinfo_serial_no'   => $_POST['serial_no'],

                                                'row_no'                   => $k,

                                                'branch_code'              => $_POST['branch_code'],

                                                'source_code'              => $source_code,

                                                'activity_date'            => date_conv($_POST['date'.$i]),

                                                'activity_type'            => '3',

                                                'counsel_code'             => isset($_REQUEST['counsel_code'.$i]) ? $_REQUEST['counsel_code'.$i] : '',

                                                'activity_desc'            => stripslashes(strtoupper($_POST['details'.$i])),

                                                'io_ind'                   => 'O',

                                                'amount'                   => $_POST['out_amt'.$i],

                                                'billed_amount'            => $_POST['amount'.$i],

                                                'service_tax_ind'          =>  isset($_POST['tax_ind'.$i]) ? $_POST['tax_ind'.$i] : '',

                                                'service_tax_percent'      => $_POST['tax_percent'.$i],

                                                'service_tax_amount'       => $_POST['tax_amount'.$i],

                                                'printer_ind'              => $_POST['printer'.$i],

                                                'prn_seq_no'               => $_POST['prn_seq'.$i],

                                                );



                                $where = "billinfo_detail.ref_billinfo_serial_no = '".$_POST['serial_no']."'";

                                $billrDtl2 = $billinfo_detail_table->insert($array,$where);

                                $k++;

                            }

                            $row_count++;

                        }

                    }

                //--------------------------------------------------------------------------------------------------------------



                if ($ref_bill_serial_no != '')

                {

                

                    $array =  array('advance_amount_inpocket'  => NULL,

                                    'advance_amount_outpocket'   => NULL,

                                    'advance_amount_counsel'     => NULL,

                                    'bill_amount_inpocket_stax'  => $_POST['bill_amount_inpocket_stax'],

                                    'service_tax_inpocket'       => $_POST['service_tax_inpocket'],

                                    'bill_amount_outpocket_stax' => $_POST['bill_amount_outpocket_stax'],

                                    'service_tax_outpocket'      => $_POST['service_tax_outpocket'],

                                    'bill_amount_counsel_stax'   => $_POST['bill_amount_counsel_stax'],

                                    'service_tax_counsel'        => $_POST['service_tax_counsel'],

                                    'bill_amount_inpocket_ntax'  => $_POST['bill_amount_inpocket_ntax'],

                                    'bill_amount_outpocket_ntax' => $_POST['bill_amount_outpocket_ntax'],

                                    'bill_amount_counsel_ntax'   => $_POST['bill_amount_counsel_ntax'],

                                    'bill_amount_inpocket'       => $tot_inpocket,

                                    'bill_amount_outpocket'      => $tot_outpocket,

                                    'bill_amount_counsel'        => $tot_counsel,

                                    'service_tax_amount'         => $_POST['total_service_tax'],



                                );

                            $where = "serial_no = '".$_POST['ref_bill_serial_no']."'";

                            //print_r($bill_array);

                            

                            

                            

                            $bill = $bill_detail_table->update($array,$where);



                }

            }
            session()->setFlashdata('message', 'Record Updated Successfully !!');
            return redirect()->to($data['requested_url']);
        }
        if($finsub=="" || $finsub!="fsub")
        {


            $heading           = "Final Bill Editing";

            $screen_ref         = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

            $param_id           = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;

            $my_menuid          = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;

           // $user_option        = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;

            $display_id         = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

            $menu_id            = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

            $screen_ref         = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

            $index              = isset($_REQUEST['index'])?$_REQUEST['index']:null;

            $ord                = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;

            $pg                 = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;

            $search_val         = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;

            $ref_bill_serial_no = isset($_REQUEST['ref_bill_serial_no'])?$_REQUEST['ref_bill_serial_no']:null;

            $serial_no          = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

            $matter_code        = isset($_REQUEST['matter_code'])?$_REQUEST['matter_code']:null;

            $matter_desc        = isset($_REQUEST['matter_desc'])?$_REQUEST['matter_desc']:null;

            $client_code        = isset($_REQUEST['client_code'])?$_REQUEST['client_code']:null;

            $client_name        = isset($_REQUEST['client_name'])?$_REQUEST['client_name']:null;

            

            if ($user_option == 'Edit')     { $redk = ''         ;  $redv = '';          $disv = ''         ; }

            if ($user_option == 'View')     { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; }

            

            $qry1 = "SELECT * FROM billinfo_header WHERE serial_no = '$serial_no'";

            $row = $this->db->query($qry1)->getResultArray()[0];

            

            $serial_no                   = $row['serial_no'];

            $branch_code                 = $row['branch_code'];

            $bill_date                   = $row['bill_date'];

            $ref_bill_serial_no          = isset($row['ref_bill_serial_no']) ? $row['ref_bill_serial_no'] : '';  

            $start_date                  = $row['start_date'];

            $end_date                    = $row['end_date'];

            $client_code                 = $row['client_code'];

            $matter_code                 = $row['matter_code'];

            $subject_desc                = $row['subject_desc'];

            $other_case_desc             = $row['other_case_desc'];

            $reference_desc              = $row['reference_desc'];

            $ref_billinfo_serial_no      = $row['ref_billinfo_serial_no'];

            $bill_amount_inpocket_stax   = $row['bill_amount_inpocket_stax'];      if(!empty($bill_amount_inpocket_stax))  {$bill_amount_inpocket_stax   = number_format($bill_amount_inpocket_stax,2,'.','')  ; }

            $bill_amount_outpocket_stax  = $row['bill_amount_outpocket_stax'];     if(!empty($bill_amount_outpocket_stax)) {$bill_amount_outpocket_stax  = number_format($bill_amount_outpocket_stax,2,'.','')  ; }

            $bill_amount_counsel_stax    = $row['bill_amount_counsel_stax'];       if(!empty($bill_amount_counsel_stax))   {$bill_amount_counsel_stax    = number_format($bill_amount_counsel_stax,2,'.','')  ; }

            $service_tax_inpocket        = $row['service_tax_inpocket'] ;          if(!empty($service_tax_inpocket))       {$service_tax_inpocket        = number_format($service_tax_inpocket,2,'.','')  ; }

            $service_tax_outpocket       = $row['service_tax_outpocket'] ;         if(!empty($service_tax_outpocket))      {$service_tax_outpocket       = number_format($service_tax_outpocket,2,'.','')  ; }   

            $service_tax_counsel         = $row['service_tax_counsel'] ;           if(!empty($service_tax_counsel))        {$service_tax_counsel         = number_format($service_tax_counsel,2,'.','')  ; }

            $bill_amount_inpocket_ntax   = $row['bill_amount_inpocket_ntax'];      if(!empty($bill_amount_inpocket_ntax))  {$bill_amount_inpocket_ntax   = number_format($bill_amount_inpocket_ntax,2,'.','')  ; }

            $bill_amount_outpocket_ntax  = $row['bill_amount_outpocket_ntax'];     if(!empty($bill_amount_outpocket_ntax)) {$bill_amount_outpocket_ntax  = number_format($bill_amount_outpocket_ntax,2,'.','')  ; }

            $bill_amount_counsel_ntax    = $row['bill_amount_counsel_ntax'];       if(!empty($bill_amount_counsel_ntax))   {$bill_amount_counsel_ntax    = number_format($bill_amount_counsel_ntax,2,'.','')  ; } 

            $bill_amount_inpocket        = $row['bill_amount_inpocket'];           if(!empty($bill_amount_inpocket))       {$bill_amount_inpocket        = number_format($bill_amount_inpocket,2,'.','')  ; }

            $bill_amount_outpocket       = $row['bill_amount_outpocket'];          if(!empty($bill_amount_outpocket))      {$bill_amount_outpocket       = number_format($bill_amount_outpocket,2,'.','')  ; }  

            $bill_amount_counsel         = $row['bill_amount_counsel'];            if(!empty($bill_amount_counsel))        {$bill_amount_counsel         = number_format($bill_amount_counsel,2,'.','')  ; }

            $service_tax_amount          = $row['service_tax_amount'] ;            if(!empty($service_tax_amount))         {$service_tax_amount          = number_format($service_tax_amount,2,'.','')  ; }  

            $source_code                 = $row['source_code'];

            $court_fee_bill_ind          = $row['court_fee_bill_ind'];

            $no_fee_bill_ind             = $row['no_fee_bill_ind'];

            $status_code                 = $row['status_code'];

            $message = 'Sorry !!! ..... This Bill is not EDITABLE in this Module .......';

            if($user_option == 'edit' && $status_code != 'E' ) { 



                session()->setFlashdata('valid_message', $message);

                return redirect()->to($data['requested_url']);

                // echo '<script type="text/javascript">

                // alert("' . $msg . '");

                // window.location.href = "'.$data['requested_url'].'"; ; 

                // </script>';

            }

            $prepared_by                 = $row['prepared_by'];

            $prepared_on                 = $row['prepared_on'];

            $updated_by                  = $row['updated_by'];

            $updated_on                  = $row['updated_on'];

            $approved_by                 = $row['approved_by'];

            $approved_on                 = $row['approved_on'];

            $direct_counsel_ind          = $row['direct_counsel_ind'];



            $bill_amount_inpocket_ntax   = $bill_amount_inpocket      - $bill_amount_inpocket_stax ;                               

            if(!empty($bill_amount_inpocket_ntax))  {$bill_amount_inpocket_ntax  = number_format($bill_amount_inpocket_ntax,2,'.','')  ; }



            $bill_amount_outpocket_ntax  = $bill_amount_outpocket     - $bill_amount_outpocket_stax ;                              

            if(!empty($bill_amount_outpocket_ntax)) {$bill_amount_outpocket_ntax = number_format($bill_amount_outpocket_ntax,2,'.','')  ; }



            $bill_amount_counsel_ntax    = $bill_amount_counsel       - $bill_amount_counsel_stax ;                                

            if(!empty($bill_amount_counsel_ntax))   {$bill_amount_counsel_ntax   = number_format($bill_amount_counsel_ntax,2,'.','')  ; }



            $total_bill_amount_stax      = $bill_amount_inpocket_stax + $bill_amount_outpocket_stax + $bill_amount_counsel_stax ;  

            if(!empty($total_bill_amount_stax))     {$total_bill_amount_stax     = number_format($total_bill_amount_stax,2,'.','')  ; }



            $total_bill_amount_ntax      = $bill_amount_inpocket_ntax + $bill_amount_outpocket_ntax + $bill_amount_counsel_ntax ;  

            if(!empty($total_bill_amount_ntax))     {$total_bill_amount_ntax     = number_format($total_bill_amount_ntax,2,'.','')  ; }



            $total_amount                = $bill_amount_inpocket      + $bill_amount_outpocket      + $bill_amount_counsel ;       

            if(!empty($total_amount))               {$total_amount               = number_format($total_amount,2,'.','')  ; }



            $total_service_tax           = $service_tax_inpocket      + $service_tax_outpocket      + $service_tax_counsel ;       

            if(!empty($total_service_tax))          {$total_service_tax          = round(number_format($total_service_tax,2,'.',''),0)  ; }



            $net_bill_amount             = $total_amount              + number_format(round($total_service_tax,0),2,'.','') ; 

            if(!empty($net_bill_amount))            {$net_bill_amount            = number_format($net_bill_amount,2,'.','')  ; }

            //  $net_bill_amount             = $total_amount              + $service_tax_inpocket       + $service_tax_outpocket      + $service_tax_counsel ; 



            $client_qry  = $this->db->query("select client_name from client_master where client_code = '$client_code' ")->getResultArray()[0]; 

            $client_name = $client_qry['client_name'] ;



            $qry2 = $this->db->query("SELECT * FROM billinfo_detail WHERE ref_billinfo_serial_no = '$serial_no' and activity_type in('1','2') ORDER BY prn_seq_no,row_no")->getResultArray();

            $qry_count2 = count($qry2);

            $qry3 = $this->db->query("SELECT * FROM billinfo_detail WHERE ref_billinfo_serial_no = '$serial_no' and activity_type in('3')     ORDER BY prn_seq_no,row_no")->getResultArray();

            $qry_count3 = count($qry3);



            $stat_qry = $this->db->query("select status_desc from status_master where table_name='billinfo_header' and status_code = '$status_code' ")->getResultArray()[0];  

            $status_desc = $stat_qry['status_desc'] ;



            //  $fin_year   = getFinYear($bill_date) ;  if($fin_year == '') { $fin_year = $global_curr_finyear ; }





            $fin_year   = session()->financialYear ; 

            $taxper_qry = $this->db->query("select service_tax_percent from params where fin_year = '$fin_year' ")->getResultArray()[0]; 

            $tax_per    = $taxper_qry['service_tax_percent'] ;

            



            $bill_no_sql  = $this->db->query("select * from bill_detail where serial_no = $ref_bill_serial_no")->getResultArray();

            //echo '<pre>';print_r($bill_no_sql);die;

            try {

                $bill_no_sql = $bill_no_sql[0];

                $bill_no      = $bill_no_sql['bill_no'];

                $bill_year    = $bill_no_sql['fin_year'];

                

            } catch (\Exception $e) {

                $bill_no      = '';

                $bill_year    = '';

            }

            //-----

            $next_page_text = 'Change Addr & Attn';

            

            $matter_sql   = $this->db->query("select * from fileinfo_header where matter_code = $matter_code")->getResultArray()[0];

            $mat_desc_1   = $matter_sql['matter_desc1'];

            $mat_desc_2   = $matter_sql['matter_desc2'];

            $maatter_description  = trim($mat_desc_1." : ".$mat_desc_2);

            // echo '<pre>'; print_r($maatter_description );die;



            $params = [

                'bill_year' => $bill_year,

                'bill_no' => $bill_no,

                'status_desc' => $status_desc,

                'maatter_description' => $maatter_description,

                'client_name' => $client_name,

                'maatter_description' => $maatter_description,

                'maatter_description' => $maatter_description,

                "bill_amount_inpocket_stax" => $bill_amount_inpocket_stax,

                "bill_amount_inpocket_ntax" => $bill_amount_inpocket_ntax,

                "bill_amount_inpocket" => $bill_amount_inpocket,

                "service_tax_inpocket" => $service_tax_inpocket,

                "bill_amount_outpocket_stax" => $bill_amount_outpocket_stax,

                "bill_amount_outpocket_ntax" => $bill_amount_outpocket_ntax,

                "bill_amount_outpocket" => $bill_amount_outpocket,

                "service_tax_outpocket" => $service_tax_outpocket,

                "bill_amount_counsel_stax" => $bill_amount_counsel_stax,

                "bill_amount_counsel_ntax" => $bill_amount_counsel_ntax,

                "bill_amount_counsel" => $bill_amount_counsel,

                "service_tax_counsel" => $service_tax_counsel,

                "total_bill_amount_stax" => $total_bill_amount_stax,

                "total_bill_amount_ntax" => $total_bill_amount_ntax,

                "total_amount" => $total_amount,

                "total_service_tax" => $total_service_tax,

                "net_bill_amount" => $net_bill_amount,

                "tax_per" => $tax_per,

                "qry_count2" => $qry_count2,

                "qry_count3" => $qry_count3,

                "serial_no" => $serial_no,

                "ref_bill_serial_no" => $ref_bill_serial_no,

                "requested_url" => $data['requested_url'],

            ];



                return view("pages/Billing/final_bill_editing", compact("row", "params", "qry2", "qry3", "permission", "data", "option"));

        }

        }else {

            if($user_option == null){

                return view("pages/Billing/final_bill_editing", compact("data", "displayId"));

            }
        }

    }



    public function bill_send_entry($option = null){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches(session()->userId);

        $user_option = $option; $permission = ($option == 'search') ? 'readonly disabled' : '';

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $finsub      = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:'';

        $daybook_qry   = $this->db->query("select * from branch_master where branch_code = 'B001'")->getResultArray() ; 

        $month_qry     = $this->db->query("select month_descl, month_no from months order by month_serial ")->getResultArray() ;

        $finyr_qry     = $this->db->query("select fin_year from params order by fin_year desc ")->getResultArray() ;

        $current_date = date('d-m-Y');

        // echo '<pre>';print_r($current_date);die;

        $fin_year    = session()->financialYear; 

        $month_no    = substr($current_date,03,02) ;

        $rec_type    = 'A' ;

        $trans_type  = 'A' ;

        if($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            $selemode      = isset($_REQUEST['selemode'])?$_REQUEST['selemode']:'Y';

            $recon_cnt     = isset($_REQUEST['recon_cnt'])?$_REQUEST['recon_cnt']:null;

            $userId = session()->userId;

            //----

            $db_code         = $_POST['db_code'];

          

            //----

            for ($i = 1; $i <= $recon_cnt; $i++)

            {

               $serial_no  = $_POST['serial_no'.$i] ;

               //echo '<pre>';print_r($serial_no);die();

               $billsend_ind  = isset($_POST['billsend_ind'.$i]) ? $_POST['billsend_ind'.$i] : '' ;

               $billsend_on = $_POST['billsend_on'.$i] ; 

               if ($billsend_ind == 'Y') { $billsend_ind = 'Y' ; $billsend_on = date_conv($billsend_on,'-') ; } else { $billsend_ind = 'N' ; $billsend_on = null ; }

               if ($billsend_ind == 'Y') { $billsend_by = 'Y' ; $userId ; } else { $billsend_by = '' ; $billsend_by = null ; }

               //

               $update_sql = $this->db->query("update bill_detail set billsend_on ='$billsend_on', billsend_by ='$userId', billsend_ind = '$billsend_ind'  where serial_no = '$serial_no' ");

               

            }  

        	session()->setFlashdata('success_message', 'Records Updated Successfully');

            return redirect()->to($data['requested_url']);
        }
        if($finsub=="" || $finsub!="fsub")
        {
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

            $selemode      = isset($_REQUEST['selemode'])?$_REQUEST['selemode']:'Y'; //null;

            $current_date   = date('d-m-Y');



            //------  

            

            //echo '<pre>'; print_r($selemode);die;



            //------

            if ($selemode != 'Y')

            {

                $fin_year       = session()->financialYear ;

                $month_no       = substr($date,03,02) ;

                $rec_type       = 'A' ;

                $trans_type     = 'A' ;

                

            }

            else 

            {

                //-----

                $redv = 'readonly' ;  $disv = 'disabled' ; $disb = 'disabled' ; 



                //-----

                $db_code         = isset($_REQUEST['db_code'])?$_REQUEST['db_code']:null; 

                $branch_code    = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null; 

                $branch_code     = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null;

                $rec_type        = isset($_REQUEST['rec_type'])?$_REQUEST['rec_type']:null; 

                $trans_type      = isset($_REQUEST['trans_type'])?$_REQUEST['trans_type']:null;

                //echo '<pre>'; print_r($trans_type);die;

                $fin_year      = isset($_REQUEST['fin_year'])?$_REQUEST['fin_year']:null; 

                $month_no      = isset($_REQUEST['month_no'])?$_REQUEST['month_no']:null; 

                $last_recon_date = isset($_REQUEST['last_recon_date'])?$_REQUEST['last_recon_date']:null; 

                $reco_yymm       = isset($_REQUEST['reco_yymm'])?$_REQUEST['reco_yymm']:null; 

                $reco_sdt        = substr($reco_yymm,0,4).'-'.substr($reco_yymm,4,2).'-'.'01' ;

                $branch_code     = 'B001' ;

                //

                $xqry            = $this->db->query("select last_day('$reco_sdt') ldate ")->getResultArray()[0] ;

                $reco_edt        = $xqry['ldate'] ;

                $bill_date       = date_conv($current_date) ;

                

                //----- Last Reconcile Date 

                $last_recon_qry  = $this->db->query("select max(billsend_on) last_billsend_on from bill_detail where branch_code = '$branch_code' ")->getResultArray()[0] ;

                $last_date       = $last_recon_qry['last_billsend_on'] ;  

                $last_date       = $current_date ;

                if ($last_date != '') { $last_recon_date = date_conv($last_date,'/') ; } else { $last_recon_date = '' ; }

                $recon_sql = '';

                //-----

                switch($trans_type) {

                    case 'A' :

                    case 'R' :

                    case 'U' :

                        $recon_sql = "select a.serial_no, concat(a.fin_year,'/',a.bill_no) bill_no, a.bill_date, a.matter_code,a.client_code, b.client_name, concat(c.matter_desc1,' ',c.matter_desc2) matter_desc, sum(ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt, a.billsend_on

                                    from bill_detail a, client_master b, fileinfo_header c 

                                where a.branch_code like 'B001' 

                                    and a.bill_date between '$reco_sdt' and '$reco_edt'

                                    and a.matter_code = c.matter_code

                                    and c.initial_code != ('AR')

                                    and c.initial_code != ('BKG')

                                    and a.client_code = b.client_code

                                    group by a.serial_no order by a.bill_date, a.client_code " ; 

                    break;

                    // case 'R' :

                    //     $recon_sql = "select a.serial_no,a.bill_no,a.bill_date,a.matter_code,a.client_code, (ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt, a.billsend_on

                    //                 from bill_detail a 

                    //             where a.branch_code like '$branch_code' 

                    //                 and a.doc_date <= '$reco_edt'

                    //                 and a.billsend_on between '$reco_sdt' and '$reco_edt'

                    //             order by a.instrument_no " ;

                    // break;

                    // case 'U' :

                    //     $recon_sql = "select a.serial_no,a.bill_no,a.bill_date,a.matter_code,a.client_code, (ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt, a.billsend_on

                    //                 from bill_detail a 

                    //             where a.branch_code like '$branch_code' 

                    //                 and a.doc_date <= '$reco_edt'

                    //                 and (a.billsend_on is null or a.billsend_on = '0000-00-00')

                    //             order by a.instrument_no " ;

                    // break;

                }

                

                

                $recon_qry = $this->db->query($recon_sql)->getResultArray() ;

                // echo '<pre>'; print_r($recon_qry[0]);die;

                $recon_cnt = count($recon_qry);



                try {

                $recon_qry[0];

                if($recon_cnt == 0)  throw new \Exception('No Records Found !!');

        

                } catch (\Exception $e) {

                    session()->setFlashdata('message', 'No Records Found !!');

                    return redirect()->to($data['requested_url']);

                }

            }

            $params = [

                'db_code' => $db_code,

                'branch_code' => $branch_code,

                'trans_type' => $trans_type,

                'last_recon_date' => $last_recon_date,

                'fin_year' => $fin_year,

                'month_no' => $month_no,

                'recon_cnt' => $recon_cnt,

                "requested_url" => $data['requested_url'],

            ];



            return view("pages/Billing/bill_send_entry", compact("option", "params", "recon_qry","fin_year", "month_no", "rec_type", "trans_type", "daybook_qry", "month_qry", "finyr_qry", "permission"));

        }

        } else {

            if($user_option == null){



                return view("pages/Billing/bill_send_entry", compact("option", "fin_year", "month_no", "rec_type", "trans_type", "daybook_qry", "month_qry", "finyr_qry", "permission"));



            }

        }

    }



    public function final_bill_updation($option = null) {

        $data = branches(session()->userId);

        $displayId   = ['client_help_id' => '5001'] ; 

        $user_option = $option; $permission = ($option == 'show') ? 'readonly disabled' : '';

        $branch_code = $data['branch_code']['branch_code'] ; //$global_branch_code

        $logdt_qry    = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getRowArray();

        $user_id      = session()->userId ;

        $curr_sysdate = $logdt_qry['current_date'];
        $finsub        = isset($_REQUEST['finsub'])        ?$_REQUEST['finsub']        :null; 


        $billyr_qry  = $this->db->query("select distinct fin_year from bill_detail order by fin_year desc")->getResultArray();

        // echo "<pre>";print_r($billyr_qry);die;

        $data['requested_url'] = $this->session->requested_end_menu_url;



        if($this->request->getMethod() == 'post') {

            if($finsub=="fsub")
            {
            $branch_code        = isset($_REQUEST['branch_code'])        ?$_REQUEST['branch_code']        :null;     

            $bill_serial_no     = isset($_REQUEST['bill_serial_no'])     ?$_REQUEST['bill_serial_no']     :null;     

            $bill_year          = isset($_REQUEST['bill_year'])          ?$_REQUEST['bill_year']          :null;     

            $bill_no            = isset($_REQUEST['bill_no'])            ?$_REQUEST['bill_no']            :null;     

            $status_code        = isset($_REQUEST['status_code'])        ?$_REQUEST['status_code']        :null;     

            $client_code        = isset($_REQUEST['client_code'])        ?$_REQUEST['client_code']        :null;     

            $client_name        = isset($_REQUEST['client_name'])        ?$_REQUEST['client_name']        :null;     

            $matter_code        = isset($_REQUEST['matter_code'])        ?$_REQUEST['matter_code']        :null;    

            $matter_desc        = isset($_REQUEST['matter_desc'])        ?$_REQUEST['matter_desc']        :null;     

            $address_code       = isset($_REQUEST['address_code'])       ?$_REQUEST['address_code']       :null;     

            $address_desc       = isset($_REQUEST['address_desc'])       ?$_REQUEST['address_desc']       :null;     

            $attention_code     = isset($_REQUEST['attention_code'])     ?$_REQUEST['attention_code']     :null;     

            $attention_name     = isset($_REQUEST['attention_name'])     ?$_REQUEST['attention_name']     :null;     

            $change_ind         = isset($_REQUEST['change_ind'])         ?$_REQUEST['change_ind']         :null;

            $new_client_code    = isset($_REQUEST['new_client_code'])    ?$_REQUEST['new_client_code']    :null;     

            $new_client_name    = isset($_REQUEST['new_client_name'])    ?$_REQUEST['new_client_name']    :null;     

            $new_matter_code    = isset($_REQUEST['new_matter_code'])    ?$_REQUEST['new_matter_code']    :null;    

            $new_matter_desc    = isset($_REQUEST['new_matter_desc'])    ?$_REQUEST['new_matter_desc']    :null;     

            $new_address_code   = isset($_REQUEST['new_address_code'])   ?$_REQUEST['new_address_code']   :null;     

            $new_address_desc   = isset($_REQUEST['new_address_desc'])   ?$_REQUEST['new_address_desc']   :null;     

            $new_attention_code = isset($_REQUEST['new_attention_code']) ?$_REQUEST['new_attention_code'] :null;     

            $new_attention_name = isset($_REQUEST['new_attention_name']) ?$_REQUEST['new_attention_name'] :null;     

          //echo'<pre>';print_r($_REQUEST);die;

            //---- Updation of Record

            $xSql  = "update bill_detail

                         set old_client_code    = client_code

                            ,old_address_code   = address_code

                            ,old_attention_code = attention_code

                            ,client_code        = '$new_client_code'

                            ,address_code       = '$new_address_code'

                            ,attention_code     = '$new_attention_code'

                            ,updated_by         = '$user_id'

                            ,updated_on         = '$curr_sysdate'

                       where serial_no = '$bill_serial_no' ";

            $this->db->query($xSql) ;

            

            $ySql  = "update fileinfo_header

                         set billing_addr_code  = '$new_address_code'

                            ,billing_attn_code  = '$new_attention_code'

                            ,last_update_id     = '$user_id'

                            ,last_update_dt     = '$curr_sysdate'

                       where matter_code = '$matter_code' ";

            $this->db->query($ySql) ;



            return redirect()->to($data['requested_url']);
            }
            if($finsub=="" || $finsub!="fsub")
            {


                $display_id    = isset($_REQUEST['display_id'])  ?$_REQUEST['display_id']  :null;

                $param_id      = isset($_REQUEST['param_id'])    ?$_REQUEST['param_id']    :null;

                $my_menuid     = isset($_REQUEST['my_menuid'])   ?$_REQUEST['my_menuid']   :null;

                $menu_id       = isset($_REQUEST['menu_id'])     ?$_REQUEST['menu_id']     :null;	

                $user_option   = isset($_REQUEST['user_option']) ?$_REQUEST['user_option'] :null;

                $screen_ref    = isset($_REQUEST['screen_ref'])  ?$_REQUEST['screen_ref']  :null;

                $index         = isset($_REQUEST['index'])       ?$_REQUEST['index']       :null;

                $ord           = isset($_REQUEST['ord'])         ?$_REQUEST['ord']         :null;

                $pg            = isset($_REQUEST['pg'])          ?$_REQUEST['pg']          :null;

                $search_val    = isset($_REQUEST['search_val'])  ?$_REQUEST['search_val']  :null;

            

                $helpid = '5001';



                $brchcd = isset($_REQUEST['branch_code']) ?$_REQUEST['branch_code'] :null ;

                $billyr = isset($_REQUEST['bill_year'])   ?$_REQUEST['bill_year']   :null ;

                $billno = isset($_REQUEST['bill_no'])     ?$_REQUEST['bill_no']     :null ;



                $my_sql1  = "select * from bill_detail where branch_code = '$brchcd' and fin_year = '$billyr' and bill_no = '$billno' " ;

                $my_arr1  = $this->db->query($my_sql1)->getResultArray();

                $my_cnt1  = count($my_arr1) ; 

                //$my_arr1  = mysql_fetch_array($my_qry1);

                //

                try {

                $my_arr1 = $my_arr1[0];

                $xSerialNo      = $my_arr1['serial_no'] ;       

                $xClientCode    = $my_arr1['client_code'];

                $xMatterCode    = $my_arr1['matter_code'];

                $xAddrCode      = $my_arr1['address_code'];

                $xAttnCode      = $my_arr1['attention_code'];

                //

                $client_qry     = $this->db->query("select client_name from client_master where client_code = '$xClientCode' ")->getResultArray()[0] ;

                $xClientName    = $client_qry['client_name'] ;

                $matter_qry     = $this->db->query("select if(matter_desc1 != '', concat(matter_desc1,':',matter_desc2), matter_desc2) matter_desc from fileinfo_header where matter_code = '$xMatterCode' ")->getResultArray()[0] ;

                $xMatterDesc    = $matter_qry['matter_desc'] ;

                $address_qry    = $this->db->query("select * from client_address where address_code = '$xAddrCode' ")->getResultArray()[0] ;

                $xAddrDesc      = $address_qry['address_line_1'].' '.$address_qry['address_line_2'].' '.$address_qry['address_line_3'].' '.$address_qry['address_line_4'] ;

                $attention_qry  = $this->db->query("select * from client_attention where attention_code = '$xAttnCode' ")->getResultArray()[0] ;

                $xAttnName      = $attention_qry['attention_name'] ;

                

                if($my_cnt1 == 0)  throw new \Exception('No Records Found !!');

        

                } catch (\Exception $e) {

                    session()->setFlashdata('message', 'No Records Found !!');

                    return redirect()->to($data['requested_url']);

                }



                $params = [

                    "xMatterCode" => $xMatterCode,

                    "xMatterDesc" => $xMatterDesc,

                    "xClientCode" => $xClientCode,

                    "xClientName" => $xClientName,

                    "xAddrCode"   => $xAddrCode,

                    "xAddrDesc"   => $xAddrDesc,

                    "xAttnCode"   => $xAttnCode,

                    "xAttnName"   => $xAttnName,

                    "xSerialNo"   => $xSerialNo,

                    "billno"      => $billno,



                ];

                return view("pages/Billing/final_bill_updation", compact("branch_code", "params", "option", "data", "billyr_qry", "displayId"));
            }

        } else {

            if($user_option == null){

                return view("pages/Billing/final_bill_updation", compact("data", "option", "branch_code","billyr_qry"));

            } 
        }



    }



    public function bill_summary_correction($option = null){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches('demo');

        $user_option = $option; $permission = ($option == 'show') ? 'readonly disabled' : '';

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $finsub= isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;

        $branch_qry  = $this->db->query("select * from branch_master order by branch_name")->getResultArray();

        $finyr_qry   = $this->db->query("select distinct fin_year bill_year from bill_detail order by bill_year desc ")->getResultArray();



        if($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            $branch_code                   = isset($_REQUEST['branch_code'])                   ?$_REQUEST['branch_code']                   :null;

            $bill_serial_no                = isset($_REQUEST['bill_serial_no'])                ?$_REQUEST['bill_serial_no']                :null;

            $bill_year                     = isset($_REQUEST['bill_year'])                     ?$_REQUEST['bill_year']                     :null;

            $bill_no                       = isset($_REQUEST['bill_no'])                       ?$_REQUEST['bill_no']                       :null;

            $bill_date                     = isset($_REQUEST['bill_date'])                     ?$_REQUEST['bill_date']                     :null;    

            $matter_code                   = isset($_REQUEST['matter_code'])                   ?$_REQUEST['matter_code']                   :null;

            $client_code                   = isset($_REQUEST['client_code'])                   ?$_REQUEST['client_code']                   :null;

            $initial_code                  = isset($_REQUEST['initial_code'])                  ?$_REQUEST['initial_code']                  :null;

            $matter_desc                   = isset($_REQUEST['matter_desc'])                   ?$_REQUEST['matter_desc']                   :null;

            $client_name                   = isset($_REQUEST['client_name'])                   ?$_REQUEST['client_name']                   :null;

            //

            $bill_amount_inpocket          = isset($_REQUEST['bill_amount_inpocket'])          ?$_REQUEST['bill_amount_inpocket']          :null;

            $bill_amount_outpocket         = isset($_REQUEST['bill_amount_outpocket'])         ?$_REQUEST['bill_amount_outpocket']         :null;

            $bill_amount_counsel           = isset($_REQUEST['bill_amount_counsel'])           ?$_REQUEST['bill_amount_counsel']           :null;

            $bill_amount_service_tax       = isset($_REQUEST['bill_amount_service_tax'])       ?$_REQUEST['bill_amount_service_tax']       :null;

            $bill_amount_total             = isset($_REQUEST['bill_amount_total'])             ?$_REQUEST['bill_amount_total']             :null;

            $realise_amount_inpocket       = isset($_REQUEST['realise_amount_inpocket'])       ?$_REQUEST['realise_amount_inpocket']       :null;

            $realise_amount_outpocket      = isset($_REQUEST['realise_amount_outpocket'])      ?$_REQUEST['realise_amount_outpocket']      :null;

            $realise_amount_counsel        = isset($_REQUEST['realise_amount_counsel'])        ?$_REQUEST['realise_amount_counsel']        :null;

            $realise_amount_service_tax    = isset($_REQUEST['realise_amount_service_tax'])    ?$_REQUEST['realise_amount_service_tax']    :null;

            $realise_amount_total          = isset($_REQUEST['realise_amount_total'])          ?$_REQUEST['realise_amount_total']          :null;

            $adjusted_amount_inpocket      = isset($_REQUEST['adjusted_amount_inpocket'])      ?$_REQUEST['adjusted_amount_inpocket']      :null;

            $adjusted_amount_outpocket     = isset($_REQUEST['adjusted_amount_outpocket'])     ?$_REQUEST['adjusted_amount_outpocket']     :null;

            $adjusted_amount_counsel       = isset($_REQUEST['adjusted_amount_counsel'])       ?$_REQUEST['adjusted_amount_counsel']       :null;

            $adjusted_amount_service_tax   = isset($_REQUEST['adjusted_amount_service_tax'])   ?$_REQUEST['adjusted_amount_service_tax']   :null;

            $adjusted_amount_total         = isset($_REQUEST['adjusted_amount_total'])         ?$_REQUEST['adjusted_amount_total']         :null;

            $deficit_amount_inpocket       = isset($_REQUEST['deficit_amount_inpocket'])       ?$_REQUEST['deficit_amount_inpocket']       :null;

            $deficit_amount_outpocket      = isset($_REQUEST['deficit_amount_outpocket'])      ?$_REQUEST['deficit_amount_outpocket']      :null;

            $deficit_amount_counsel        = isset($_REQUEST['deficit_amount_counsel'])        ?$_REQUEST['deficit_amount_counsel']        :null;

            $deficit_amount_service_tax    = isset($_REQUEST['deficit_amount_service_tax'])    ?$_REQUEST['deficit_amount_service_tax']    :null;

            $deficit_amount_total          = isset($_REQUEST['deficit_amount_total'])          ?$_REQUEST['deficit_amount_total']          :null;

            $balance_amount_inpocket       = isset($_REQUEST['balance_amount_inpocket'])       ?$_REQUEST['balance_amount_inpocket']       :null;

            $balance_amount_outpocket      = isset($_REQUEST['balance_amount_outpocket'])      ?$_REQUEST['balance_amount_outpocket']      :null;

            $balance_amount_counsel        = isset($_REQUEST['balance_amount_counsel'])        ?$_REQUEST['balance_amount_counsel']        :null;

            $balance_amount_service_tax    = isset($_REQUEST['balance_amount_service_tax'])    ?$_REQUEST['balance_amount_service_tax']    :null;

            $balance_amount_total          = isset($_REQUEST['balance_amount_total'])          ?$_REQUEST['balance_amount_total']          :null;

            $booked_amount_inpocket        = isset($_REQUEST['booked_amount_inpocket'])        ?$_REQUEST['booked_amount_inpocket']        :null;

            $booked_amount_outpocket       = isset($_REQUEST['booked_amount_outpocket'])       ?$_REQUEST['booked_amount_outpocket']       :null;

            $booked_amount_counsel         = isset($_REQUEST['booked_amount_counsel'])         ?$_REQUEST['booked_amount_counsel']         :null;

            $booked_amount_service_tax     = isset($_REQUEST['booked_amount_service_tax'])     ?$_REQUEST['booked_amount_service_tax']     :null;

            $booked_amount_total           = isset($_REQUEST['booked_amount_total'])           ?$_REQUEST['booked_amount_total']           :null;

            $receivable_amount_inpocket    = isset($_REQUEST['receivable_amount_inpocket'])    ?$_REQUEST['receivable_amount_inpocket']    :null;

            $receivable_amount_outpocket   = isset($_REQUEST['receivable_amount_outpocket'])   ?$_REQUEST['receivable_amount_outpocket']   :null;

            $receivable_amount_counsel     = isset($_REQUEST['receivable_amount_counsel'])     ?$_REQUEST['receivable_amount_counsel']     :null;

            $receivable_amount_service_tax = isset($_REQUEST['receivable_amount_service_tax']) ?$_REQUEST['receivable_amount_service_tax'] :null;

            $receivable_amount_total       = isset($_REQUEST['receivable_amount_total'])       ?$_REQUEST['receivable_amount_total']       :null;

            $status_code                   = isset($_REQUEST['status_code'])                   ?$_REQUEST['status_code']                   :null;

            //

            $part_full_ind                 = ($balance_amount_total > 0) ? 'P' : 'F' ;

            $bill_date_ymd                 = $bill_date ;

            //echo '<pre>';print_r($bill_date_ymd);die;

            

            //---- Data Updation

            $bill_sql = $this->db->query("update bill_detail set bill_date      = '$bill_date_ymd'

                                ,bill_amount_inpocket          = '$bill_amount_inpocket'

                                ,bill_amount_outpocket         = '$bill_amount_outpocket'

                                ,bill_amount_counsel           = '$bill_amount_counsel'

                                ,service_tax_amount            = '$bill_amount_service_tax'

                                ,realise_amount_inpocket       = '$realise_amount_inpocket'

                                ,realise_amount_outpocket      = '$realise_amount_outpocket'

                                ,realise_amount_counsel        = '$realise_amount_counsel'

                                ,realise_amount_service_tax    = '$realise_amount_service_tax'

                                ,advance_amount_inpocket       = '$adjusted_amount_inpocket'

                                ,advance_amount_outpocket      = '$adjusted_amount_outpocket'

                                ,advance_amount_counsel        = '$adjusted_amount_counsel'

                                ,advance_amount_service_tax    = '$adjusted_amount_service_tax'

                                ,deficit_amount_inpocket       = '$deficit_amount_inpocket'

                                ,deficit_amount_outpocket      = '$deficit_amount_outpocket'

                                ,deficit_amount_counsel        = '$deficit_amount_counsel'

                                ,deficit_amount_service_tax    = '$deficit_amount_service_tax'

                                ,part_full_ind                 = '$part_full_ind'

                                where serial_no = '$bill_serial_no' " );



            $xSql = $this->db->query("update billinfo_header set bill_date = '$bill_date_ymd' where ref_bill_serial_no = '$bill_serial_no' ");



            return redirect()->to($data['requested_url']);
            }
            if($finsub=="" || $finsub!="fsub")
            {


                $billyr = isset($_REQUEST['bill_year']) ?$_REQUEST['bill_year'] :null ;

                $billno = isset($_REQUEST['bill_no'])   ?$_REQUEST['bill_no']   :null ;

           

                $my_sql1  = "select * from bill_detail where fin_year = '$billyr' and bill_no = '$billno' " ;

                $my_qry1  = $this->db->query($my_sql1)->getResultArray();

                $my_cnt1  = count($my_qry1) ;

                // echo '<pre>';print_r($bill_date);die;

                try{

                $my_arr1  = $my_qry1[0];

                //

                $bill_no       = $my_arr1['bill_no']    ;

                $bill_date       = $my_arr1['bill_date']    ;

                $matter_code     = $my_arr1['matter_code']  ;

                $client_code     = $my_arr1['client_code']  ;

                $initial_code    = $my_arr1['initial_code'] ;

                $part_full_ind   = $my_arr1['part_full_ind']  ;

                //

                $matter_qry      = $this->db->query("select if(matter_desc1 != '', concat(matter_desc1,':',matter_desc2), matter_desc2) matter_desc from fileinfo_header where matter_code = '$matter_code' ")->getResultArray()[0] ;

                $matter_desc     = $matter_qry['matter_desc']  ;

                $client_qry      = $this->db->query("select client_name from client_master where client_code = '$client_code' ")->getResultArray()[0] ;

                $client_name     = $client_qry['client_name'];

                //	 

                if($my_cnt1 == 0)  throw new \Exception('No Records Found !!');

        

                } catch (\Exception $e) {

                    session()->setFlashdata('message', 'No Records Found !!');

                    return redirect()->to($data['requested_url']);

                }

                

                $params = [

                    "bill_date"     => $bill_date,

                    "matter_code"   => $matter_code,

                    "matter_desc"   => $matter_desc,

                    "client_code"   => $client_code,

                    "client_name"   => $client_name,

                    "initial_code"  => $initial_code,

                    "part_full_ind" => $part_full_ind,

                    "bill_no"       => $bill_no,

                    "requested_url" => $data['requested_url'],



                ];

                return view("pages/Billing/bill_summary_correction", compact("my_arr1", "params","data", "option", "branch_qry","finyr_qry", "permission"));

            }

        } else {

            if($user_option == null) {

                return view("pages/Billing/bill_summary_correction", compact("data", "option", "branch_qry","finyr_qry", "permission"));

            } 
        }



    }





    /*********************************************************************************************/

    /***************************** Billing [Reports] ****************************************/

    /*********************************************************************************************/

    public function bill_printing_draft($option = null){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches(session()->userId);

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $user_option = $option;

        $displayId   = ['client_help_id' => '4079', 'matter_help_id' => '4213'] ;

        

        if($this->request->getMethod() == 'post') {

            $requested_url = base_url($data['requested_url']);

            $display_id  = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

            $param_id    = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;

            $my_menuid   = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;

            $menu_id     = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

            $screen_ref  = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

            $index       = isset($_REQUEST['index'])?$_REQUEST['index']:null;

            $ord         = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;

            $pg          = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;

            $search_val  = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;

            //$user_option   = isset($_REQUEST['user_option'])    ? $_REQUEST['user_option']:null;



            if($user_option == ""){

                $client_matter = isset($_REQUEST['client_matter'])  ? $_REQUEST['client_matter'] : NULL;

                $range_from    = isset($_REQUEST['range_from'])     ? $_REQUEST['range_from']   : NULL;

                $range_to      = isset($_REQUEST['range_to'])       ? $_REQUEST['range_to']     : NULL;

                $input_code    = isset($_REQUEST['input_code'])     ? $_REQUEST['input_code']   : NULL;

                $input_name    = isset($_REQUEST['input_name'])     ? $_REQUEST['input_name']   : NULL;

                $req_params = ['client_matter' => $client_matter, 'range_from' => $range_from, 'range_to' => $range_to, 'input_code' => $input_code, 'input_name' => $input_name];

                if($client_matter == 'Client')

                {

                $input_stmt = " and client_code = '".$input_code."'" ;

                }

                else if($client_matter == 'Matter')

                { 

                $input_stmt = " and matter_code = '".$input_code."'" ;

                }

                else

                {

                $input_stmt = " and serial_no >= '".$range_from."' and serial_no <= '".$range_to."' " ;

                }



                $stmt = "select serial_no,date_format(bill_date,'%d-%m-%Y') bill_date,client_code,matter_code

                    from billinfo_header

                    where status_code = 'A' " . $input_stmt . " order by serial_no" ;

                //echo '<pre>';print_r($stmt);die;

                $report = $this->db->query($stmt)->getResultArray() ;

                $params['bill_cnt'] = count($report);



                foreach( $report as $reports){

                    $cl_res = $this->db->query("select client_name from client_master where client_code = '".$reports['client_code']."'")->getResultArray()[0];

                    $params['client_name'] = $cl_res['client_name'];



                    $mt_res = $this->db->query("select concat(matter_desc1,matter_desc2) matter_name from fileinfo_header where matter_code = '".$reports['matter_code']."'")->getResultArray();

                    try {

                        $mt_res = $mt_res[0];

                        $params['matter_name'] = $mt_res['matter_name'];

                        

                    } catch (\Exception $e) {

                        $params['matter_name'] = '';

                    }

                }

                try {

                $report[0];

                if($params['bill_cnt'] == 0)  throw new \Exception('No Records Found !!');



                } catch (\Exception $e) {

                    session()->setFlashdata('message', 'No Records Found !!');

                    return redirect()->to($requested_url);

                }



                return view("pages/Billing/bill_printing_draft", compact("report", "params", "displayId", "data", "req_params"));



            } else if($user_option == 'laser') {

                //echo 'n';die;

                $row_count       = isset($_REQUEST['row_count'])?$_REQUEST['row_count']:null;

                $bill_str        = isset($_REQUEST['bill_str'])?$_REQUEST['bill_str']:null;

                // echo '<pre>';var_dump($bill_str);die;

                $tot_char        = 60 ;

                $tot_no_of_lines = 60 ;

                $b_serial_no     = explode('x_x',$bill_str);

            // echo '<pre>';var_dump($b_serial_no);die;

                $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];

                $user_id        = session()->userId ;

                $curr_time      = $logdt_qry['current_time'];

                $curr_date      = $logdt_qry['current_dmydate'];

                $curr_day       = substr($curr_date,0,2) ;

                $curr_month     = substr($curr_date,3,2) ; 

                $curr_year      = substr($curr_date,6,4) ;

                // $temp_id        = "sinhaco_temp.".$user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);

                $temp_id        = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);

                $temp_table     = $temp_id . "_fb";

                $drop_stmt      = "drop table if exists $temp_table";

                $tbl_qry        = $this->temp_db->query($drop_stmt);



                $create_stmt = "create table if not exists $temp_table 

                                (srl_no              int(8) NOT NULL AUTO_INCREMENT PRIMARY KEY ,

                                row_no              int(4),

                                activity_date       date,

                                activity_desc       text,

                                io_ind              varchar(1),

                                billed_amount       double(12,2),

                                service_tax_ind     varchar(1),

                                service_tax_percent double(7,3),

                                service_tax_desc    varchar(50),

                                service_tax_amount  double(12,2))";

                $tbl_qry = $this->temp_db->query($create_stmt);
                
                $index = 1;
                for($i=1;$i<=$row_count;$i++)

                {

                    $print_ind = isset($_POST['print_ind'.$i]) ? $_POST['print_ind'.$i] :'';

                    if($print_ind == "Y") {

                        //draft bill printing for selected records

                        $bill_serial_no = isset($b_serial_no[$index]) ? $b_serial_no[$index] : ''; $index++;
// echo "<pre>"; print_r($b_serial_no); die;
                        $hdr_stmt = "select a.serial_no, date_format(a.bill_date,'%d-%m-%Y') bill_date, a.client_code, a.matter_code, a.subject_desc, a.other_case_desc, a.reference_desc,a.status_code,

                            ifnull(a.bill_amount_inpocket,'') bill_amount_inpocket,

                            ifnull(a.bill_amount_outpocket,'') bill_amount_outpocket,

                            ifnull(a.bill_amount_counsel,'') bill_amount_counsel,

                            ifnull(a.service_tax_amount,'') service_tax_amount,a.source_code

                            from billinfo_header a

                            where a.serial_no = '$bill_serial_no' ";

                        $hdr_row = $this->db->query($hdr_stmt)->getRowArray();

                        // echo '<pre>';print_r($hdr_row);die;

    

                        $serial_no = empty(!$hdr_row) ? $hdr_row['serial_no'] : '';

                        $bill_date = empty(!$hdr_row) ? $hdr_row['bill_date'] : '';

                        $client_code = empty(!$hdr_row) ? $hdr_row['client_code'] : '';

                        $matter_code = empty(!$hdr_row) ? $hdr_row['matter_code'] : '';

                        $subject_desc = empty(!$hdr_row) ? stripslashes($hdr_row['subject_desc']) : '';

                        $other_case_desc = empty(!$hdr_row) ? stripslashes($hdr_row['other_case_desc']) : '';

                        $other_case_desc = str_replace(',','<br>',$other_case_desc);

                        $reference_desc = empty(!$hdr_row) ? stripslashes($hdr_row['reference_desc']) : '';

                        $bill_amount_inpocket = empty(!$hdr_row) ? $hdr_row['bill_amount_inpocket'] : '';

                        $bill_amount_outpocket = empty(!$hdr_row) ? $hdr_row['bill_amount_outpocket'] : '';

                        $bill_amount_counsel = empty(!$hdr_row) ? $hdr_row['bill_amount_counsel'] : '';

                        $service_tax_amount  = empty(!$hdr_row) ? $hdr_row['service_tax_amount'] : '';

                        $source_code = empty(!$hdr_row) ? $hdr_row['source_code'] : '';

                        $status_code = empty(!$hdr_row) ? $hdr_row['status_code'] : '';

    

                        // fileinfo header

                        $finh_stmt = "select matter_desc1, matter_desc2, billing_addr_code, billing_attn_code from fileinfo_header where matter_code = '".$matter_code."'";

                        $finh_row = $this->db->query($finh_stmt)->getRowArray() ;

                        $matter_name    = empty(!$finh_row) ? $finh_row['matter_desc1'].'&nbsp;'.$finh_row['matter_desc2'] : '';

                        $bill_addr_code = empty(!$finh_row) ? $finh_row['billing_addr_code'] : '';

                        $bill_attn_code = empty(!$finh_row) ? $finh_row['billing_attn_code'] : '';

    

                        // client name

                        $clnt_stmt = "select * from client_master where client_code = '".$client_code."'";

                        $clnt_row = $this->db->query($clnt_stmt)->getRowArray();

                        $client_name = empty(!$clnt_row) ? $clnt_row['client_name'] : '';

    

                        // client address

                        $cadr_stmt = "select * from client_address where client_code = '".$client_code."' and address_code = '".$bill_addr_code."'";

                        $cadr_row[$i-1] = $this->db->query($cadr_stmt)->getRowArray();

    

                        // client attention

                        $catn_stmt = "select * from client_attention where client_code    = '".$client_code."' and attention_code = '".$bill_attn_code."'";

                        $catn_row = $this->db->query($catn_stmt)->getRowArray() ;

                        $attention_name = empty($catn_row) ? '' : $catn_row['attention_name'];

                        $designation    = empty($catn_row) ? '' : $catn_row['designation'];

                        $sex            = empty($catn_row) ? '' : $catn_row['sex'];

                        if ($sex == 'M') { $attention_name = 'Mr. '.$attention_name ; } 

    

                        $dtl_stmt = "select b.row_no, b.activity_date, b.activity_desc, b.io_ind,

                            ifnull(b.billed_amount,0) billed_amount,

                            ifnull(b.service_tax_ind,'N') service_tax_ind,

                            ifnull(b.service_tax_percent,'N') service_tax_percent,

                            if(ifnull(b.service_tax_ind,'N')='Y','(A) TAXABLE SERVICE','(B) NON TAXABLE SERVICE') service_tax_desc,

                            ifnull(b.service_tax_amount,0) service_tax_amount

                            from billinfo_detail b

                            where b.ref_billinfo_serial_no  = '$bill_serial_no'

                            and ifnull(b.printer_ind,'N') = 'Y'

                            order by ifnull(b.service_tax_ind,'N') desc, b.prn_seq_no ";

                        $dtl_qry = $this->db->query($dtl_stmt)->getResultArray();

    

                        $dele_stmt = "delete from $temp_table";

                        $dele_qry  = $this->temp_db->query($dele_stmt);

                        // echo '<pre>';print_r($dtl_qry);die;

                        foreach($dtl_qry as $dtl_row)

                        {

                            $row_no            = $dtl_row['row_no'];

                            $activity_date     = $dtl_row['activity_date'];

                            $activity_desc     = $dtl_row['activity_desc'] . chr(13);

                            $io_ind            = $dtl_row['io_ind'];

                            $billed_amount     = $dtl_row['billed_amount'];

                            $serv_tax_ind      = $dtl_row['service_tax_ind'];

                            $serv_tax_per      = $dtl_row['service_tax_percent'];

                            $serv_tax_desc     = $dtl_row['service_tax_desc'];

                            $serv_tax_amount   = $dtl_row['service_tax_amount'];

    

                            $actvt_desc    = wordwrap($activity_desc, $tot_char, "\n");

                            $actvt_array   = explode("\n",$actvt_desc);

                            $row_cnt       = count($actvt_array);

    

                            // insertion of first line into temp table

                            $inst_stmt = "insert into $temp_table

                                    (row_no,activity_date,activity_desc,io_ind,billed_amount,service_tax_ind,service_tax_percent,service_tax_desc,service_tax_amount)

                                    values($row_no,'$activity_date','".addslashes($actvt_array[0])."','$io_ind',$billed_amount,'$serv_tax_ind','$serv_tax_per','$serv_tax_desc','$serv_tax_amount')";

                            $inst_qry = $this->temp_db->query($inst_stmt);

                            for($j=1;$j<$row_cnt;$j++)

                            {

                                $inst_stmt = "insert into $temp_table (row_no,activity_desc,service_tax_ind,service_tax_percent)

                                        values($row_no,'".addslashes($actvt_array[$j])."','$serv_tax_ind','$serv_tax_per')";

                                $inst_qry = $this->temp_db->query($inst_stmt);

                            }

                        }

                        $sele_stmt = "select date_format(activity_date,'%d-%m-%Y') activity_date, activity_desc, io_ind,

                            ifnull(billed_amount,0) billed_amount, service_tax_ind, service_tax_percent, service_tax_desc,

                            ifnull(service_tax_amount,0) service_tax_amount 

                            from $temp_table

                            order by service_tax_ind desc, srl_no, row_no ";

                        $sele_qry[$i-1] = $this->temp_db->query($sele_stmt)->getResultArray() ;

                        // echo '<pre>';print_r($sele_qry);die;

    

                        $selecnt_qry = $this->temp_db->query("select * from $temp_table ")->getResultArray() ; 

                        //$selecnt_qry = $this->temp_db->query($selecnt_sql)->getResultArray() ;

                        $selecnt_nos = count($selecnt_qry) ;

                        // echo '<pre>';print_r($sele_qry);die;

                        

                        $params[$i-1] = [

                            "serial_no" => $serial_no,

                            "bill_date" => $bill_date,

                            "status_code" => $status_code,

                            "client_name" => $client_name,

                            "attention_name" => $attention_name,

                            "matter_name" => $matter_name,

                            "other_case_desc" => $other_case_desc,

                            "source_code" => $source_code,

                            "reference_desc" => $reference_desc,

                            "subject_desc" => $subject_desc,

                            "selecnt_nos" => $selecnt_nos,

                            "tot_no_of_lines" => $tot_no_of_lines,

                            "tot_char" => $tot_char,

                            "service_tax_amount" => $service_tax_amount,

                            "requested_url" => $requested_url,

                        ];

                    }

                }

                // echo "<pre>";print_r($params);die;

                return view("pages/Billing/bill_printing_draft", compact("cadr_row" ,"params", "sele_qry", "data", "displayId"));

            }

        }else{

            return view("pages/Billing/bill_printing_draft", compact("data", "displayId"));

        }

    }
    public function bill_printing_final($option = null){

        // $arr['leftMenus'] = menu_data(); 

        // $arr['menuHead'] = [0];

        $data = branches(session()->userId);

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $user_option = $option;

        $displayId   = ['client_help_id' => '4079', 'matter_help_id' => '4213'] ;



        if($this->request->getMethod() == 'post') {

            $requested_url = base_url($data['requested_url']);

            $display_id  = isset($_REQUEST['display_id'])  ? $_REQUEST['display_id']  : NULL;

            $param_id    = isset($_REQUEST['param_id'])    ? $_REQUEST['param_id']    : NULL;

            $my_menuid   = isset($_REQUEST['my_menuid'])   ? $_REQUEST['my_menuid']   : NULL;

            $menu_id     = isset($_REQUEST['menu_id'])     ? $_REQUEST['menu_id']     : NULL;

            //$user_option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : NULL;

            // echo "<pre>";print_r($user_option);die;

            $screen_ref  = isset($_REQUEST['screen_ref'])  ? $_REQUEST['screen_ref']  : NULL;

            $index       = isset($_REQUEST['index'])       ? $_REQUEST['index']       : NULL;

            $ord         = isset($_REQUEST['ord'])         ? $_REQUEST['ord']         : NULL;

            $pg          = isset($_REQUEST['pg'])          ? $_REQUEST['pg']          : NULL;

            $search_val  = isset($_REQUEST['search_val'])  ? $_REQUEST['search_val']  : NULL;

            if($user_option == ""){

                $client_matter = isset($_REQUEST['client_matter'])  ? $_REQUEST['client_matter'] : NULL;

                $params['final_bill_date'] = isset($_REQUEST['final_bill_date'])  ? $_REQUEST['final_bill_date'] : NULL;

                $range_from    = isset($_REQUEST['range_from'])     ? $_REQUEST['range_from']   : NULL;

                $range_to      = isset($_REQUEST['range_to'])       ? $_REQUEST['range_to']     : NULL;

                $input_code    = isset($_REQUEST['input_code'])     ? $_REQUEST['input_code']   : NULL;
                
                $input_name    = isset($_REQUEST['input_name'])     ? $_REQUEST['input_name']   : NULL;

                $req_params = ['client_matter' => $client_matter, 'range_from' => $range_from, 'range_to' => $range_to, 'input_code' => $input_code, 'input_name' => $input_name];

                if($client_matter == 'Client')

                {

                $input_stmt = " client_code = '".$input_code."'" ;

                }

                else if($client_matter == 'Matter')

                {

                $input_stmt = " matter_code = '".$input_code."'" ;

                }

                else

                {

                $input_stmt = " concat(fin_year,'/',bill_no) >= '".$range_from."' and concat(fin_year,'/',bill_no) <= '".$range_to."' " ;

                }



                $stmt = "select serial_no, concat(fin_year,'/',bill_no) bill_number, date_format(bill_date,'%d-%m-%Y') bill_date, client_code, matter_code

                    from bill_detail    

                    where cancel_ind IS NULL and " . $input_stmt . " order by serial_no" ;



                //echo "<pre>";print_r($stmt);die;

                $report = $this->db->query($stmt)->getResultArray() ;

                $params['bill_cnt'] = count($report);



                foreach( $report as $reports){

                $cl_res = $this->db->query("select client_name from client_master where client_code = '".$reports['client_code']."'")->getResultArray()[0];

                $params['client_name'] = $cl_res['client_name'];



                $mt_res = $this->db->query("select concat(matter_desc1,matter_desc2) matter_name from fileinfo_header where matter_code = '".$reports['matter_code']."'")->getResultArray();

                try {

                    $mt_res = $mt_res[0];

                    $params['matter_name'] = $mt_res['matter_name'];

                    

                } catch (\Exception $e) {

                    $params['matter_name'] = '';

                }

                }



                try {

                $report[0];

                if($params['bill_cnt'] == 0)  throw new \Exception('No Records Found !!');

        

                } catch (\Exception $e) {

                    session()->setFlashdata('message', 'No Records Found !!');

                    return redirect()->to($requested_url);

                }

                return view("pages/Billing/bill_printing_final", compact("report", "params", "displayId", "data", "req_params"));



            } 

            else if($user_option == 'laser') {
                $bill_row = $params = $cadr_row = $sele_qry = [];
                $row_count       = isset($_REQUEST['row_count'])?$_REQUEST['row_count']:null;

                $bill_str        = isset($_REQUEST['bill_str'])?$_REQUEST['bill_str']:null;
                // echo '<pre>';print_r($_POST); die;

                $final_bill_date = isset($_REQUEST['final_bill_date'])?$_REQUEST['final_bill_date']:null;

                // echo '<pre>';print_r($final_bill_date); die;

                $dupl_ind        = isset($_REQUEST['dupl_ind'])?$_REQUEST['dupl_ind']:null;

                $revd_ind        = isset($_REQUEST['revd_ind'])?$_REQUEST['revd_ind']:null;

                $recd_ind        = isset($_REQUEST['recd_ind'])?$_REQUEST['recd_ind']:null;

                $prop_ind        = isset($_REQUEST['prop_ind'])?$_REQUEST['prop_ind']:null;

                $copy_ind        = isset($_REQUEST['copy_ind'])?$_REQUEST['copy_ind']:null;

                $tot_char        = 58 ;

                $tot_no_of_lines = 60 ;

                $b_serial_no     = explode('x_x',$bill_str);

                $x25thLogoYear   = get_parameter_value('20') ;

                $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];

                $user_id        = session()->userId ;

                $curr_time      = $logdt_qry['current_time'];

                $curr_date      = $logdt_qry['current_dmydate'];

                $curr_day       = substr($curr_date,0,2) ;

                $curr_month     = substr($curr_date,3,2) ; 

                $curr_year      = substr($curr_date,6,4) ;

                // $temp_id        = "sinhaco_temp.".$user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);

                $temp_id        = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);

                $temp_table     = $temp_id . "_fb";

                $drop_stmt      = "drop table if exists $temp_table";

                $tbl_qry        = $this->temp_db->query($drop_stmt);

                $create_stmt = "create table if not exists $temp_table 

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

                            service_tax_amount  double(12,2))";

                $tbl_qry = $this->temp_db->query($create_stmt);
                
                for($i=1; $i < count($b_serial_no); $i++)

                { 

                    // $print_ind = isset($_POST['print_ind'.$i]) ? $_POST['print_ind'.$i] :'';

                    // if($print_ind == "Y") {

                        $bill_serial_no = isset($b_serial_no[$i]) ? $b_serial_no[$i] : ''; //$index++;

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

                                from bill_detail b left outer join billinfo_header a on a.ref_bill_serial_no = b.serial_no 

                                where b.serial_no = '$bill_serial_no' "; 

    

                        $hdr_row = $this->db->query($hdr_stmt)->getRowArray();

                    // echo '<pre>';print_r($hdr_row); die;

                        $ref_bill_serial_no    = empty($hdr_row) ? '' : $hdr_row['ref_bill_serial_no'];

                        $serial_no             = empty($hdr_row) ? '' : $hdr_row['serial_no'];

                        $branch_code           = empty($hdr_row) ? '' : $hdr_row['branch_code'];

                        $matter_code           = empty($hdr_row) ? '' : $hdr_row['matter_code'];

                        $subject_desc          = empty($hdr_row) ? '' : stripslashes($hdr_row['subject_desc']);

                        $other_case_desc       = empty($hdr_row) ? '' : stripslashes($hdr_row['other_case_desc']);

                        $other_case_desc       = str_replace(',','<br>',$other_case_desc);

                        $reference_desc        = empty($hdr_row) ? '' : stripslashes($hdr_row['reference_desc']);

                        $no_fee_bill_ind       = empty($hdr_row) ? '' : $hdr_row['no_fee_bill_ind'];

                        $direct_counsel_ind    = empty($hdr_row) ? '' : stripslashes($hdr_row['direct_counsel_ind']);

                        $bill_amount_inpocket  = empty($hdr_row) ? '' : $hdr_row['bill_amount_inpocket'];

                        $bill_amount_outpocket = empty($hdr_row) ? '' : $hdr_row['bill_amount_outpocket'];

                        $bill_amount_counsel   = empty($hdr_row) ? '' : $hdr_row['bill_amount_counsel'];

                        $service_tax_amount    = empty($hdr_row) ? '' : $hdr_row['service_tax_amount'];

                        $source_code           = empty($hdr_row) ? '' : $hdr_row['source_code'];

    

                        $myqry1 = $this->db->query("select subject_desc, reference_desc from fileinfo_header where matter_code = '$matter_code' ")->getResultArray();

                        try {

                            $myqry1 = $myqry1[0];

                            $subj_desc = stripslashes($myqry1['subject_desc']);

                            $refr_desc = stripslashes($myqry1['reference_desc']) ;

                            

                        } catch (\Exception $e) {

                            $subj_desc = '';

                            $refr_desc = '';

                        }

                        // $subj_desc = stripslashes($myqry1['subject_desc']);

                        // $refr_desc = stripslashes($myqry1['reference_desc']) ;

                        if ($subject_desc   == '') { $subject_desc   = $subj_desc ; }

                        if ($reference_desc == '') { $reference_desc = $refr_desc ; }

    

                        $branch_sql         = $this->db->query("select * from branch_master where branch_code = '$branch_code' ")->getRowArray();

                        $branch_addr1       = empty($branch_sql) ? '' : $branch_sql['address_line_1'].', '.$branch_sql['city'].' - '.$branch_sql['pin_code'] ;

                        $branch_addr2       = empty($branch_sql) ? '' : 'TEL : '.$branch_sql['phone_no'].'     FAX : '.$branch_sql['fax_no'] ;

                        $branch_addr3       = empty($branch_sql) ? '' : 'E-Mail : '.$branch_sql['email_id'] ;

                        $branch_pan_no      = empty($branch_sql) ? '' : 'PAN : '.$branch_sql['pan_no'] ;

                        $branch_service_tax = empty($branch_sql) ? '' : 'SERVICE TAX REGN. NO. : '.$branch_sql['pan_no'] ;

                        $service_nature     = 'NATURE OF SERVICE : LEGAL CONSULTANT`S SERVICE';

                        //  $service_dec        = 'WITH EFFECT FROM JULY 1, 2012, IN TERMS OF NOTIFICATION NO. 30/2012-ST DATED JUNE 20, 2012 READ WITH CORRIGENDUM DATED JUNE 29, 2012 IN RESPECT OF LEGAL SERVICES PROVIDED BY AN INDIVIDUAL ADVOCATE OR A FIRM OF ADVOCATES, SERVICE TAX AS APPLICABLE IS BY THE SERVICE RECEIVER. HENCE SUCH TAX NOT INCLUDED.';

                        $service_dec        = 'GST on the above services are payable on reverse charge basis by you, the service recipient.';

    

                        

                        $my_company_name = 'Sinha & Company' ;

    

                        

                        $direct_memo  = 'Counsel fee not included, payable directly by you as per memo(s).';

                        $direct_memo = strtoupper($direct_memo) ;

                        // bill detail

                        $bill_stmt = "select fin_year,bill_no,client_code,address_code,attention_code,date_format(bill_date,'%d-%m-%Y') bill_date, bill_date b_d

                                                                ,sum(ifnull(bill_amount_inpocket_stax,0)) bill_amount_inpocket_stax

                                                                ,sum(ifnull(bill_amount_outpocket_stax,0)) bill_amount_outpocket_stax

                                                                ,sum(ifnull(bill_amount_counsel_stax,0)) bill_amount_counsel_stax

                                                                ,sum(ifnull(bill_amount_inpocket_ntax,0)) bill_amount_inpocket_ntax

                                                                ,sum(ifnull(bill_amount_outpocket_ntax,0)) bill_amount_outpocket_ntax

                                                                ,sum(ifnull(bill_amount_counsel_ntax,0)) bill_amount_counsel_ntax

                                                                ,service_tax_amount from bill_detail where serial_no = '".$ref_bill_serial_no."' group by fin_year,bill_no,bill_date";

                        $bill_row[$i-1] = $this->db->query($bill_stmt)->getRowArray();

                        //echo '<pre>';print_r($bill_row[$i-1]); die;

                        

                        $finh_stmt = "select trust_name,matter_desc1,matter_desc2,billing_addr_code,billing_attn_code from fileinfo_header where matter_code = '".$matter_code."'";

                        $finh_row = $this->db->query($finh_stmt)->getResultArray(); 

                        try {

                            $finh_row = $finh_row[0];

                            $matter_name    = stripslashes($finh_row['matter_desc1']).'&nbsp;'.stripslashes($finh_row['matter_desc2']);

                            $trust_name     = stripslashes($finh_row['trust_name']);

                            

                        } catch (\Exception $e) {

                            $matter_name    = '';

                            $trust_name     = '';

                        }

                        // $matter_name    = stripslashes($finh_row['matter_desc1']).'&nbsp;'.stripslashes($finh_row['matter_desc2']);

                        // $trust_name     = stripslashes($finh_row['trust_name']);

                        //foreach($bill_row as $bill_rows) {

                        

                        $clnt_stmt = "select * from client_master where client_code = '".$bill_row[$i-1]['client_code']."'";

                        $clnt_row = $this->db->query($clnt_stmt)->getResultArray()[0];

                        $client_name = $clnt_row['client_name'];

                        // echo '<pre>';print_r($client_name); die;

    

                        $cadr_stmt = "select * from client_address where client_code = '".$bill_row[$i-1]['client_code']."' and address_code = '".$bill_row[$i-1]['address_code']."'";

                        $cadr_row[$i-1] = $this->db->query($cadr_stmt)->getRowArray();

                        // echo '<pre>';print_r($cadr_row);die;

                        $gst_sql         = $this->db->query("select client_gst, pan_no from client_address where client_code = '".$bill_row[$i-1]['client_code']."' and address_code = '".$bill_row[$i-1]['address_code']."'")->getRowArray();

                        $client_gst      = empty($gst_sql) ? '' : $gst_sql['client_gst'] ;

                        $pan_no          = empty($gst_sql) ? '' : $gst_sql['pan_no'] ;
    

                        $state_sql        = $this->db->query("SELECT a.state_code, a.state_name, a.zone_code, a.gst_zone_code, a.country FROM state_master a, client_address b where a.state_code = b.state_code and a.state_code <> '33' and b.address_code = '".$bill_row[$i-1]['address_code']."'")->getRowArray(); 

                        $state_name       = empty($state_sql) ? '' : $state_sql['state_name'] ;

                        $gst_zone_code    = empty($state_sql) ? '' : $state_sql['gst_zone_code'] ;
    

                        $catn_stmt = "select * from client_attention where client_code = '".$bill_row[$i-1]['client_code']."' and attention_code = '".$bill_row[$i-1]['attention_code']."'";

                        $catn_row = $this->db->query($catn_stmt)->getRowArray();

                        $attention_name = empty($catn_row) ? '' : $catn_row['attention_name'];

                        $designation    = empty($catn_row) ? '' : $catn_row['designation'];

                        $sex            = empty($catn_row) ? '' : $catn_row['sex'];

                        $title          = empty($catn_row) ? '' : $catn_row['title'];

    

                        if($title != 'ORS') { $attention_name = $title.' '.$attention_name; }

                        if($title == 'ORS') { $attention_name = $attention_name; }

                    //}

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

                                where b.ref_billinfo_serial_no  = '$serial_no'

                                    and ifnull(b.printer_ind,'N') = 'Y'

                            order by ifnull(b.service_tax_ind,'N') desc, b.prn_seq_no ";

                        $dtl_qry = $this->db->query($dtl_stmt)->getResultArray();

                        $dele_stmt = "delete from $temp_table";

                        $dele_qry  = $this->temp_db->query($dele_stmt);

                        // echo '<pre>';print_r($dtl_qry);die;

                        foreach($dtl_qry as $dtl_row)

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

    

                            $actvt_desc    = wordwrap($activity_desc, $tot_char, "\n");

                            $actvt_array   = explode("\n",$actvt_desc);

                            $row_cnt       = count($actvt_array);

    

                            // insertion of first line into temp table

                            $inst_stmt = "insert into $temp_table

                                            (row_no,activity_date,activity_desc,io_ind,billed_amount,service_tax_ind,service_tax_percent,service_tax_desc,service_tax_amount,source_code)

                                        values($row_no,'$activity_date','".addslashes($actvt_array[0])."','$io_ind',$billed_amount,'$serv_tax_ind','$serv_tax_per','$serv_tax_desc','$serv_tax_amount','$source_code_dtl')";

                            $inst_qry = $this->temp_db->query($inst_stmt);

    

                            for($j=1;$j<$row_cnt;$j++)

                            {

                            $inst_stmt = "insert into $temp_table

                                                    (row_no,activity_desc,service_tax_ind,service_tax_percent)

                                            values($row_no,'".addslashes($actvt_array[$j])."','$serv_tax_ind','$serv_tax_per')";

                            $inst_qry = $this->temp_db->query($inst_stmt);

                            }

                        }

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

                        $sele_qry[$i-1] = $this->temp_db->query($sele_stmt)->getResultArray();

    

                        $selecnt_sql = "select * from $temp_table " ;

                        $selecnt_qry = $this->temp_db->query($selecnt_sql)->getResultArray() ;

    

                        $selecnt_nos = count($selecnt_qry);

                        // echo '<pre>';print_r($selecnt_qry);die;

                        

                        $params[$i-1] = [

                            "trust_name" => $trust_name,

                            "client_name" => $client_name,

                            "pan_no" => $pan_no,

                            "state_name" => $state_name,

                            "final_bill_date" => $final_bill_date,

                            "client_gst" => $client_gst,

                            "gst_zone_code" => $gst_zone_code,

                            "attention_name" => $attention_name,

                            "designation" => $designation,

                            "matter_name" => $matter_name,

                            "other_case_desc" => $other_case_desc,

                            "source_code" => $source_code,

                            "reference_desc" => $reference_desc,

                            "subject_desc" => $subject_desc,

                            "prop_ind" => $prop_ind,

                            "dupl_ind" => $dupl_ind,

                            "revd_ind" => $revd_ind,

                            "recd_ind" => $recd_ind,

                            "copy_ind" => $copy_ind,

                            "direct_counsel_ind" => $direct_counsel_ind,

                            "direct_memo" => $direct_memo,

                            "branch_pan_no" => $branch_pan_no,

                            "branch_service_tax" => $branch_service_tax,

                            "service_nature" => $service_nature,

                            "service_dec" => $service_dec,

                            "selecnt_nos" => $selecnt_nos,

                            "tot_no_of_lines" => $tot_no_of_lines,

                            "tot_char" => $tot_char,

                            "service_tax_amount" => $service_tax_amount,

                            "no_fee_bill_ind" => $no_fee_bill_ind

                        ];

                    // }

                }

                // echo "<pre>"; print_r($print_ind); die;

                return view("pages/Billing/bill_printing_final", compact("bill_row", "params", "cadr_row", "sele_qry", "data", "displayId" ));

            }



        }else{



            return view("pages/Billing/bill_printing_final", compact("data", "displayId"));

        }



    }
    public function bill_register_bill_client_matter_initial() {   //bill-wise report-seq with summary report type not combine together//

        

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

       	$data = branches(session()->userId);

    	$data['requested_url'] = $this->session->requested_end_menu_url;

        $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4220' , 'initial_help_id' => '4191'] ; 



        if($this->request->getMethod() == 'post') {

            // echo "m";die;

            $requested_url = base_url($data['requested_url']);

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



            $branch_code     = $_REQUEST['branch_code'] ;

            $start_date      = $_REQUEST['start_date'] ;      

            if($start_date != '')    { $start_date_ymd = date_conv($start_date); } else { $start_date_ymd = '1901-01-01'; }

            $end_date        = $_REQUEST['end_date'] ;        

            if($end_date   != '')    { $end_date_ymd   = date_conv($end_date);   } else { $end_date_ymd   = date('Y-m-d') ; }

            $client_code     = $_REQUEST['client_code'] ;     

            if(empty($client_code))  { $client_code  = '%' ; }

            $client_name     = $_REQUEST['client_name'] ;

            $matter_code     = $_REQUEST['matter_code'] ;     

            if(empty($matter_code))  { $matter_code  = '%' ; }

            $matter_desc     = $_REQUEST['matter_desc'] ;

            $initial_code    = $_REQUEST['initial_code'] ;    

            if(empty($initial_code)) { $initial_code = '%' ; }

            $initial_name    = $_REQUEST['initial_name'] ;

            $billfor_ind     = $_REQUEST['billfor_ind'] ;  

            $report_seqn     = $_REQUEST['report_seqn'] ;

            $report_type     = $_REQUEST['report_type'] ;

            $output_type     = $_REQUEST['output_type'] ;

            $court_code      = isset($_REQUEST['court_code']) ? $_REQUEST['court_code'] : '' ;      

            if(empty($court_code))  { $court_code   = '%' ; }

            //$branch_name     = getBranchName($branch_code) ;

            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0];

            $branch_name   = $branch_qry["branch_name"] ;

            $old_matter_desc = 'ALL MATTERS UPTO 30/09/2003' ;



            if ($report_seqn == 'B' && $report_type == 'S') {

                session()->setFlashdata('valid_message', 'Sorry !!!  This facility is not VALID !!');

                return redirect()->to($requested_url);

            }



            //

            if($output_type == 'Report' || $output_type == 'Pdf') {

                if($client_code   == '%') { $client_heading  = 'CLIENT  : ALL'  ; } else { $client_heading  = 'CLIENT  : SELECTIVE'  ; }

                if($matter_code   == '%') { $matter_heading  = 'MATTER  : ALL'  ; } else { $matter_heading  = 'MATTER  : SELECTIVE'  ; }

                if($initial_code  == '%') { $initial_heading = 'INITIAL : ALL'  ; } else { $initial_heading = 'INITIAL : SELECTIVE'  ; }

                $report_sub_desc = '[ '.$client_heading.' / '.$matter_heading.' / '.$initial_heading.' ]' ;

                //

                if($start_date    == '' ) {$period_desc = "UPTO ".$end_date ;} else {$period_desc = $start_date.' - '.$end_date ;}



                $bill_sql = '';

                switch($report_type) {

                    case 'D' :

                        $report_desc = "BILL REGISTER (DETAIL)" ;



                        if($report_seqn == 'B')      { $order_by_clause = "a.bill_date" ; }

                        else if($report_seqn == 'C') { $order_by_clause = "b.client_name,a.bill_date" ; }

                        else if($report_seqn == 'M') { $order_by_clause = "a.matter_code,a.bill_date" ; }

                        else if($report_seqn == 'I') { session()->setFlashdata('message', 'No Records Found !!'); return redirect()->to($requested_url); }

                        // $bill_sql    = "select concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.matter_code, c.initial_code,ifnull(a.bill_amount_inpocket, 0) ipamt,ifnull(a.bill_amount_outpocket,0) opamt,ifnull(a.bill_amount_counsel,  0) cnamt,ifnull(a.service_tax_amount,   0) stamt,(ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt,b.client_name,if(c.matter_desc1 != '', concat(c.matter_desc1,' : ',c.matter_desc2),c.matter_desc2) matter_desc,c.court_code,d.code_desc court_name

                        //             from bill_detail a , client_master b, fileinfo_header c, code_master d

                        //             where a.branch_code like '$branch_code'

                        //             and a.client_code like '$client_code'

                        //             and a.matter_code like '$matter_code'

                        //             and ifnull(c.initial_code,'N') like '$initial_code' 

                        //             and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                        //             and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                        //             and a.client_code = b.client_code

                        //             and a.matter_code = c.matter_code

                        //             and c.court_code like '$court_code'

                        //             and c.court_code = d.code_code and d.type_code = '001' 

                        //             and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                        //             order by ".$order_by_clause ; 

                        $bill_sql="select concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.matter_code, 

                                    c.initial_code,ifnull(a.bill_amount_inpocket, 0) ipamt,ifnull(a.bill_amount_outpocket,0) opamt,

                                    ifnull(a.bill_amount_counsel,  0) cnamt,ifnull(a.service_tax_amount,   0) stamt,

                                    (ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt,

                                    b.client_name,if(c.matter_desc1 != '',

                                    concat(c.matter_desc1,' : ',c.matter_desc2),c.matter_desc2) matter_desc,

                                    c.court_code,d.code_desc court_name from

                                    bill_detail a LEFT JOIN client_master b ON a.client_code = b.client_code

                                    LEFT JOIN fileinfo_header c ON a.matter_code = c.matter_code 

                                    LEFT JOIN code_master d ON c.court_code = d.code_code 

                                    where a.branch_code like '$branch_code' and a.client_code like '$client_code' and 

                                    a.matter_code like '$matter_code' and ifnull(c.initial_code,'N') like '$initial_code' 

                                    and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' and a.bill_date between '$start_date_ymd' 

                                    and '$end_date_ymd' and c.court_code like '$court_code' and c.court_code = d.code_code 

                                    and d.type_code = '001' and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')";



                        //echo '<pre>';print_r($bill_sql);die;

                        break;



                    case 'S' :

                        $old_matter_desc = 'ALL MATTERS UPTO 30/09/2003' ;

                        $report_desc      = "BILL REGISTER (SUMMARY)" ;



                        if($report_seqn == 'C')      { $group_by_clause = "b.client_name"  ; $order_by_clause = "b.client_name" ; }

                        else if($report_seqn == 'M') { $group_by_clause = "a.matter_code"  ; $order_by_clause = "a.matter_code" ;}

                        else if($report_seqn == 'I') { $group_by_clause = "d.initial_name" ; $order_by_clause = "d.initial_name" ;}



                        $bill_sql    = "select a.client_code,a.matter_code,a.initial_code,ifnull(a.bill_amount_inpocket, 0) ipamt,ifnull(a.bill_amount_outpocket,0) opamt,ifnull(a.bill_amount_counsel,  0) cnamt,ifnull(a.service_tax_amount,   0) stamt,(ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt,b.client_name,

                                if(substring(a.matter_code,1,1)='0','$old_matter_desc',if(c.matter_desc1 != '', concat(c.matter_desc1,' : ',c.matter_desc2),c.matter_desc2)) matter_desc,d.initial_name

                                from bill_detail a left join fileinfo_header c on a.matter_code = c.matter_code, client_master b, initial_master d

                                where a.branch_code like '$branch_code'

                                and a.client_code like '$client_code'

                                and a.matter_code like '$matter_code'

                                and a.initial_code like '$initial_code'

                                and ifnull(a.court_fee_bill_ind,'N')   like '$billfor_ind'

                                and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                                and a.client_code = b.client_code

                                and a.initial_code = d.initial_code 

                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                                order by ".$order_by_clause ; 

                        break;

                }



                $reports  = $this->db->query($bill_sql)->getResultArray() ;

                        // echo '<pre>';print_r($reports);die;

                $bill_cnt  = count($reports);

                $date = date('d-m-Y');



            	if(empty($reports)) {

                    session()->setFlashdata('message', 'No Records Found !!');

                    return redirect()->to($requested_url);

                }

//                 try {

//                 $reports[0];

//                 if($bill_cnt == 0)  throw new \Exception('No Records Found !!');



//                 } catch (\Exception $e) {

//                     session()->setFlashdata('message', 'No Records Found !!');

//                     return redirect()->to($_SERVER['REQUEST_URI']);

//                 }



                $params = [

                    "branch_name" => $branch_name,

                    "report_desc" => $report_desc,

                    "bill_cnt" => $bill_cnt,

                    "report_sub_desc" => $report_sub_desc,

                    "period_desc" => $period_desc,

                    "client_code" => $client_code,

                    "client_name" => $client_name,

                    "matter_code" => $matter_code,

                    "matter_desc" => $matter_desc,

                    "initial_code" => $initial_code,

                    "initial_name" => $initial_name,

                    "billfor_ind" => $billfor_ind,

                    "report_seqn" => $report_seqn,

                    "report_type" => $report_type,

                    "date" => $date,

                    "requested_url" => $requested_url,

                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/Billing/bill_register_billwise", compact("reports", "params", "report_type"));
                    // echo htmlspecialchars($reportHTML); die;
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/Billing/bill_register_billwise", compact("reports", "params"));


            } else if($output_type == 'Excel'){ 

                $bill_sql = '';

                switch($report_type) {

                    case 'D' :

                        

                        if($report_seqn == 'B')      { $order_by_clause = "a.bill_date" ; }

                        else if($report_seqn == 'C') { $order_by_clause = "b.client_name,a.bill_date" ; }

                        else if($report_seqn == 'M') { $order_by_clause = "a.matter_code,a.bill_date" ; }

                        else if($report_seqn == 'I') { $order_by_clause = "d.initial_name,a.bill_date" ; }

                        $bill_sql = "select concat(a.fin_year,'/',a.bill_no) bill_number, a.bill_date, a.client_code, a.matter_code, a.initial_code,

                                ifnull(a.bill_amount_inpocket, 0) ipamt, ifnull(a.bill_amount_outpocket,0) opamt, ifnull(a.bill_amount_counsel,  0) cnamt, ifnull(a.service_tax_amount,   0) stamt,

                                (ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt, b.client_name,

                                if(substring(a.matter_code,1,1)='0','',c.matter_desc1) matter_desc1, if(substring(a.matter_code,1,1)='0','$old_matter_desc',c.matter_desc2) matter_desc2, d.initial_name

                                from bill_detail a left join fileinfo_header c on a.matter_code = c.matter_code, client_master b, initial_master d

                                where a.branch_code like '$branch_code'

                                and a.client_code like '$client_code'

                                and a.matter_code like '$matter_code'

                                and a.initial_code like '$initial_code'

                                and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind'

                                and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                                and a.client_code = b.client_code

                                and a.initial_code = d.initial_code 

                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                        order by ".$order_by_clause ;  

                        break;



                    case 'S' :



                        if($report_seqn == 'C')      { $group_by_clause = "b.client_name"  ; $order_by_clause = "b.client_name" ; }

                        else if($report_seqn == 'M') { $group_by_clause = "a.matter_code"  ; $order_by_clause = "a.matter_code" ;}

                        else if($report_seqn == 'I') { $group_by_clause = "d.initial_name" ; $order_by_clause = "d.initial_name" ;}



                        $bill_sql = "select a.client_code, a.matter_code, a.initial_code, sum(ifnull(a.bill_amount_inpocket, 0)) ipamt, sum(ifnull(a.bill_amount_outpocket,0)) opamt, sum(ifnull(a.bill_amount_counsel,  0)) cnamt,

                            sum(ifnull(a.service_tax_amount, 0)) stamt, sum((ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0))) totamt,

                            b.client_name, if(substring(a.matter_code,1,1)='0','',c.matter_desc1) matter_desc1, if(substring(a.matter_code,1,1)='0','$old_matter_desc',c.matter_desc2) matter_desc2, d.initial_name

                            from bill_detail a left join fileinfo_header c on a.matter_code = c.matter_code, client_master b, initial_master d

                            where a.branch_code like '$branch_code'

                            and a.client_code like '$client_code'

                            and a.matter_code like '$matter_code'

                            and a.initial_code like '$initial_code'

                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind'

                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                            and a.client_code = b.client_code

                            and a.initial_code = d.initial_code 

                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                        group by ".$group_by_clause." order by ".$order_by_clause ; 

                        break;

                }



                $excels  = $this->db->query($bill_sql)->getResultArray() ;

                        // echo '<pre>';print_r($reports);die;

                $bill_cnt  = count($excels);



                try {

                    $excels[0];

                    if($bill_cnt == 0)  throw new \Exception('No Records Found !!');



                } catch (\Exception $e) {

                    session()->setFlashdata('message', 'No Records Found !!');

                    return redirect()->to($this->requested_url());

                }

                $fileName = 'BILL_REGISTER-'.date('d-m-Y').'.xlsx';  

                $spreadsheet = new Spreadsheet();

                $sheet = $spreadsheet->getActiveSheet();



                switch($report_type) {

                    case 'D' :

                        // Define the headings

                        $headings = ['Bill No', 'Bill Date', 'Client', 'Matter', 'Case', 'Description', 'Initial', 'Inpocket', 'Outpocket', 'Counsel', 'Service Tax', 'Total'];



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



                            $sheet->setCellValue('A' . $rows, $excel['bill_number']);

                            $sheet->setCellValue('B' . $rows, date_conv($excel['bill_date']));

                            $sheet->setCellValue('C' . $rows, strtoupper($excel['client_name']));

                            $sheet->setCellValue('D' . $rows, strtoupper($excel['matter_code']));

                            $sheet->setCellValue('E' . $rows, strtoupper($excel['matter_desc1']));

                            $sheet->setCellValue('F' . $rows, strtoupper($excel['matter_desc2']));

                            $sheet->setCellValue('G' . $rows, strtoupper($excel['initial_code']));

                            $sheet->setCellValue('H' . $rows, ($excel['ipamt'] > 0) ? number_format($excel['ipamt'], 2,'.','') : '');

                            $sheet->setCellValue('I' . $rows, ($excel['opamt'] > 0) ? number_format($excel['opamt'], 2,'.','') : '');

                            $sheet->setCellValue('J' . $rows, ($excel['cnamt'] > 0) ? number_format($excel['cnamt'], 2,'.','') : '');

                            $sheet->setCellValue('K' . $rows, ($excel['stamt'] > 0) ? number_format($excel['stamt'], 2,'.','') : '');

                            $sheet->setCellValue('L' . $rows, ($excel['totamt'] > 0) ? number_format($excel['totamt'], 2,'.','') : '');

                            

                            // Apply border to the current row

                            $style = $sheet->getStyle('A' . $rows . ':L' . $rows);

                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



                            $rows++;

                        } break;

                    case 'S' :

                        // Define the headings

                        $headings = ['Code', 'Name', 'Inpocket', 'Outpocket', 'Counsel', 'Service Tax', 'Total'];



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

                            if($report_seqn == 'C') {$level_code = $excel['client_code']  ; $level_name = $excel['client_name']  ; }

                            if($report_seqn == 'M') {$level_code = $excel['matter_code']  ; $level_name = $excel['matter_desc']  ; }

                            if($report_seqn == 'I') {$level_code = $excel['initial_code'] ; $level_name = $excel['initial_name'] ; }



                            $sheet->setCellValue('A' . $rows, strtoupper($level_code));

                            $sheet->setCellValue('B' . $rows, strtoupper($level_name));

                            $sheet->setCellValue('C' . $rows, ($excel['ipamt'] > 0) ? number_format($excel['ipamt'], 2,'.','') : '');

                            $sheet->setCellValue('D' . $rows, ($excel['opamt'] > 0) ? number_format($excel['opamt'], 2,'.','') : '');

                            $sheet->setCellValue('E' . $rows, ($excel['cnamt'] > 0) ? number_format($excel['cnamt'], 2,'.','') : '');

                            $sheet->setCellValue('F' . $rows, ($excel['stamt'] > 0) ? number_format($excel['stamt'], 2,'.','') : '');

                            $sheet->setCellValue('G' . $rows, ($excel['totamt'] > 0) ? number_format($excel['totamt'], 2,'.','') : '');

                            

                            // Apply border to the current row

                            $style = $sheet->getStyle('A' . $rows . ':G' . $rows);

                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



                            $rows++;

                        } break;



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

        }else{

            return view("pages/Billing/bill_register_billwise", compact("data", "displayId"));



        }

    }
    public function bill_register_court_client_matter_initial() {

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches(session()->userId);

    	$data['requested_url'] = $this->session->requested_end_menu_url;

        $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4220' , 'court_help_id' => '4221', 'initial_help_id' => '4191', 'attn_help_id' => '4580'] ;

    

        if($this->request->getMethod() == 'post') {

            $requested_url = base_url($data['requested_url']);

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



            $ason_date       = $_REQUEST['ason_date'] ;

            $branch_code     = $_REQUEST['branch_code'] ;

            $start_date      = $_REQUEST['start_date'] ;      

            if($start_date != '')   { $start_date_ymd = date_conv($start_date); } else { $start_date_ymd = '1901-01-01'; }

            $end_date        = $_REQUEST['end_date'] ;        

            if($end_date   != '')   { $end_date_ymd   = date_conv($end_date);   } else { $end_date_ymd   = date('Y-m-d') ; }

            $court_code      = $_REQUEST['court_code'] ;      

            if(empty($court_code))  { $court_code   = '%' ; }

            $court_name      = $_REQUEST['court_name'] ;

            $client_code     = $_REQUEST['client_code'] ;     

            if(empty($client_code)) { $client_code  = '%' ; }

            $client_name     = $_REQUEST['client_name'] ;     $client_name = str_replace('_|_','&',$client_name) ;   $client_name = str_replace('-|-',"'",$client_name) ;

            $attention_code  = $_REQUEST['attention_code'] ;  

            if(empty($attention_code)) { $attention_code  = '%' ; }

            $attention_name  = get_attention_name($attention_code) ;

            $matter_code     = $_REQUEST['matter_code'] ;     

            if(empty($matter_code)) { $matter_code  = '%' ; }

            $matter_desc     = $_REQUEST['matter_desc'] ;

            $billfor_ind     = $_REQUEST['billfor_ind'] ;

            $initial_code    = $_REQUEST['initial_code'] ;    

            if(empty($initial_code)){ $initial_code = '%' ; }

            $initial_name    = $_REQUEST['initial_name'] ;

            $report_seqn     = $_REQUEST['report_seqn'] ;

            $report_type     = $_REQUEST['report_type'] ;

            $output_type     = $_REQUEST['output_type'] ;

            //$branch_name     = getBranchName($branch_code,$link) ;

            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();

            $branch_name   = $branch_qry["branch_name"] ;



            if ($report_seqn == 'B' && $report_type == 'S') {



                session()->setFlashdata('valid_message', 'Sorry !!!  This facility is not VALID !!');

                return redirect()->to($requested_url);



            } else if ($report_seqn == 'C' && $report_type == 'S' && $output_type == 'Report' ) {



                session()->setFlashdata('valid_message', 'Sorry !!!  This facility is ONLY VALID FOR EXCEL !!');

                return redirect()->to($requested_url);



            }

            //

            if($start_date == '')  {$period_desc = "UPTO ".$end_date ;} else {$period_desc = $start_date.' - '.$end_date ;}



            if($court_code     == '%') { $court_heading      = 'COURT : ALL'     ; } else { $court_heading      = 'COURT : SELECTIVE'     ; }

            if($client_code    == '%') { $client_heading     = 'CLIENT : ALL'    ; } else { $client_heading     = 'CLIENT : SELECTIVE'    ; }

            if($attention_code == '%') { $attention_heading  = 'ATTENTION : ALL' ; } else { $attention_heading  = 'ATTENTION : SELECTIVE' ; }

            if($matter_code    == '%') { $matter_heading     = 'MATTER : ALL'    ; } else { $matter_heading     = 'MATTER : SELECTIVE'    ; }

            if($initial_code   == '%') { $initial_heading   = 'INITIAL : ALL'    ; } else { $initial_heading    = 'INITIAL : SELECTIVE'  ; }

            //

            if($client_code    == '%') { $client_name       = 'ALL'              ; } else { $client_name        = $client_name ; }

            if($attention_code == '%') { $attention_name    = 'ALL'              ; } else { $attention_name     = $attention_name ; }



            $report_sub_desc = '[ '.$court_heading.' / '.$client_heading.' / '.$attention_heading.' / '.$matter_heading.' ]' ;





            if($report_seqn == 'B')      { $order_by_clause = "a.bill_date,a.fin_year,a.bill_no" ; }

            else if($report_seqn == 'C') { $order_by_clause = "b.client_name,a.bill_date,a.fin_year,a.bill_no" ; }

            else if($report_seqn == 'M') { $order_by_clause = "a.matter_code,a.bill_date,a.fin_year,a.bill_no" ; }

            else if($report_seqn == 'I') { $order_by_clause = "d.code_desc,a.bill_date,a.fin_year,a.bill_no" ; }

            else if($report_seqn == 'N') { $order_by_clause = "c.initial_code,d.code_desc,a.bill_date,a.fin_year,a.bill_no" ; }



            //--------  

            if($output_type == 'Report' || $output_type == 'Pdf'){

                $bill_sql = '';

                switch($report_type) {

                    case 'D' :

                        $report_desc = "BILL REGISTER : COURT/CLIENT/INITIAL/MATTER-WISE (DETAILS)" ;

                        $bill_sql    = "select concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.attention_code,a.matter_code,ifnull(a.bill_amount_inpocket, 0) ipamt,ifnull(a.bill_amount_outpocket,0) opamt,ifnull(a.bill_amount_counsel,  0) cnamt,ifnull(a.service_tax_amount,   0) stamt,

                                    (ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt,

                                    (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,

                                    (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,

                                    (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,

                                    ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,

                                    b.client_name,if(c.matter_desc1 != '', concat(c.matter_desc1,' : ',c.matter_desc2),c.matter_desc2) matter_desc,c.court_code,c.initial_code,d.code_desc court_name

                                    from bill_detail a , client_master b, fileinfo_header c, code_master d

                                    where a.branch_code like '$branch_code'

                                    and a.client_code like '$client_code'

                                    and a.attention_code like '$attention_code'

                                    and a.matter_code like '$matter_code'

                                    and ifnull(c.initial_code,'N') like '$initial_code' 

                                    and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                                    and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                                    and a.client_code = b.client_code

                                    and a.matter_code = c.matter_code

                                    and c.court_code like '$court_code'

                                    and c.court_code = d.code_code and d.type_code = '001' 

                                    and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                                    order by ".$order_by_clause ;  

                        break;

                    case 'S':

                        $report_desc = "BILL REGISTER : COURT/CLIENT/INITIAL/MATTER-WISE (SUMMARY)" ;

                        $bill_sql    = "select c.court_code,d.code_desc court_name,sum(ifnull(a.bill_amount_inpocket, 0)) ipamt,sum(ifnull(a.bill_amount_outpocket,0)) opamt,sum(ifnull(a.bill_amount_counsel,  0)) cnamt,sum(ifnull(a.service_tax_amount,   0)) stamt,sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,sum(ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount

                                    from bill_detail a, fileinfo_header c, code_master d

                                    where a.branch_code like '$branch_code'

                                    and a.client_code like '$client_code'

                                    and a.attention_code like '$attention_code'

                                    and a.matter_code like '$matter_code'

                                    and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                                    and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                                    and a.matter_code = c.matter_code

                                    and c.court_code like '$court_code'

                                    and c.court_code = d.code_code and d.type_code = '001' 

                                    and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                                group by c.court_code,d.code_desc 

                                order by d.code_desc " ; 

                        // echo '<pre>';print_r($reports);die;

                        break;

                }



                $reports  = $this->db->query($bill_sql)->getResultArray() ;

                $bill_cnt  = count($reports);

                $date = date('d-m-Y');



            	if(empty($reports)) {

                    session()->setFlashdata('message', 'No Records Found !!');

                    return redirect()->to($requested_url);

                }



                $params = [

                    "branch_name" => $branch_name,

                    "report_desc" => $report_desc,

                    "bill_cnt" => $bill_cnt,

                    "report_sub_desc" => $report_sub_desc,

                    "period_desc" => $period_desc,

                    "client_code" => $client_code,

                    "client_name" => $client_name,

                    "matter_code" => $matter_code,

                    "matter_desc" => $matter_desc,

                    "initial_code" => $initial_code,

                    "initial_name" => $initial_name,

                    "billfor_ind" => $billfor_ind,

                    "report_seqn" => $report_seqn,

                    "report_type" => $report_type,

                    "date" => $date,

                    "ason_date" => $ason_date,

                    "court_code" => $court_code,

                    "court_name" => $court_name,

                    "attention_code" => $attention_code,

                    "attention_name" => $attention_name,

                    "requested_url" => $requested_url,



                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/Billing/bill_register_courtwise", compact("reports", "params", "report_type"));
                    // echo $reportHTML; die;
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/Billing/bill_register_courtwise", compact("reports", "params"));

            }else if ($output_type == 'Excel') {

                if($report_type != 'D') {

                    if($report_seqn == 'C')      { $group_by_clause = "b.client_name"  ; $order_by_clause = "b.client_name" ; }

                    else if($report_seqn == 'M') { $group_by_clause = "a.matter_code"  ; $order_by_clause = "a.matter_code" ; }

                    else if($report_seqn == 'I') { $group_by_clause = "c.court_code" ; $order_by_clause = "d.code_desc" ;}

                    else if($report_seqn == 'N') { $group_by_clause = "c.initial_code" ; $order_by_clause = "c.initial_code" ; }

                }

                $bill_sql = '';

                if ($report_type == 'D') {

                        $bill_sql = "select concat(a.fin_year,'/',a.bill_no) bill_number, a.bill_date, a.client_code, a.attention_code, a.matter_code,

                            ifnull(a.bill_amount_inpocket, 0) ipamt, ifnull(a.bill_amount_outpocket,0) opamt, ifnull(a.bill_amount_counsel,  0) cnamt,ifnull(a.service_tax_amount,   0) stamt,

                            (ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt,

                            (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,

                            (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,

                            (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,

                            ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,

                            b.client_name, c.matter_desc1, c.matter_desc2, c.reference_desc, c.requisition_no, c.date_of_filing, c.court_code, c.initial_code, d.code_desc court_name

                            from bill_detail a , client_master b, fileinfo_header c, code_master d

                            where a.branch_code like '$branch_code'

                            and a.client_code like '$client_code'

                            and a.attention_code like '$attention_code'

                            and a.matter_code like '$matter_code'

                            and ifnull(c.initial_code,'N') like '$initial_code' 

                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                            and a.client_code = b.client_code

                            and a.matter_code = c.matter_code

                            and c.court_code like '$court_code'

                            and c.court_code = d.code_code and d.type_code = '001' 

                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                        order by ".$order_by_clause  ;  



                } if($report_type != 'D' && $report_seqn == 'C') { 

                        

                    $bill_sql = "select a.client_code, sum(ifnull(a.bill_amount_inpocket, 0)) ipamt, sum(ifnull(a.bill_amount_outpocket,0)) opamt, sum(ifnull(a.bill_amount_counsel,  0)) cnamt,

                            sum(ifnull(a.service_tax_amount,   0)) stamt, sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt, sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,

                            sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,

                            sum(ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,

                            sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,

                            b.client_name

                            from bill_detail a , client_master b, fileinfo_header c, code_master d,initial_master e

                            where a.branch_code like '$branch_code'

                            and a.client_code like '$client_code'

                            and a.attention_code like '$attention_code'

                            and a.matter_code like '$matter_code'

                            and ifnull(c.initial_code,'N') like '$initial_code' 

                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                            and a.client_code = b.client_code

                            and a.matter_code = c.matter_code

                            and c.initial_code = e.initial_code

                            and c.court_code like '$court_code'

                            and c.court_code = d.code_code and d.type_code = '001' 

                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                        group by a.client_code order by b.client_name" ;



                } if($report_type != 'D' && $report_seqn == 'I') { 



                   $bill_sql = "select c.court_code, d.code_desc court_name,

                            sum(ifnull(a.bill_amount_inpocket, 0)) ipamt, sum(ifnull(a.bill_amount_outpocket,0)) opamt, sum(ifnull(a.bill_amount_counsel,  0)) cnamt,

                            sum(ifnull(a.service_tax_amount, 0)) stamt, sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,

                            sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,

                            sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,

                            sum(ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,

                            sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount

                            from bill_detail a , client_master b, fileinfo_header c, code_master d,initial_master e

                            where a.branch_code like '$branch_code'

                            and a.client_code like '$client_code'

                            and a.attention_code like '$attention_code'

                            and a.matter_code like '$matter_code'

                            and ifnull(c.initial_code,'N') like '$initial_code' 

                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                            and a.client_code = b.client_code

                            and a.matter_code = c.matter_code

                            and c.initial_code = e.initial_code

                            and c.court_code like '$court_code'

                            and c.court_code = d.code_code and d.type_code = '001' 

                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                            group by c.court_code order by court_name" ; 



                } if($report_type != 'D' && $report_seqn == 'C') { 



                    $bill_sql = "select a.client_code, a.matter_code, a.attention_code, c.court_code, d.code_desc court_name, c.initial_code, e.initial_name, concat(c.matter_desc1,' ',c.matter_desc2) matter_name,

                            sum(ifnull(a.bill_amount_inpocket, 0)) ipamt, sum(ifnull(a.bill_amount_outpocket,0)) opamt, sum(ifnull(a.bill_amount_counsel,  0)) cnamt, sum(ifnull(a.service_tax_amount,   0)) stamt,

                            sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,

                            sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,

                            sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,

                            sum(ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,

                            sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,

                            c.reference_desc, b.client_name

                            from bill_detail a , client_master b, fileinfo_header c, code_master d,initial_master e

                            where a.branch_code like '$branch_code'

                            and a.client_code like '$client_code'

                            and a.attention_code like '$attention_code'

                            and a.matter_code like '$matter_code'

                            and ifnull(c.initial_code,'N') like '$initial_code' 

                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                            and a.client_code = b.client_code

                            and a.matter_code = c.matter_code

                            and c.initial_code = e.initial_code

                            and c.court_code like '$court_code'

                            and c.court_code = d.code_code and d.type_code = '001' 

                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                            group by ".$group_by_clause." order by ".$order_by_clause ; 



                } if($report_type != 'D' && $report_seqn == 'M') { 



                   $bill_sql = "select a.client_code, a.attention_code, a.matter_code, c.court_code, d.code_desc court_name, c.initial_code, e.initial_name, concat(c.matter_desc1,' ',c.matter_desc2) matter_name,

                            sum(ifnull(a.bill_amount_inpocket, 0)) ipamt, sum(ifnull(a.bill_amount_outpocket,0)) opamt, sum(ifnull(a.bill_amount_counsel,  0)) cnamt, sum(ifnull(a.service_tax_amount,   0)) stamt,

                            sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,

                            sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,

                            sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,

                            sum(ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,

                            sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,

                            c.reference_desc, b.client_name

                            from bill_detail a , client_master b, fileinfo_header c, code_master d,initial_master e

                            where a.branch_code like '$branch_code'

                            and a.client_code like '$client_code'

                            and a.attention_code like '$attention_code'

                            and a.matter_code like '$matter_code'

                            and ifnull(c.initial_code,'N') like '$initial_code' 

                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                            and a.client_code = b.client_code

                            and a.matter_code = c.matter_code

                            and c.initial_code = e.initial_code

                            and c.court_code like '$court_code'

                            and c.court_code = d.code_code and d.type_code = '001' 

                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                            group by a.matter_code order by matter_code" ; 

                } if($report_type != 'D' && $report_seqn == 'N') { 

                   $bill_sql = "select c.initial_code, d.initial_name, sum(ifnull(a.bill_amount_inpocket, 0)) ipamt, sum(ifnull(a.bill_amount_outpocket,0)) opamt, sum(ifnull(a.bill_amount_counsel,  0)) cnamt, sum(ifnull(a.service_tax_amount,   0)) stamt,

                            sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,

                            sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,

                            sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,

                            sum(ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,

                            sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount

                            from bill_detail a , client_master b, fileinfo_header c, initial_master d

                            where a.branch_code like '$branch_code'

                            and a.client_code like '$client_code'

                            and a.attention_code like '$attention_code'

                            and a.matter_code like '$matter_code'

                            and a.initial_code like '$initial_code'

                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                            and a.client_code = b.client_code

                            and a.matter_code = c.matter_code

                            and c.initial_code = d.initial_code 

                            and a.initial_code = c.initial_code

                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                            group by c.initial_code, d.initial_name 

                            order by d.initial_name "  ; 

                }             



                $excels  = $this->db->query($bill_sql)->getResultArray() ;

                // echo '<pre>'; print_r($excels);die;

                $bill_cnt  = count($excels);



            	if(empty($excels)) {

                    session()->setFlashdata('message', 'No Records Found !!');

                    return redirect()->to($requested_url);

                }

               

                $fileName = 'BILL_REGISTER_COURTWISE-'.date('d-m-Y').'.xlsx';  

                $spreadsheet = new Spreadsheet();

                $sheet = $spreadsheet->getActiveSheet();



                switch($report_type) {

                    case 'D' :

                        // Define the headings

                        $headings = ['Court', 'Bill No', 'Bill Date', 'Initial', 'Client', 'Attention', 'Matter', 'Case', 'Description', 'Reference', 'Inpocket', 'Outpocket', 'Counsel', 'Service Tax', 'Total', 'Realised', 'Deficit', 'O/s'];



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

                            $attention_code = get_attention_name($excel['attention_code']);

                            $sheet->setCellValue('A' . $rows, strtoupper($excel['court_name']));

                            $sheet->setCellValue('B' . $rows, $excel['bill_number']);

                            $sheet->setCellValue('C' . $rows, date_conv($excel['bill_date']));

                            $sheet->setCellValue('D' . $rows, strtoupper($excel['initial_code']));

                            $sheet->setCellValue('E' . $rows, strtoupper($excel['client_name']));

                            $sheet->setCellValue('F' . $rows, strtoupper($attention_code));

                            $sheet->setCellValue('G' . $rows, strtoupper($excel['matter_code']));

                            $sheet->setCellValue('H' . $rows, strtoupper($excel['matter_desc1']));

                            $sheet->setCellValue('I' . $rows, strtoupper($excel['matter_desc2']));

                            $sheet->setCellValue('J' . $rows, "'".strtoupper(stripslashes($excel['reference_desc'])));

                            $sheet->setCellValue('K' . $rows, ($excel['ipamt'] > 0) ? number_format($excel['ipamt'], 2,'.','') : '');

                            $sheet->setCellValue('L' . $rows, ($excel['opamt'] > 0) ? number_format($excel['opamt'], 2,'.','') : '');

                            $sheet->setCellValue('M' . $rows, ($excel['cnamt'] > 0) ? number_format($excel['cnamt'], 2,'.','') : '');

                            $sheet->setCellValue('N' . $rows, ($excel['stamt'] > 0) ? number_format($excel['stamt'], 2,'.','') : '');

                            $sheet->setCellValue('O' . $rows, ($excel['totamt'] > 0) ? number_format($excel['totamt'], 2,'.','') : '');

                            $sheet->setCellValue('P' . $rows, ($excel['realised_amount'] > 0) ? number_format($excel['realised_amount'], 2,'.','') : '');

                            $sheet->setCellValue('Q' . $rows, ($excel['deficit_amount'] > 0) ? number_format($excel['deficit_amount'], 2,'.','') : '');

                            $sheet->setCellValue('R' . $rows, ($excel['balance_amount'] > 0) ? number_format($excel['balance_amount'], 2,'.','') : '');

                            

                            // Apply border to the current row

                            $style = $sheet->getStyle('A' . $rows . ':R' . $rows);

                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



                            $rows++;

                        } break;

                    case 'S' :

                        // Define the headings

                        $headings = ['Name', 'Inpocket', 'Outpocket', 'Counsel', 'Service Tax', 'Total', 'Realised', 'Deficit', 'O/s'];



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

                            if($report_seqn == 'C') {$level_code = $excel['client_code']   ; $level_name = $excel['client_name']  ; }

                            if($report_seqn == 'M') {$level_code = $excel['matter_code']   ; $level_name = $excel['matter_name']  ; }

                            if($report_seqn == 'I') {$level_code = $excel['court_code']    ; $level_name = $excel['court_name']  ; }

                            if($report_seqn == 'N') {$level_code = $excel['initial_code']  ; $level_name = $excel['initial_name']  ; }



                            $sheet->setCellValue('A' . $rows, strtoupper($level_name));

                            $sheet->setCellValue('B' . $rows, ($excel['ipamt'] > 0) ? number_format($excel['ipamt'], 2,'.','') : '');

                            $sheet->setCellValue('C' . $rows, ($excel['opamt'] > 0) ? number_format($excel['opamt'], 2,'.','') : '');

                            $sheet->setCellValue('D' . $rows, ($excel['cnamt'] > 0) ? number_format($excel['cnamt'], 2,'.','') : '');

                            $sheet->setCellValue('E' . $rows, ($excel['stamt'] > 0) ? number_format($excel['stamt'], 2,'.','') : '');

                            $sheet->setCellValue('F' . $rows, ($excel['totamt'] > 0) ? number_format($excel['totamt'], 2,'.','') : '');

                            $sheet->setCellValue('G' . $rows, ($excel['realised_amount'] > 0) ? number_format($excel['realised_amount'], 2,'.','') : '');

                            $sheet->setCellValue('H' . $rows, ($excel['deficit_amount'] > 0) ? number_format($excel['deficit_amount'], 2,'.','') : '');

                            $sheet->setCellValue('I' . $rows, ($excel['balance_amount'] > 0) ? number_format($excel['balance_amount'], 2,'.','') : '');

                            

                            // Apply border to the current row

                            $style = $sheet->getStyle('A' . $rows . ':I' . $rows);

                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



                            $rows++;

                        } break;

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

        }else{

            return view("pages/Billing/bill_register_courtwise", compact("data", "displayId"));



        }



    }
    public function bill_realisation() {               //doc-wise report-seq with summary report type not combine together//

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches(session()->userId);

    	$data['requested_url'] = $this->session->requested_end_menu_url;

        $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4220' , 'court_help_id' => '4221', 'initial_help_id' => '4191'] ;



        if($this->request->getMethod() == 'post') {

            $requested_url = base_url($data['requested_url']);

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



            $branch_code            = $_REQUEST['branch_code'] ;

            $realisation_start_date = $_REQUEST['realisation_start_date'] ;   

            if($realisation_start_date != '')    { $realisation_start_date_ymd = date_conv($realisation_start_date); } else { $realisation_start_date_ymd = '1901-01-01'; }

            $realisation_end_date   = $_REQUEST['realisation_end_date'] ;     

            if($realisation_end_date   != '')    { $realisation_end_date_ymd   = date_conv($realisation_end_date);   } else { $realisation_end_date_ymd   = date('Y-m-d') ; }

            $billing_start_date     = $_REQUEST['billing_start_date'] ;       

            if($billing_start_date     != '')    { $billing_start_date_ymd     = date_conv($billing_start_date);     } else { $billing_start_date_ymd     = '1901-01-01'; }

            $billing_end_date       = $_REQUEST['billing_end_date'] ;         

            if($billing_end_date       != '')    { $billing_end_date_ymd       = date_conv($billing_end_date);       } else { $billing_end_date_ymd       = date('Y-m-d') ; }

            $client_code            = $_REQUEST['client_code'] ;              

            if(empty($client_code))  { $client_code  = '%' ; }

            $client_name     = $_REQUEST['client_name'] ; 

            $client_name            = str_replace('_|_','&',$client_name) ;

            $client_name            = str_replace('-|-',"'",$client_name) ;  

            $matter_code            = $_REQUEST['matter_code'] ;              

            if(empty($matter_code))  { $matter_code  = '%' ; }

            $matter_desc            = $_REQUEST['matter_desc'] ;

            $initial_code           = $_REQUEST['initial_code'] ;             

            if(empty($initial_code)) { $initial_code = '%' ; }

            $initial_name           = $_REQUEST['initial_name'] ;

            $court_code             = $_REQUEST['court_code'] ;               

            if(empty($court_code))   { $court_code   = '%' ; }

            $court_name             = $_REQUEST['court_name'] ;

            $court_code             = $_REQUEST['court_code'] ;               

            if(empty($court_code))   { $court_code   = '%' ; }

            $court_name             = $_REQUEST['court_name'] ;

            $info_by                = $_REQUEST['info_by'] ;

            $report_seqn            = $_REQUEST['report_seqn'] ;

            $report_type            = $_REQUEST['report_type'] ;

            $output_type            = $_REQUEST['output_type'] ;

            //$branch_name            = getBranchName($branch_code,$link) ;

            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0];

            $branch_name   = $branch_qry["branch_name"] ;

            $old_matter_desc        = 'ALL MATTERS UPTO 30/09/2003' ;



            if ($info_by == 'B' && $report_seqn == 'B' && $report_type == 'S') 

            {

                session()->setFlashdata('valid_message', 'Sorry !!!  This facility is not VALID !!');

                return redirect()->to($requested_url);

            }



            if($client_code   == '%') { $client_heading  = 'CLIENT  : ALL'  ; } else { $client_heading  = 'CLIENT  : '. $client_name  ; }

            if($matter_code   == '%') { $matter_heading  = 'MATTER  : ALL'  ; } else { $matter_heading  = 'MATTER  : SELECTIVE'  ; }

            if($initial_code  == '%') { $initial_heading = 'INITIAL : ALL'  ; } else { $initial_heading = 'INITIAL  : '. $initial_name  ; }

            if($court_code    == '%') { $court_heading   = 'COURT   : ALL'  ; } else { $court_heading = 'COURT : '. $court_name  ; }



            $report_sub_desc = '[ '.$client_heading.' / '.$matter_heading.' / '.$initial_heading.' / '.$court_heading.' ]' ;

            //

            if($realisation_start_date    == '' ) {$period_desc = "UPTO ".$realisation_end_date ;} else {$period_desc = $realisation_start_date.' - '.$realisation_end_date ;}

            

                if($output_type == 'Report' || $output_type == 'Pdf'){

                    $bill_sql = '';

                    switch($report_type) {

                        case 'D' :

                    

                            if($info_by == 'B'){
                                
                                if($report_seqn == 'B')      { $group_by_clause = "2,1" ;    $order_by_clause = "2,1" ; }
                                else if($report_seqn == 'C') { $group_by_clause = "6,2,1"  ; $order_by_clause = "6,2,1" ; }
                                else if($report_seqn == 'M') { $group_by_clause = "7,2,1"  ; $order_by_clause = "7,2,1" ; }
                                else if($report_seqn == 'I') { session()->setFlashdata('message', 'No Records Found !!'); return redirect()->to($this->requested_url()); }
                                else if($report_seqn == 'T') { $group_by_clause = "10,2,1" ; $order_by_clause = "10,2,1" ; }
                                
                                $report_desc = "BILL REALISATION (DETAIL)" ;

                                $bill_sql    = "select x.client_code,x.client_name,x.matter_code,x.matter_desc,x.initial_code,x.initial_name,x.court_code,x.court_name,x.realamt,x.defcamt,x.bill_number,x.bill_date

                                        from (select concat(a.ref_bill_year,'/',a.ref_bill_no) bill_number,b.bill_date,b.client_code,b.matter_code,b.initial_code,c.client_name,e.initial_name,

                                        sum(ifnull(a.realise_amount_inpocket, 0)+ifnull(a.realise_amount_outpocket,0)+ifnull(a.realise_amount_counsel,0)+ifnull(a.realise_amount_service_tax,0)) realamt,

                                        sum(ifnull(a.deficit_amount_inpocket, 0)+ifnull(a.deficit_amount_outpocket,0)+ifnull(a.deficit_amount_counsel,0)+ifnull(a.deficit_amount_service_tax,0)) defcamt,

                                        if(substring(b.matter_code,1,1)='0','$old_matter_desc',if(i.matter_desc1 != '', concat(i.matter_desc1,' : ',i.matter_desc2),i.matter_desc2)) matter_desc,

                                        i.court_code, h.code_desc court_name

                                        from bill_realisation_detail a, bill_detail b, client_master c, initial_master e, bill_realisation_header f, ledger_trans_hdr g,  fileinfo_header i, code_master h

                                        where a.branch_code like '$branch_code'

                                        and a.ref_realisation_serial_no = f.serial_no 

                                        and f.ref_ledger_serial_no = g.serial_no

                                        and g.doc_date between '$realisation_start_date_ymd' and '$realisation_end_date_ymd'

                                        and a.ref_bill_serial_no = b.serial_no

                                        and b.bill_date between '$billing_start_date_ymd' and '$billing_end_date_ymd'

                                        and b.client_code like '$client_code'

                                        and b.matter_code like '$matter_code'

                                        and b.initial_code like '$initial_code'

                                        and b.client_code = c.client_code

                                        and b.initial_code = e.initial_code 

                                        and (ifnull(b.cancel_ind,'N') = 'N' or b.cancel_ind = '')

                                        and b.matter_code = i.matter_code

                                        and i.court_code like '$court_code'

                                        and i.court_code = h.code_code  

                                        and h.type_code = '001'

                                        group by ".$group_by_clause."

                                        ) x  

                                        order by ".$order_by_clause ; 

                            }else if($info_by == 'C'){

                                if($report_seqn == 'B')      { $group_by_clause = "11,12,13,2,1" ;    $order_by_clause = "12,11,13,2,1" ; }
                                else if($report_seqn == 'C') { $group_by_clause = "6,11,12,13,2,1"  ; $order_by_clause = "6,12,11,13,2,1" ; }
                                else if($report_seqn == 'M') { $group_by_clause = "7,11,12,13,2,1"  ; $order_by_clause = "7,12,11,13,2,1" ; }
                                else if($report_seqn == 'I') { $group_by_clause = "8,11,12,13,2,1"  ; $order_by_clause = "8,12,11,13,2,1" ; }
                                else if($report_seqn == 'T') { $group_by_clause = "15,11,12,13,2,1" ; $order_by_clause = "15,12,11,13,2,1" ; }

                                $report_desc = "BILL REALISATION (DETAIL)" ;

                                $bill_sql    = "select x.bill_number,x.bill_date,x.client_code,x.matter_code,x.initial_code,x.client_name,x.matter_desc,x.initial_name,sum(x.realamt) realamt,sum(x.defcamt) defcamt,x.instrument_no,x.instrument_dt,x.bank_name,x.court_code,x.court_name,x.doc_date

                                    from(select concat(a.ref_bill_year,'/',a.ref_bill_no) bill_number,b.bill_date,b.client_code,b.matter_code,b.initial_code,c.client_name,e.initial_name,

                                    ifnull(a.realise_amount_inpocket, 0)+ifnull(a.realise_amount_outpocket,0)+ifnull(a.realise_amount_counsel,0) realamt,

                                    ifnull(a.deficit_amount_inpocket, 0)+ifnull(a.deficit_amount_outpocket,0)+ifnull(a.deficit_amount_counsel,0) defcamt,g.instrument_no,g.instrument_dt,g.bank_name,g.doc_date,

                                    if(substring(d.matter_code,1,1)='0','$old_matter_desc',if(d.matter_desc1 != '', concat(d.matter_desc1,' : ',d.matter_desc2),d.matter_desc2)) matter_desc,d.court_code, h.code_desc court_name

                                    from bill_realisation_detail a, bill_detail b, client_master c, initial_master e, bill_realisation_header f, ledger_trans_hdr g, fileinfo_header d, code_master h

                                    where a.branch_code like '$branch_code'

                                    and a.ref_realisation_serial_no = f.serial_no 

                                    and f.ref_ledger_serial_no = g.serial_no

                                    and g.doc_date between '$realisation_start_date_ymd' and '$realisation_end_date_ymd'

                                    and a.ref_bill_year = b.fin_year

                                    and a.ref_bill_no = b.bill_no

                                    and b.bill_date between '$billing_start_date_ymd' and '$billing_end_date_ymd'

                                    and b.client_code like '$client_code'

                                    and b.matter_code like '$matter_code'

                                    and b.initial_code like '$initial_code'

                                    and b.client_code = c.client_code

                                    and b.initial_code = e.initial_code 

                                    and (ifnull(b.cancel_ind,'N') = 'N' or b.cancel_ind = '')

                                    and b.matter_code = d.matter_code

                                    and d.court_code like '$court_code'

                                    and d.court_code = h.code_code  

                                    and h.type_code = '001'

                                            ) x 

                                    group by ".$group_by_clause." order by ".$order_by_clause ; 

                            }

                                break;

                        case 'S' :

                            

                            if($info_by == 'B'){

                                if($report_seqn == 'C')      { $group_by_clause = "1"    ; $order_by_clause = "2" ; }

                                else if($report_seqn == 'M') { $group_by_clause = "3"    ; $order_by_clause = "3" ; }

                                else if($report_seqn == 'I') { $group_by_clause = "5"    ; $order_by_clause = "6" ; }

                                else if($report_seqn == 'T') { $group_by_clause = "7"    ; $order_by_clause = "8" ; }

                                

                                $report_desc = "BILL REALISATION (SUMMARY)" ;

                                $bill_sql    = "select x.client_code,x.client_name,x.matter_code,x.matter_desc,x.initial_code,x.initial_name,x.court_code,x.court_name,x.realamt,x.defcamt

                                    from(select concat(a.ref_bill_year,'/',a.ref_bill_no) bill_number,b.bill_date,b.client_code,b.matter_code,b.initial_code,c.client_name,e.initial_name,

                                    sum(ifnull(a.realise_amount_inpocket, 0)+ifnull(a.realise_amount_outpocket,0)+ifnull(a.realise_amount_counsel,0)+ifnull(a.realise_amount_service_tax,0)) realamt,

                                    sum(ifnull(a.deficit_amount_inpocket, 0)+ifnull(a.deficit_amount_outpocket,0)+ifnull(a.deficit_amount_counsel,0)+ifnull(a.deficit_amount_service_tax,0)) defcamt,

                                    if(substring(b.matter_code,1,1)='0','$old_matter_desc',if(i.matter_desc1 != '', concat(i.matter_desc1,' : ',i.matter_desc2),i.matter_desc2)) matter_desc,i.court_code,h.code_desc court_name

                                    from bill_realisation_detail a, bill_detail b, client_master c, initial_master e, bill_realisation_header f, ledger_trans_hdr g,  fileinfo_header i, code_master h

                                    where a.branch_code like '$branch_code'

                                    and a.ref_realisation_serial_no = f.serial_no 

                                    and f.ref_ledger_serial_no = g.serial_no

                                    and g.doc_date between '$realisation_start_date_ymd' and '$realisation_end_date_ymd'

                                    and a.ref_bill_serial_no = b.serial_no

                                    and b.bill_date between '$billing_start_date_ymd' and '$billing_end_date_ymd'

                                    and b.client_code like '$client_code'

                                    and b.matter_code like '$matter_code'

                                    and b.initial_code like '$initial_code'

                                    and b.client_code = c.client_code

                                    and b.initial_code = e.initial_code 

                                    and (ifnull(b.cancel_ind,'N') = 'N' or b.cancel_ind = '')

                                    and b.matter_code = i.matter_code

                                    and i.court_code like '$court_code'

                                    and i.court_code = h.code_code  

                                    and h.type_code = '001'

                                    group by ".$group_by_clause."

                                    ) x  

                                    order by ".$order_by_clause ; 

                                    

                            }else if($info_by == 'C'){



                                $report_desc = "BILL REALISATION (SUMMARY)" ;



                                if($report_seqn == 'B')      { $group_by_clause = "11,12,13,14"    ; $order_by_clause = "12,11,13,14" ; }

                                else if($report_seqn == 'C') { $group_by_clause = "6,11,12,13,14"  ; $order_by_clause = "6,12,11,13,14" ; }

                                else if($report_seqn == 'M') { $group_by_clause = "7,11,12,13,14"  ; $order_by_clause = "7,12,11,13,14" ; }

                                else if($report_seqn == 'I') { $group_by_clause = "8,11,12,13,14"  ; $order_by_clause = "8,12,11,13,14" ; }

                                else if($report_seqn == 'T') { $group_by_clause = "16,11,12,13,14" ; $order_by_clause = "16,12,11,13,14" ; }



                                $bill_sql    = "select x.bill_number,x.bill_date,x.client_code,x.matter_code,x.initial_code,x.client_name,x.matter_desc,x.initial_name,x.realamt,x.defcamt,x.instrument_no,x.instrument_dt,x.bank_name,x.received_from,x.court_code,x.court_name

                                    from(select concat(a.ref_bill_year,'/',a.ref_bill_no) bill_number,b.bill_date,b.client_code,b.matter_code,b.initial_code,c.client_name,e.initial_name,

                                    ifnull(b.bill_amount_inpocket,0)+ifnull(b.bill_amount_outpocket,0)+ifnull(b.bill_amount_counsel,0)+ifnull(b.service_tax_amount,0) billamount,

                                    sum(ifnull(a.realise_amount_inpocket,0)+ifnull(a.realise_amount_outpocket,0)+ifnull(a.realise_amount_counsel,0)+ifnull(a.realise_amount_service_tax,0)) realamt,

                                    sum(ifnull(a.deficit_amount_inpocket,0)+ifnull(a.deficit_amount_outpocket,0)+ifnull(a.deficit_amount_counsel,0)+ifnull(a.deficit_amount_service_tax,0)) defcamt,g.instrument_no,g.instrument_dt,g.bank_name,g.received_from,

                                    if(substring(d.matter_code,1,1)='0','$old_matter_desc',if(d.matter_desc1 != '', concat(d.matter_desc1,' : ',d.matter_desc2),d.matter_desc2)) matter_desc,d.court_code, h.code_desc court_name

                                    from bill_realisation_detail a, bill_detail b, client_master c, initial_master e, bill_realisation_header f, ledger_trans_hdr g, fileinfo_header d, code_master h

                                    where a.branch_code like '$branch_code'

                                    and a.ref_realisation_serial_no = f.serial_no 

                                    and f.ref_ledger_serial_no = g.serial_no

                                    and g.doc_date between '$realisation_start_date_ymd' and '$realisation_end_date_ymd'

                                    and a.ref_bill_serial_no = b.serial_no

                                    and b.bill_date between '$billing_start_date_ymd' and '$billing_end_date_ymd'

                                    and b.client_code like '$client_code'

                                    and b.matter_code like '$matter_code'

                                    and b.initial_code like '$initial_code'

                                    and b.client_code = c.client_code

                                    and b.initial_code = e.initial_code 

                                    and (ifnull(b.cancel_ind,'N') = 'N' or b.cancel_ind = '')

                                    and b.matter_code = d.matter_code

                                    and d.court_code like '$court_code'

                                    and d.court_code = h.code_code  

                                    and h.type_code = '001'

                                    group by ".$group_by_clause."

                                    ) x  

                                    order by ".$order_by_clause ; 

                            }

                                break;

                    }

                    $reports  = $this->db->query($bill_sql)->getResultArray() ;

                    // echo '<pre>';print_r($reports);die;

                    $bill_cnt  = count($reports);

                    $date = date('d-m-Y');



                	if(empty($reports)) {

                    	session()->setFlashdata('message', 'No Records Found !!');

                    	return redirect()->to($requested_url);

                	}



                    $params = [

                        "branch_name" => $branch_name,

                        "report_desc" => $report_desc,

                        "bill_cnt" => $bill_cnt,

                        "report_sub_desc" => $report_sub_desc,

                        "period_desc" => $period_desc,

                        "client_code" => $client_code,

                        "client_name" => $client_name,

                        "matter_code" => $matter_code,

                        "matter_desc" => $matter_desc,

                        "initial_code" => $initial_code,

                        "initial_name" => $initial_name,

                        "info_by" => $info_by,

                        "report_seqn" => $report_seqn,

                        "report_type" => $report_type,

                        "date" => $date,

                        "court_code" => $court_code,

                        "court_name" => $court_name,

                        "requested_url" => $requested_url,



                    ];

                        if ($output_type == 'Pdf') {
                        $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                        $reportHTML = view("pages/Billing/bill_realisation", compact("reports", "params", "report_type"));
                        // echo $reportHTML; die;
                        $dompdf->loadHtml($reportHTML);
                        $dompdf->setPaper('A4', 'landscape'); // portrait
                        $dompdf->render();
                        $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                    } else return view("pages/Billing/bill_realisation", compact("reports", "params"));

                } else if($output_type == 'Excel') {

                

                    $bill_sql = '';

                    switch($info_by) {

                        case 'B' :

                    

                            if($report_type == 'D'){



                                if($report_seqn == 'B')      { $group_by_clause = "2,1" ;    $order_by_clause = "2,1" ; }

                                else if($report_seqn == 'C') { $group_by_clause = "6,2,1"  ; $order_by_clause = "6,2,1" ; }

                                else if($report_seqn == 'M') { $group_by_clause = "7,2,1"  ; $order_by_clause = "7,2,1" ; }

                                else if($report_seqn == 'I') { $group_by_clause = "8,2,1"  ; $order_by_clause = "8,2,1" ; }

                                else if($report_seqn == 'T') { $group_by_clause = "10,2,1" ; $order_by_clause = "10,2,1" ; }



                                $bill_sql = "select x.bill_number ,x.bill_date ,x.client_code ,x.matter_code ,x.initial_code ,x.client_name ,x.matter_desc ,x.initial_name ,x.court_code ,x.court_name

                                        ,sum(x.realamt) realamt

                                        ,sum(x.defcamt) defcamt

                                        from (select concat(a.ref_bill_year,'/',a.ref_bill_no) bill_number, b.bill_date, b.client_code,b.matter_code,b.initial_code,c.client_name,e.initial_name,

                                            ifnull(a.realise_amount_inpocket, 0)+ifnull(a.realise_amount_outpocket,0)+ifnull(a.realise_amount_counsel,0)+ifnull(a.realise_amount_service_tax,0) realamt,

                                            ifnull(a.deficit_amount_inpocket, 0)+ifnull(a.deficit_amount_outpocket,0)+ifnull(a.deficit_amount_counsel,0)+ifnull(a.deficit_amount_service_tax,0) defcamt,

                                            if(substring(d.matter_code,1,1)='0','$old_matter_desc',if(d.matter_desc1 != '', concat(d.matter_desc1,' : ',d.matter_desc2),d.matter_desc2)) matter_desc,

                                            d.court_code, h.code_desc court_name

                                            from bill_realisation_detail a, bill_detail b, client_master c, initial_master e, bill_realisation_header f, ledger_trans_hdr g, fileinfo_header d, code_master h

                                            where a.branch_code like '$branch_code'

                                            and a.ref_realisation_serial_no = f.serial_no 

                                            and f.ref_ledger_serial_no = g.serial_no

                                            and g.doc_date between '$realisation_start_date_ymd' and '$realisation_end_date_ymd'

                                            and a.ref_bill_year = b.fin_year

                                            and a.ref_bill_no = b.bill_no     

                                            and b.bill_date between '$billing_start_date_ymd' and '$billing_end_date_ymd'

                                            and b.client_code like '$client_code'

                                            and b.matter_code like '$matter_code'

                                            and b.initial_code like '$initial_code'

                                            and b.client_code = c.client_code

                                            and b.initial_code = e.initial_code 

                                            and (ifnull(b.cancel_ind,'N') = 'N' or b.cancel_ind = '')

                                            and b.matter_code = d.matter_code

                                            and d.court_code like '$court_code'

                                            and d.court_code = h.code_code  

                                            and h.type_code = '001'

                                            ) x  

                                        group by ".$group_by_clause." order by ".$order_by_clause  ; 

                            }else if($report_type == 'S'){



                                if($report_seqn == 'C')      { $group_by_clause = "2"    ; $order_by_clause = "1" ; }

                                else if($report_seqn == 'M') { $group_by_clause = "3"    ; $order_by_clause = "3" ; }

                                else if($report_seqn == 'I') { $group_by_clause = "5"    ; $order_by_clause = "6" ; }

                                else if($report_seqn == 'T') { $group_by_clause = "7"    ; $order_by_clause = "8" ; }



                                $bill_sql = "select x.client_name,x.client_code,x.matter_code,x.matter_desc,x.initial_code,x.initial_name,x.court_code,x.court_name,x.realamt,x.defcamt

                                    from (select concat(a.ref_bill_year,'/',a.ref_bill_no) bill_number,b.client_code,b.bill_date,b.matter_code,								b.initial_code,c.client_name,e.initial_name,

                                        sum(ifnull(a.realise_amount_inpocket, 0)+ifnull(a.realise_amount_outpocket,0)+ifnull(a.realise_amount_counsel,0)+ifnull(a.realise_amount_service_tax,0)) realamt,

                                        sum(ifnull(a.deficit_amount_inpocket, 0)+ifnull(a.deficit_amount_outpocket,0)+ifnull(a.deficit_amount_counsel,0)+ifnull(a.deficit_amount_service_tax,0)) defcamt,

                                        if(substring(b.matter_code,1,1)='0','$old_matter_desc',if(i.matter_desc1 != '', concat(i.matter_desc1,' : ',i.matter_desc2),i.matter_desc2)) matter_desc,

                                        i.court_code,h.code_desc court_name

                                        from bill_realisation_detail a, bill_detail b, client_master c, initial_master e, bill_realisation_header f, ledger_trans_hdr g,  fileinfo_header i, code_master h

                                        where a.branch_code like '$branch_code'

                                        and a.ref_realisation_serial_no = f.serial_no 

                                        and f.ref_ledger_serial_no = g.serial_no

                                        and g.doc_date between '$realisation_start_date_ymd' and '$realisation_end_date_ymd'

                                        and a.ref_bill_serial_no = b.serial_no

                                        and b.bill_date between '$billing_start_date_ymd' and '$billing_end_date_ymd'

                                        and b.client_code like '$client_code'

                                        and b.matter_code like '$matter_code'

                                        and b.initial_code like '$initial_code'

                                        and b.client_code = c.client_code

                                        and b.initial_code = e.initial_code 

                                        and (ifnull(b.cancel_ind,'N') = 'N' or b.cancel_ind = '')

                                        and b.matter_code = i.matter_code

                                        and i.court_code like '$court_code'

                                        and i.court_code = h.code_code  

                                        and h.type_code = '001'

                                        group by ".$group_by_clause."

                                        ) x  

                                    order by ".$order_by_clause  ; 

                            }

                        break;

                        case 'C' :

                            

                            if($report_type == 'D'){



                                if($report_seqn == 'B')      { $group_by_clause = "11,12,13,2,1" ;    $order_by_clause = "12,11,13,2,1" ; }

                                else if($report_seqn == 'C') { $group_by_clause = "6,11,12,13,2,1"  ; $order_by_clause = "6,12,11,13,2,1" ; }

                                else if($report_seqn == 'M') { $group_by_clause = "7,11,12,13,2,1"  ; $order_by_clause = "7,12,11,13,2,1" ; }

                                else if($report_seqn == 'I') { $group_by_clause = "8,11,12,13,2,1"  ; $order_by_clause = "8,12,11,13,2,1" ; }

                                else if($report_seqn == 'T') { $group_by_clause = "15,11,12,13,2,1" ; $order_by_clause = "15,12,11,13,2,1" ; }



                                $bill_sql = "select x.bill_number,x.bill_date,x.client_code,x.matter_code,x.initial_code,x.client_name,x.matter_desc,x.initial_name

                                        ,sum(x.realamt) realamt,sum(x.defcamt) defcamt,x.billamount,x.instrument_no,x.instrument_dt,x.bank_name,x.court_code,x.court_name,x.received_from,x.doc_date

                                        ,x.realise_amount_inpocket,x.realise_amount_outpocket,x.realise_amount_counsel,x.realise_amount_service_tax

                                        from (select concat(a.ref_bill_year,'/',a.ref_bill_no) bill_number, b.bill_date,b.client_code,b.matter_code,b.initial_code,

                                            c.client_name,e.initial_name,a.realise_amount_inpocket,a.realise_amount_outpocket,a.realise_amount_counsel,a.realise_amount_service_tax,

                                            ifnull(b.bill_amount_inpocket,0)+ifnull(b.bill_amount_outpocket,0)+ifnull(b.bill_amount_counsel,0)+ifnull(b.service_tax_amount,0) billamount,

                                            ifnull(a.realise_amount_inpocket, 0)+ifnull(a.realise_amount_outpocket,0)+ifnull(a.realise_amount_counsel,0)+ifnull(a.realise_amount_service_tax,0) realamt,

                                            ifnull(a.deficit_amount_inpocket, 0)+ifnull(a.deficit_amount_outpocket,0)+ifnull(a.deficit_amount_counsel,0)+ifnull(a.deficit_amount_service_tax,0) defcamt,

                                            ifnull(g.instrument_no,'') instrument_no,

                                            ifnull(g.instrument_dt,'') instrument_dt,

                                            ifnull(g.bank_name,'') bank_name,

                                            ifnull(g.received_from,'') received_from,

                                            ifnull(g.doc_date,'') doc_date,

                                            if(substring(d.matter_code,1,1)='0','$old_matter_desc',if(d.matter_desc1 != '', concat(d.matter_desc1,' : ',d.matter_desc2),d.matter_desc2)) matter_desc,

                                            d.court_code, h.code_desc court_name

                                            from bill_realisation_detail a, bill_detail b, client_master c, initial_master e, bill_realisation_header f, ledger_trans_hdr g, fileinfo_header d, code_master h

                                            where a.branch_code like '$branch_code'

                                            and a.ref_realisation_serial_no = f.serial_no 

                                            and f.ref_ledger_serial_no = g.serial_no

                                            and g.doc_date between '$realisation_start_date_ymd' and '$realisation_end_date_ymd'

                                            and a.ref_bill_year = b.fin_year

                                            and a.ref_bill_no = b.bill_no     

                                            and b.bill_date between '$billing_start_date_ymd' and '$billing_end_date_ymd'

                                            and b.client_code like '$client_code'

                                            and b.matter_code like '$matter_code'

                                            and b.initial_code like '$initial_code'

                                            and b.client_code = c.client_code

                                            and b.initial_code = e.initial_code 

                                            and (ifnull(b.cancel_ind,'N') = 'N' or b.cancel_ind = '')

                                            and b.matter_code = d.matter_code

                                            and d.court_code like '$court_code'

                                            and d.court_code = h.code_code  

                                            and h.type_code = '001'

                                            ) x 

                                        group by ".$group_by_clause." order by ".$order_by_clause ; 

                                    

                            }else if($report_type == 'S'){



                                if($report_seqn == 'B')      { $group_by_clause = "11,12,13,14"    ; $order_by_clause = "12,11,13,14" ; }

                                else if($report_seqn == 'C') { $group_by_clause = "6,11,12,13,14"  ; $order_by_clause = "6,12,11,13,14" ; }

                                else if($report_seqn == 'M') { $group_by_clause = "7,11,12,13,14"  ; $order_by_clause = "7,12,11,13,14" ; }

                                else if($report_seqn == 'I') { $group_by_clause = "8,11,12,13,14"  ; $order_by_clause = "8,12,11,13,14" ; }

                                else if($report_seqn == 'T') { $group_by_clause = "16,11,12,13,14" ; $order_by_clause = "16,12,11,13,14" ; }



                                $bill_sql = "select x.bill_number,x.bill_date,x.client_code,x.matter_code,x.initial_code,x.client_name,x.matter_desc,x.initial_name,x.realamt

                                        ,x.defcamt,x.instrument_no,x.instrument_dt,x.bank_name,x.received_from,x.court_code,x.court_name

                                        from (select concat(a.ref_bill_year,'/',a.ref_bill_no) bill_number,b.bill_date,b.client_code,b.matter_code,b.initial_code,c.client_name,e.initial_name,

                                            ifnull(b.bill_amount_inpocket,0)+ifnull(b.bill_amount_outpocket,0)+ifnull(b.bill_amount_counsel,0)+ifnull(b.service_tax_amount,0) billamount,

                                            sum(ifnull(a.realise_amount_inpocket,0)+ifnull(a.realise_amount_outpocket,0)+ifnull(a.realise_amount_counsel,0)+ifnull(a.realise_amount_service_tax,0)) realamt,

                                            sum(ifnull(a.deficit_amount_inpocket,0)+ifnull(a.deficit_amount_outpocket,0)+ifnull(a.deficit_amount_counsel,0)+ifnull(a.deficit_amount_service_tax,0)) defcamt,

                                            g.instrument_no,g.instrument_dt,g.bank_name,g.received_from,

                                            if(substring(d.matter_code,1,1)='0','$old_matter_desc',if(d.matter_desc1 != '', concat(d.matter_desc1,' : ',d.matter_desc2),d.matter_desc2)) matter_desc,

                                            d.court_code, h.code_desc court_name

                                            from bill_realisation_detail a, bill_detail b, client_master c, initial_master e, bill_realisation_header f, ledger_trans_hdr g, fileinfo_header d, code_master h

                                            where a.branch_code like '$branch_code'

                                            and a.ref_realisation_serial_no = f.serial_no 

                                            and f.ref_ledger_serial_no = g.serial_no

                                            and g.doc_date between '$realisation_start_date_ymd' and '$realisation_end_date_ymd'

                                            and a.ref_bill_serial_no = b.serial_no

                                            and b.bill_date between '$billing_start_date_ymd' and '$billing_end_date_ymd'

                                            and b.client_code like '$client_code'

                                            and b.matter_code like '$matter_code'

                                            and b.initial_code like '$initial_code'

                                            and b.client_code = c.client_code

                                            and b.initial_code = e.initial_code 

                                            and (ifnull(b.cancel_ind,'N') = 'N' or b.cancel_ind = '')

                                            and b.matter_code = d.matter_code

                                            and d.court_code like '$court_code'

                                            and d.court_code = h.code_code  

                                            and h.type_code = '001'

                                            group by ".$group_by_clause."

                                            ) x  

                                        order by ".$order_by_clause  ; 

                            }

                        break;

                    }

                    $excels  = $this->db->query($bill_sql)->getResultArray() ;

                    // echo '<pre>';print_r($excels);die;

                    $bill_cnt  = count($excels);



                	if(empty($excels)) {

                    	session()->setFlashdata('message', 'No Records Found !!');

                    	return redirect()->to($requested_url);

                	}

                    

                    $fileName = 'BILL_REALISATION-'.date('d-m-Y').'.xlsx';  

                    $spreadsheet = new Spreadsheet();

                    $sheet = $spreadsheet->getActiveSheet();



                    switch($info_by) {

                        case 'B' :

                            if($report_type == 'D') {

                                $headings = ['Bill No', 'Bill Date', 'Client', 'Matter', 'Description', 'Initial', 'Court', 'Realised', 'Deficit'];



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



                                    $billamt = isset($excel['billamt']) ? $excel['billamt'] : 0;  

                                    $realamt = $excel['realamt'];  

                                    $defcamt = $excel['defcamt'];  

                                    $balnamt = $billamt - $realamt - $defcamt ;  



                                    $sheet->setCellValue('A' . $rows, $excel['bill_number']);

                                    $sheet->setCellValue('B' . $rows, date_conv($excel['bill_date']));

                                    $sheet->setCellValue('C' . $rows, strtoupper($excel['client_name']));

                                    $sheet->setCellValue('D' . $rows, strtoupper($excel['matter_code']));

                                    $sheet->setCellValue('E' . $rows, strtoupper($excel['matter_desc']));

                                    $sheet->setCellValue('F' . $rows, strtoupper($excel['initial_code']));

                                    $sheet->setCellValue('G' . $rows, strtoupper($excel['court_name']));

                                    $sheet->setCellValue('H' . $rows, ($realamt > 0) ? number_format($realamt,2,'.','') : '');

                                    $sheet->setCellValue('I' . $rows, ($defcamt > 0) ? number_format($defcamt,2,'.','') : '');

                                    

                                    // Apply border to the current row

                                    $style = $sheet->getStyle('A' . $rows . ':I' . $rows);

                                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



                                    $rows++;

                                }

                            }

                            if($report_type == 'S') {

                                $headings = ['Code', 'Name', 'Realised', 'Deficit'];



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



                                    if($report_seqn == 'C') {$level_code = $excel['client_code']  ; $level_name = $excel['client_name']  ; }

                                    if($report_seqn == 'M') {$level_code = $excel['matter_code']  ; $level_name = $excel['matter_desc']  ; }

                                    if($report_seqn == 'I') {$level_code = $excel['initial_code'] ; $level_name = $excel['initial_name'] ; }

                                    if($report_seqn == 'T') {$level_code = $excel['court_code']   ; $level_name = $excel['court_name']   ; }



                                    $billamt = isset($excel['billamt']) ? $excel['billamt'] : 0;  

                                    $realamt = $excel['realamt'];  

                                    $defcamt = $excel['defcamt'];  

                                    $balnamt = $billamt - $realamt - $defcamt ; 



                                    $sheet->setCellValue('A' . $rows, strtoupper($level_code));

                                    $sheet->setCellValue('B' . $rows, strtoupper($level_name));

                                    $sheet->setCellValue('C' . $rows, ($realamt > 0) ? number_format($realamt,2,'.','') : '');

                                    $sheet->setCellValue('D' . $rows, ($defcamt > 0) ? number_format($defcamt,2,'.','') : '');

                                    

                                    // Apply border to the current row

                                    $style = $sheet->getStyle('A' . $rows . ':D' . $rows);

                                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



                                    $rows++;

                                }

                            }

                        break;



                        case 'C' :

                            if($report_type == 'D') {

                                $headings = ['Chq#', 'Chq Dt', 'Bank', 'Bill No', 'Bill Date', 'Client', 'Matter Number', 'Matter', 'Initial', 'Court', 'Bill Amount',  'Realised Date', 'Realised Inpocket', 'Realised Outpocket', 'Realised Counsel', 'Realised S Tax', 'Realised' ,'Deficit'];



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



                                    $realamt = $excel['realamt'];  

                                    $defcamt = $excel['defcamt'];

                                    $billamt = $excel['billamount'];

                                    $realip  = $excel['realise_amount_inpocket'];

                                    $realop  = $excel['realise_amount_outpocket'];

                                    $realcou = $excel['realise_amount_counsel'];

                                    $realst  = $excel['realise_amount_service_tax'];

                                    

                                    if ($excel['court_name'] != '') {



                                        $sheet->setCellValue('A' . $rows, $excel['instrument_no']);

                                        $sheet->setCellValue('B' . $rows, date_conv($excel['instrument_dt']));

                                        $sheet->setCellValue('C' . $rows, strtoupper($excel['bank_name']));

                                        $sheet->setCellValue('D' . $rows, $excel['bill_number']);

                                        $sheet->setCellValue('E' . $rows, date_conv($excel['bill_date']));

                                        $sheet->setCellValue('F' . $rows, strtoupper($excel['client_name']));

                                        $sheet->setCellValue('G' . $rows, strtoupper($excel['matter_code']));

                                        $sheet->setCellValue('H' . $rows, strtoupper($excel['matter_desc']));

                                        $sheet->setCellValue('I' . $rows, strtoupper($excel['initial_code']));

                                        $sheet->setCellValue('J' . $rows, strtoupper($excel['court_name']));

                                        $sheet->setCellValue('K' . $rows, ($billamt > 0) ? number_format($billamt,2,'.','') : '');

                                        $sheet->setCellValue('L' . $rows, date_conv($excel['doc_date']));

                                        $sheet->setCellValue('M' . $rows, ($realip > 0) ? number_format($realip,2,'.','') : '');

                                        $sheet->setCellValue('N' . $rows, ($realop > 0) ? number_format($realop,2,'.','') : '');

                                        $sheet->setCellValue('O' . $rows, ($realcou > 0) ? number_format($realcou,2,'.','') : '');

                                        $sheet->setCellValue('P' . $rows, ($realst > 0) ? number_format($realst,2,'.','') : '');

                                        $sheet->setCellValue('Q' . $rows, ($realamt > 0) ? number_format($realamt,2,'.','') : '');

                                        $sheet->setCellValue('R' . $rows, ($defcamt > 0) ? number_format($defcamt,2,'.','') : '');

                                        

                                        // Apply border to the current row

                                        $style = $sheet->getStyle('A' . $rows . ':R' . $rows);

                                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                                    }

                                    $rows++;

                                }

                            }

                            if($report_type == 'S') {

                                $headings = ['Code', 'Name', 'Chq#', 'Chq Date', 'Bank', 'Received From', 'Realised' ,'Deficit'];



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



                                    if($report_seqn == 'B') {$level_code = $excel['client_code']  ; $level_name = $excel['client_name']  ; }

                                    if($report_seqn == 'C') {$level_code = $excel['client_code']  ; $level_name = $excel['client_name']  ; }

                                    if($report_seqn == 'M') {$level_code = $excel['matter_code']  ; $level_name = $excel['matter_desc']  ; }

                                    if($report_seqn == 'I') {$level_code = $excel['initial_code'] ; $level_name = $excel['initial_name'] ; }

                                    if($report_seqn == 'T') {$level_code = $excel['court_code']   ; $level_name = $excel['court_name'] ; }

                                    //

                                    $realamt = $excel['realamt'];  

                                    $defcamt = $excel['defcamt'];  



                                    $sheet->setCellValue('A' . $rows, strtoupper($level_code));

                                    $sheet->setCellValue('B' . $rows, strtoupper($level_name));

                                    $sheet->setCellValue('C' . $rows, $excel['instrument_no']);

                                    $sheet->setCellValue('D' . $rows, date_conv($excel['instrument_dt']));

                                    $sheet->setCellValue('E' . $rows, strtoupper($excel['bank_name']));

                                    $sheet->setCellValue('F' . $rows, strtoupper($excel['received_from']));

                                    $sheet->setCellValue('G' . $rows, ($realamt > 0) ? number_format($realamt,2,'.','') : '');

                                    $sheet->setCellValue('H' . $rows, ($defcamt > 0) ? number_format($defcamt,2,'.','') : '');

                                    

                                    // Apply border to the current row

                                    $style = $sheet->getStyle('A' . $rows . ':H' . $rows);

                                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                        

                                    $rows++;

                                }

                            }

                        break;   

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

        }else{

            return view("pages/Billing/bill_realisation", compact("data", "displayId"));



        }

    }
    public function bill_os_details(){

        

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches(session()->userId);

    	$data['requested_url'] = $this->session->requested_end_menu_url;

        $fin_year = session()->financialYear;

        $curr_fyrsdt = '01-04-'.substr($fin_year,0,4);

        $reftyp_qry = $this->db->query("select * from code_master where type_code = '007' ")->getResultArray() ;

        $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4215' , 'initial_help_id' => '4191', 'court_help_id' => '4221'] ;

        

        if($this->request->getMethod() == 'post') {

           

            $requested_url = base_url($data['requested_url']);

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



            $report_desc      = "LIST OF OUTSTANDING BILLS (DETAILS)" ;

            $ason_date       = $_REQUEST['ason_date'] ;

            $branch_code     = $_REQUEST['branch_code'] ;

            $start_date      = $_REQUEST['start_date'] ;      

            if($start_date != '') {$start_date_ymd = date_conv($start_date); } else { $start_date_ymd = '1901-01-01'; }

            $end_date        = $_REQUEST['end_date'] ;        

            if($end_date   != '') {$end_date_ymd   = date_conv($end_date);   } else { $end_date_ymd   = date('Y-m-d') ; }

            $client_code     = $_REQUEST['client_code'] ;     

            if(empty($client_code)) { $client_code  = '%' ; }

            $client_name     = $_REQUEST['client_name'] ;

            $court_code      = $_REQUEST['court_code'] ;      if(empty($court_code))  { $court_code   = '%' ; }

            $court_name      = $_REQUEST['court_name'] ;

            $matter_code     = $_REQUEST['matter_code'] ;     

            if(empty($matter_code)) { $matter_code  = '%' ; }

            $matter_desc     = $_REQUEST['matter_desc'] ;

            $initial_code    = $_REQUEST['initial_code'] ;    

            if(empty($initial_code)){ $initial_code = '%' ; }

            $initial_name    = $_REQUEST['initial_name'] ;

            $reference_type  = $_REQUEST['reference_type'] ; 

            $collectable_ind = $_REQUEST['reference_type'] ;  

            $report_seqn     = $_REQUEST['report_seqn'] ;  

            $output_type     = $_REQUEST['output_type'] ;  

            

            $old_matter_desc = 'ALL MATTERS UPTO 30/09/2003' ;

            //

            if($start_date == '') {$period_desc = "UPTO ".$end_date ;} else {$period_desc = $start_date.' - '.$end_date ;}

            

            if($output_type == 'Report' || $output_type == 'Pdf') {

                $branch_qry   = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0] ;

                $branch_name  = $branch_qry['branch_name'] ;

                

                $ref_qry  = $this->db->query("select code_desc from code_master where type_code = '007' and code_code = '$reference_type' ")->getResultArray();

                // echo '<pre>';print_r($ref_qry);die; 

                $reference_desc = isset($ref_qry['code_desc']) ? $ref_qry['code_desc'] : '' ; 

                

                    if($reference_type == '%') 

                {

                    $reference_desc = 'ALL' ; 

                }

                else

                {

                //  $reference_qry  = mysql_fetch_array($this->db->query("select code_desc from code_master where type_code = '007' and code_code = '$referece_type' ",$link));

                    $reference_desc ; 

                }



                

                $bill_sql = '';

                switch($report_seqn) {

                    case 'B' :

                        $bill_sql    = "select concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.matter_code,a.initial_code,

                                            ifnull(a.bill_amount_inpocket,0) bill_amount_inpocket,

                                            ifnull(a.bill_amount_outpocket,0) bill_amount_outpocket,

                                            ifnull(a.bill_amount_counsel,0) bill_amount_counsel,

                                            ifnull(a.service_tax_amount,0) service_tax_amount,

                                            ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0) billed_amount,

                                            (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,b.client_name,concat(c.matter_desc1,' ',c.matter_desc2) matter_desc

                                            from client_master b, bill_detail a left outer join fileinfo_header c on c.matter_code = a.matter_code and c.reference_type_code like '$reference_type'

                                            where a.branch_code like '$branch_code'

                                            and a.client_code like '$client_code'

                                            and a.matter_code like '$matter_code'

                                            and a.initial_code like '$initial_code'

                                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                                            and a.client_code = b.client_code

                                            and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -

                                            ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +

                                            (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +

                                            (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0

                                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                                            order by a.bill_date,a.fin_year,a.bill_no " ; 

                        break;

                    case 'C' :

                        $bill_sql    = "select concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.matter_code,a.initial_code,

                                ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0) billed_amount,

                                (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,b.client_name,

                                if(substring(a.matter_code,1,1)='0','$old_matter_desc',if(c.matter_desc1 != '', concat(c.matter_desc1,' : ',c.matter_desc2),c.matter_desc2)) matter_desc

                                from client_master b, bill_detail a left outer join fileinfo_header c on c.matter_code = a.matter_code and c.reference_type_code like '$reference_type'

                                where a.branch_code like '$branch_code'

                                and a.client_code like '$client_code'

                                and a.matter_code like '$matter_code'

                                and a.initial_code like '$initial_code'

                                and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                                and a.collectable_ind like '$collectable_ind'

                                and a.client_code = b.client_code

                                and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -

                                ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +

                                (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +

                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0

                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                                order by b.client_name,a.bill_date,a.fin_year,a.bill_no " ; 

                        break;

                    case 'M' : 

                        $bill_sql    = "select concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.matter_code,a.initial_code,

                                ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0) billed_amount,

                                (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,b.client_name,

                                if(substring(a.matter_code,1,1)='0','$old_matter_desc',if(c.matter_desc1 != '', concat(c.matter_desc1,' : ',c.matter_desc2),c.matter_desc2)) matter_desc

                                from client_master b, bill_detail a left outer join fileinfo_header c on c.matter_code = a.matter_code and c.reference_type_code like '$reference_type'

                                where a.branch_code like '$branch_code'

                                and a.client_code like '$client_code'

                                and a.matter_code like '$matter_code'

                                and a.initial_code like '$initial_code'

                                and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                                and a.collectable_ind like '$collectable_ind'

                                and a.client_code = b.client_code

                                and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -

                                ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +

                                (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +

                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0

                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                                order by a.matter_code,a.bill_date,a.fin_year,a.bill_no " ; 

                        break;

                    case 'I' :

                        $bill_sql    = "select concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.matter_code,a.initial_code,

                                ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0) billed_amount,

                                (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,b.client_name,

                                if(substring(a.matter_code,1,1)='0','$old_matter_desc',if(c.matter_desc1 != '', concat(c.matter_desc1,' : ',c.matter_desc2),c.matter_desc2)) matter_desc,d.initial_name

                                from client_master b, initial_master d, bill_detail a left outer join fileinfo_header c on c.matter_code = a.matter_code and c.reference_type_code like '$reference_type'

                                where a.branch_code like '$branch_code'

                                and a.client_code like '$client_code'

                                and a.matter_code like '$matter_code'

                                and a.initial_code like '$initial_code'

                                and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                                and a.collectable_ind like '$collectable_ind'

                                and a.client_code = b.client_code

                                and a.initial_code = d.initial_code

                                and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -

                                ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +

                                (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +

                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0

                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                                order by d.initial_name,b.client_name,a.bill_date,a.fin_year,a.bill_no " ;

                        break;

                }

                

                $reports  = $this->db->query($bill_sql)->getResultArray() ;

                // echo '<pre>'; print_r($reports);die;

                $bill_cnt  = count($reports);

                $date = date('d-m-Y');



                if(empty($reports)) {

                    session()->setFlashdata('message', 'No Records Found !!');

                    return redirect()->to($requested_url);

                }



                $params = [

                    "branch_name" => $branch_name,

                    "report_desc" => $report_desc,

                    "ason_date" => $ason_date,

                    "bill_cnt" => $bill_cnt,

                    "period_desc" => $period_desc,

                    "client_code" => $client_code,

                    "client_name" => $client_name,

                    "matter_code" => $matter_code,

                    "matter_desc" => $matter_desc,

                    "initial_code" => $initial_code,

                    "initial_name" => $initial_name,

                    "old_matter_desc" => $old_matter_desc,

                    "reference_type" => $reference_type,

                    "reference_desc" => $reference_desc,

                    "report_seqn" => $report_seqn,

                    "collectable_ind" => $collectable_ind,

                    "date" => $date,

                    "requested_url" => $requested_url,



                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/Billing/bill_os_details", compact("reports", "params", 'report_type'));
                    // echo '<pre>';print_r(htmlspecialchars($reportHTML));die;
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/Billing/bill_os_details", compact("reports", "params"));

            } else if ($output_type == 'Excel') {



                if ($report_seqn == 'B') {

                    $order_by_clause = 'a.bill_date,a.fin_year,a.bill_no' ;

                }

                else if($report_seqn == 'C') {

                    $order_by_clause = 'b.client_name,a.bill_date,a.fin_year,a.bill_no' ;

                }

                else if($report_seqn == 'M') {

                    $order_by_clause = 'a.matter_code,a.bill_date,a.fin_year,a.bill_no' ;

                }

                else if($report_seqn == 'I') {

                    $order_by_clause = 'a.initial_code,b.client_name,a.bill_date,a.fin_year,a.bill_no' ;

                }

                

                $bill_sql = "select c.court_code,d.code_desc court_name,concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.matter_code,a.initial_code,

                            ifnull(a.bill_amount_inpocket,0) inpocket, ifnull(a.bill_amount_outpocket,0) outpocket, ifnull(a.bill_amount_counsel,0) counsel, ifnull(a.service_tax_amount,0) stax,

                            ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0) billed_amount,

                            ifnull(a.realise_amount_inpocket,0) real_ip, ifnull(a.realise_amount_outpocket,0) real_op, ifnull(a.realise_amount_counsel,0) real_con, ifnull(a.realise_amount_service_tax,0) real_st,

                            (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,

                            b.client_name,if(substring(a.matter_code,1,1)='0','',c.matter_desc1) matter_desc1,if(substring(a.matter_code,1,1)='0','$old_matter_desc',c.matter_desc2) matter_desc2,

                            e.reference_desc,c.court_code,e.subject_desc,c.date_of_filing

                            from client_master b, bill_detail a,  fileinfo_header c, code_master d, billinfo_header e

                            where a.branch_code like  '$branch_code'

                            and a.client_code like  '$client_code'

                            and a.matter_code like  '$matter_code'

                            and a.initial_code like  '$initial_code'

                            and a.serial_no = e.ref_bill_serial_no

                            and c.matter_code = a.matter_code and c.reference_type_code like '$reference_type'

                            and c.court_code like '$court_code'

                            and c.court_code = d.code_code and d.type_code = '001' 

                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                            and a.collectable_ind like '$collectable_ind'

                            and a.client_code =  b.client_code

                            and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0)    + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -

                            ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +

                            (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +

                            (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0

                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '') 

                            order by ".$order_by_clause ; 

                                

                $excels  = $this->db->query($bill_sql)->getResultArray() ;

                // echo '<pre>';print_r($excels);die;

                $bill_cnt  = count($excels);



                try {

                    $excels[0];

                    if($bill_cnt == 0)  throw new \Exception('No Records Found !!');



                } catch (\Exception $e) {

                    session()->setFlashdata('message', 'No Records Found !!');

                    return redirect()->to($this->requested_url());

                }

                $fileName = 'BILL_OS_DETAILS-'.date('d-m-Y').'.xlsx';  

                $spreadsheet = new Spreadsheet();

                $sheet = $spreadsheet->getActiveSheet();



                $headings = ['Court', 'Bill No', 'Bill Date', 'Initial', 'Client', 'Filing Date', 'Matter', 'Case No.', 'Matter Description', 'Subject', 'Reference', 'Inpocket', 'Outpocket', 'Counsel', 'Service Tax', 'Billed', 'Realised IP', 'Realised OP', 'Realised Con', 'Realised Stax', 'Realised', 'Balance'];



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



                            $balance_amount = $excel['billed_amount'] - $excel['realised_amount'] ;



                            $sheet->setCellValue('A' . $rows, strtoupper($excel['court_name']));

                            $sheet->setCellValue('B' . $rows, strtoupper($excel['bill_number']));

                            $sheet->setCellValue('C' . $rows, date_conv($excel['bill_date'],'-'));

                            $sheet->setCellValue('D' . $rows, strtoupper($excel['initial_code']));

                            $sheet->setCellValue('E' . $rows, strtoupper($excel['client_name']));

                            $sheet->setCellValue('F' . $rows, date_conv($excel['date_of_filing']));

                            $sheet->setCellValue('G' . $rows, strtoupper($excel['matter_code']));

                            $sheet->setCellValue('H' . $rows, strtoupper($excel['matter_desc1']));

                            $sheet->setCellValue('I' . $rows, strtoupper($excel['matter_desc2']));

                            $sheet->setCellValue('J' . $rows, strtoupper($excel['subject_desc']));

                            $sheet->setCellValue('K' . $rows, "'".strtoupper($excel['reference_desc']));

                            $sheet->setCellValue('L' . $rows, $excel['inpocket']);

                            $sheet->setCellValue('M' . $rows, $excel['outpocket']);

                            $sheet->setCellValue('N' . $rows, $excel['counsel']);

                            $sheet->setCellValue('O' . $rows, $excel['stax']);

                            $sheet->setCellValue('P' . $rows, $excel['billed_amount']);

                            $sheet->setCellValue('Q' . $rows, $excel['real_ip']);

                            $sheet->setCellValue('R' . $rows, $excel['real_op']);

                            $sheet->setCellValue('S' . $rows, $excel['real_con']);

                            $sheet->setCellValue('T' . $rows, $excel['real_st']);

                            $sheet->setCellValue('U' . $rows, $excel['realised_amount']);

                            $sheet->setCellValue('V' . $rows,  number_format($balance_amount, 2,'.',''));

                            

                            // Apply border to the current row

                            $style = $sheet->getStyle('A' . $rows . ':V' . $rows);

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

        }else{

            return view("pages/Billing/bill_os_details", compact("data", "reftyp_qry", "displayId", "curr_fyrsdt"));



        }

    }
    public function bill_os_summary(){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches(session()->userId);

    	$data['requested_url'] = $this->session->requested_end_menu_url;

        $fin_year = session()->financialYear;

        $curr_fyrsdt = '01-04-'.substr($fin_year,0,4);

        $reftyp_qry = $this->db->query("select * from code_master where type_code = '007' ")->getResultArray() ;

        $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4215' , 'initial_help_id' => '4191'] ;



        if($this->request->getMethod() == 'post') {

            $requested_url = base_url($data['requested_url']);

            $display_id    = isset($_REQUEST['display_id'])  ?$_REQUEST['display_id']  :null;

            $param_id      = isset($_REQUEST['param_id'])    ?$_REQUEST['param_id']    :null;

            $my_menuid     = isset($_REQUEST['my_menuid'])   ?$_REQUEST['my_menuid']   :null;

            $menu_id       = isset($_REQUEST['menu_id'])     ?$_REQUEST['menu_id']     :null;	

            $user_option   = isset($_REQUEST['user_option']) ?$_REQUEST['user_option'] :null;

            $screen_ref    = isset($_REQUEST['screen_ref'])  ?$_REQUEST['screen_ref']  :null;

            $index         = isset($_REQUEST['index'])       ?$_REQUEST['index']       :null;

            $ord           = isset($_REQUEST['ord'])         ?$_REQUEST['ord']         :null;

            $pg            = isset($_REQUEST['pg'])          ?$_REQUEST['pg']          :null;

            $search_val    = isset($_REQUEST['search_val'])  ?$_REQUEST['search_val']  :null;



            $ason_date       = $_REQUEST['ason_date'] ;

            $branch_code     = $_REQUEST['branch_code'] ;

            $start_date      = $_REQUEST['start_date'] ;      

            if($start_date != '')   {$start_date_ymd = date_conv($start_date); } else { $start_date_ymd = '1901-01-01'; }

            $end_date        = $_REQUEST['end_date'] ;        

            if($end_date   != '')   {$end_date_ymd   = date_conv($end_date);   } else { $end_date_ymd   = date('Y-m-d') ; }

            $client_code     = $_REQUEST['client_code'] ;     

            if(empty($client_code)) { $client_code  = '%' ; }

            $client_name     = $_REQUEST['client_name'] ;

            $matter_code     = $_REQUEST['matter_code'] ;     

            if(empty($matter_code)) { $matter_code  = '%' ; }

            $matter_desc     = $_REQUEST['matter_desc'] ;

            $initial_code    = $_REQUEST['initial_code'] ;    

            if(empty($initial_code)){ $initial_code = '%' ; }

            $initial_name    = $_REQUEST['initial_name'] ;

            $reference_type  = $_REQUEST['reference_type'] ;  

            //echo '<pre>';print_r($reference_type);die;

            $collectable_ind = $_REQUEST['reference_type'] ;  

            $os_order        = $_REQUEST['os_order'] ; 

            $report_seqn = $_REQUEST['report_seqn'] ;
            
            $output_type     = $_REQUEST['output_type'] ;  

            $old_matter_desc = 'ALL MATTERS UPTO 30/09/2003' ; 

            if($output_type == 'Report' || $output_type == 'Pdf') {

                if($start_date == '') {$period_desc = "UPTO ".$end_date ;} else {$period_desc = $start_date.' - '.$end_date ;}
    
            
    
                if($reference_type == '%') 
    
                {
    
                $reference_desc = 'ALL' ; 
    
                }
    
                else
    
                {
    
                $reference_qry  = $this->db->query("select code_desc from code_master where code_code = '$reference_type' and type_code = '007' ")->getResultArray();
    
                $reference_desc = isset($ref_qry['code_desc']) ? $ref_qry['code_desc'] : '' ;  
    
                }
    
                
    
                $branch_qry   = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0] ;
    
                $branch_name  = $branch_qry['branch_name'] ;
    
                
    
                $bill_sql = '';
    
                switch($report_seqn) {
    
                    case 'C' :
    
    
    
                        if($os_order=='D') { $report_desc = 'LIST OF OUTSTANDING BILLS (SUMMARY) [DESCENDING]'  ; $order_by_clause = '(x.billed_amount-x.realised_amount) desc' ; } 
    
                        if($os_order=='A') { $report_desc = 'LIST OF OUTSTANDING BILLS (SUMMARY) [ASSCENDING]'  ; $order_by_clause = '(x.billed_amount-x.realised_amount)'      ; }
    
                        if($os_order=='N') { $report_desc = 'LIST OF OUTSTANDING BILLS (SUMMARY) [CLIENT-WISE]' ; $order_by_clause = 'x.client_name'                            ; }
    
    
    
                        $bill_sql    = "select x.client_code,x.client_name,x.billed_amount,x.realised_amount,(x.billed_amount-x.realised_amount) balance_amount
    
                                from (select a.client_code,b.client_name,sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount
    
                                from client_master b, bill_detail a left outer join fileinfo_header c on c.matter_code = a.matter_code and c.reference_type_code like '$reference_type'
    
                                where a.branch_code like '$branch_code'
    
                                and a.client_code like '$client_code'
    
                                and a.matter_code like '$matter_code'
    
                                and a.initial_code like '$initial_code'
    
                                and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
    
                                and a.collectable_ind like '$collectable_ind'
    
                                and a.client_code = b.client_code
    
                                and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
    
                                ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +
    
                                (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +
    
                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
    
                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
    
                                group by a.client_code,b.client_name
    
                                ) x
    
                                order by ".$order_by_clause ; 
    
                        break;
    
                    case 'M' :  
    
    
    
                        if($os_order=='D') { $report_desc = 'LIST OF OUTSTANDING BILLS (SUMMARY) [DESCENDING]'  ; $order_by_clause = '(x.billed_amount-x.realised_amount) desc' ; } 
    
                        if($os_order=='A') { $report_desc = 'LIST OF OUTSTANDING BILLS (SUMMARY) [ASSCENDING]'  ; $order_by_clause = '(x.billed_amount-x.realised_amount)'      ; }
    
                        if($os_order=='N') { $report_desc = 'LIST OF OUTSTANDING BILLS (SUMMARY) [MATTER-WISE]' ; $order_by_clause = 'x.matter_code'                            ; }
    
    
    
                        $bill_sql    = "select x.matter_code,x.matter_desc,x.billed_amount,x.realised_amount,(x.billed_amount-x.realised_amount) balance_amount
    
                            from (select a.matter_code,if(substring(a.matter_code,1,1)='0','$old_matter_desc',if(b.matter_desc1 != '', concat(b.matter_desc1,' : ',b.matter_desc2),b.matter_desc2)) matter_desc,sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount
    
                            from bill_detail a left outer join fileinfo_header b on b.matter_code = a.matter_code and b.reference_type_code like '$reference_type'
    
                            where a.branch_code like '$branch_code'
    
                            and a.client_code like '$client_code'
    
                            and a.matter_code like '$matter_code'
    
                            and a.initial_code like '$initial_code'
    
                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
    
                            and a.collectable_ind like '$collectable_ind'
    
                            and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
    
                            ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +
    
                            (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +
    
                            (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
    
                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
    
                            group by a.matter_code
    
                            ) x
    
                            order by ".$order_by_clause ;
    
                        break; 
    
                    case 'I' :
    
    
    
                        if($os_order=='D') { $report_desc = 'LIST OF OUTSTANDING BILLS (SUMMARY) [DESCENDING]'   ; $order_by_clause = '(x.billed_amount-x.realised_amount) desc' ; } 
    
                        if($os_order=='A') { $report_desc = 'LIST OF OUTSTANDING BILLS (SUMMARY) [ASSCENDING]'   ; $order_by_clause = '(x.billed_amount-x.realised_amount)'      ; }
    
                        if($os_order=='N') { $report_desc = 'LIST OF OUTSTANDING BILLS (SUMMARY) [INITIAL-WISE]' ; $order_by_clause = 'x.initial_name'                           ; }
    
    
    
                        $bill_sql    = "select x.initial_code,x.initial_name,x.billed_amount,x.realised_amount,(x.billed_amount-x.realised_amount) balance_amount
    
                            from (select a.initial_code,b.initial_name,sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount
    
                            from initial_master b, bill_detail a left outer join fileinfo_header c on c.matter_code = a.matter_code and c.reference_type_code like '$reference_type'
    
                            where a.branch_code like '$branch_code'
    
                            and a.client_code like '$client_code'
    
                            and a.matter_code like '$matter_code'
    
                            and a.initial_code like '$initial_code'
    
                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
    
                            and a.collectable_ind like '$collectable_ind'
    
                            and a.initial_code = b.initial_code
    
                            and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
    
                            ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0)  + ifnull(a.realise_amount_service_tax,0)) +
    
                            (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0)  + ifnull(a.advance_amount_service_tax,0)) +
    
                            (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0)  + ifnull(a.deficit_amount_service_tax,0))) > 0
    
                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
    
                            group by a.initial_code,b.initial_name
    
                            ) x
    
                            order by ".$order_by_clause ; 
    
                        break;
    
                }
    
    
    
                $reports  = $this->db->query($bill_sql)->getResultArray() ;
    
                //echo '<pre>';print_r($reports);die;
    
                $bill_cnt  = count($reports);
    
                $date = date('d-m-Y');
    
    
    
            	if(empty($reports)) {
    
                    session()->setFlashdata('message', 'No Records Found !!');
    
                    return redirect()->to($requested_url);
    
                }
    
            
    
                    $params = [
    
                        "branch_name" => $branch_name,
    
                        "report_desc" => $report_desc,
    
                        "ason_date" => $ason_date,
    
                        "bill_cnt" => $bill_cnt,
    
                        "period_desc" => $period_desc,
    
                        "client_code" => $client_code,
    
                        "client_name" => $client_name,
    
                        "matter_code" => $matter_code,
    
                        "matter_desc" => $matter_desc,
    
                        "initial_code" => $initial_code,
    
                        "initial_name" => $initial_name,
    
                        "old_matter_desc" => $old_matter_desc,
    
                        "reference_type" => $reference_type,
    
                        "reference_desc" => $reference_desc,
    
                        "report_seqn" => $report_seqn,
    
                        "collectable_ind" => $collectable_ind,
    
                        "date" => $date,
    
                        "requested_url" => $requested_url,
    
            
    
                    ];
    
                        if ($output_type == 'Pdf') {
                            $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                            $reportHTML = view("pages/Billing/bill_os_summary", compact("reports", "params", 'report_type'));
                            $dompdf->loadHtml($reportHTML);
                            $dompdf->setPaper('A4', 'landscape'); // portrait
                            $dompdf->render();
                            $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                        } else return view("pages/Billing/bill_os_summary", compact("reports", "params"));
            } if($output_type == 'Excel') { 
                $bill_sql = '';
                switch($report_seqn) {
                    case 'C' :

                        if($os_order=='D') { $order_by_clause = '(x.billed_amount-x.realised_amount) desc' ; } 
                        if($os_order=='A') { $order_by_clause = '(x.billed_amount-x.realised_amount)'      ; }
                        if($os_order=='N') { $order_by_clause = 'x.client_name'                            ; }

                        $bill_sql = "select x.client_code,x.client_name,x.billed_amount,x.realised_amount,(x.billed_amount-x.realised_amount) balance_amount
                                    from (select a.client_code, b.client_name,
                                        sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
                                        sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount
                                        from client_master b, bill_detail a left outer join fileinfo_header c on c.matter_code = a.matter_code and c.reference_type_code like '$reference_type'
                                        where a.branch_code like '$branch_code'
                                        and a.client_code like '$client_code'
                                        and a.matter_code like '$matter_code'
                                        and a.initial_code like '$initial_code'
                                        and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
                                        and a.collectable_ind like '$collectable_ind'
                                        and a.client_code = b.client_code
                                        and (ifnull(a.bill_amount_inpocket,0)    + ifnull(a.bill_amount_outpocket,0)     + ifnull(a.bill_amount_counsel,0)     + ifnull(a.service_tax_amount,0)) -
                                        ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0)  + ifnull(a.realise_amount_service_tax,0)) +
                                        (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0)  + ifnull(a.advance_amount_service_tax,0)) +
                                        (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0)  + ifnull(a.deficit_amount_service_tax,0))) > 0
                                        and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                                        group by a.client_code,b.client_name
                                        ) x
                                    order by ".$order_by_clause ; 
                        break;
                    case 'M' :  

                        if($os_order=='D') { $order_by_clause = '(x.billed_amount-x.realised_amount) desc' ; } 
                        if($os_order=='A') { $order_by_clause = '(x.billed_amount-x.realised_amount)'      ; }
                        if($os_order=='N') { $order_by_clause = 'x.matter_code'                            ; }

                        $bill_sql = "select x.matter_code,x.matter_desc1,x.matter_desc2,x.billed_amount,x.realised_amount,(x.billed_amount-x.realised_amount) balance_amount
                                from (select a.matter_code,if(substring(a.matter_code,1,1)='0','',b.matter_desc1) matter_desc1,
                                    if(substring(a.matter_code,1,1)='0','$old_matter_desc',b.matter_desc2) matter_desc2,
                                    sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
                                    sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount
                                    from bill_detail a left outer join fileinfo_header b on b.matter_code = a.matter_code and b.reference_type_code like '$reference_type'
                                    where a.branch_code like '$branch_code'
                                    and a.client_code like '$client_code'
                                    and a.matter_code like '$matter_code'
                                    and a.initial_code like '$initial_code'
                                    and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
                                    and a.collectable_ind like '$collectable_ind'
                                    and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
                                    ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0)  + ifnull(a.realise_amount_service_tax,0)) +
                                    (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0)  + ifnull(a.advance_amount_service_tax,0)) +
                                    (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0)  + ifnull(a.deficit_amount_service_tax,0))) > 0
                                    and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                                    group by a.matter_code
                                    ) x
                                order by ".$order_by_clause ; 
                        break; 
                    case 'I' :

                        if($os_order=='D') { $order_by_clause = '(x.billed_amount-x.realised_amount) desc' ; } 
                        if($os_order=='A') { $order_by_clause = '(x.billed_amount-x.realised_amount)'      ; }
                        if($os_order=='N') { $order_by_clause = 'x.initial_name,x.client_name'             ; }

                        $bill_sql = "select x.initial_code,x.initial_name,x.client_code,x.client_name,x.billed_amount,x.realised_amount,(x.billed_amount-x.realised_amount) balance_amount
                                    from (select a.initial_code, b.initial_name, a.client_code, d.client_name,
                                        sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
                                        sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount
                                        from client_master d, initial_master b, bill_detail a left outer join fileinfo_header c on c.matter_code = a.matter_code and c.reference_type_code like '$reference_type'
                                        where a.branch_code like '$branch_code'
                                        and a.client_code like '$client_code'
                                        and a.matter_code like '$matter_code'
                                        and a.initial_code like '$initial_code'
                                        and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
                                        and a.collectable_ind like '$collectable_ind'
                                        and a.initial_code = b.initial_code
                                        and a.client_code = d.client_code
                                        and (ifnull(a.bill_amount_inpocket,0)    + ifnull(a.bill_amount_outpocket,0)     + ifnull(a.bill_amount_counsel,0)     + ifnull(a.service_tax_amount,0)) -
                                        ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0)  + ifnull(a.realise_amount_service_tax,0)) +
                                        (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0)  + ifnull(a.advance_amount_service_tax,0)) +
                                        (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0)  + ifnull(a.deficit_amount_service_tax,0))) > 0
                                        and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                                        group by a.initial_code,b.initial_name,a.client_code,d.client_name
                                        ) x
                                    order by ".$order_by_clause ; 
                        break;
                }

                // echo '<pre>';print_r($bill_sql);die;
                $excels  = $this->db->query($bill_sql)->getResultArray() ;
                $bill_cnt  = count($excels);
                $date = date('d-m-Y');

                try {
                $excels[0];
                if($bill_cnt == 0)  throw new \Exception('No Records Found !!');
        
                } catch (\Exception $e) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($_SERVER['REQUEST_URI']);
                }

                $fileName = 'BILL_OS_SUMMARY-'.date('d-m-Y').'.xlsx';  
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                switch($report_seqn) {
                    case 'C' :
                        $headings = ['Client', 'Client Name', 'Billed', 'Realised', 'Balance'];

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

                            $sheet->setCellValue('A' . $rows, strtoupper($excel['client_code']));
                            $sheet->setCellValue('B' . $rows, strtoupper($excel['client_name']));
                            $sheet->setCellValue('C' . $rows, $excel['billed_amount']);
                            $sheet->setCellValue('D' . $rows, $excel['realised_amount']);
                            $sheet->setCellValue('E' . $rows, number_format($excel['balance_amount'],2,'.',''));
                            
                            // Apply border to the current row
                            $style = $sheet->getStyle('A' . $rows . ':E' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                            $rows++;
                        } break;

                    case 'M' :
                        $headings = ['Matter', 'Case Number', 'Matter Description', 'Billed', 'Realised', 'Balance'];

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

                            $sheet->setCellValue('A' . $rows, strtoupper($excel['matter_code']));
                            $sheet->setCellValue('B' . $rows, strtoupper($excel['matter_desc1']));
                            $sheet->setCellValue('C' . $rows, strtoupper($excel['matter_desc2']));
                            $sheet->setCellValue('D' . $rows, $excel['billed_amount']);
                            $sheet->setCellValue('E' . $rows, $excel['realised_amount']);
                            $sheet->setCellValue('F' . $rows, number_format($excel['balance_amount'],2,'.',''));
                            
                            // Apply border to the current row
                            $style = $sheet->getStyle('A' . $rows . ':F' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                            $rows++;
                        } break;

                    case 'I' :
                        $headings = ['Initial', 'Client', 'Client Name', 'Billed', 'Realised', 'Balance'];

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

                            $sheet->setCellValue('A' . $rows, strtoupper($excel['initial_code']));
                            $sheet->setCellValue('B' . $rows, strtoupper($excel['client_code']));
                            $sheet->setCellValue('C' . $rows, strtoupper($excel['client_name']));
                            $sheet->setCellValue('D' . $rows, $excel['billed_amount']);
                            $sheet->setCellValue('E' . $rows, $excel['realised_amount']);
                            $sheet->setCellValue('F' . $rows, number_format($excel['balance_amount'],2,'.',''));
                            
                            // Apply border to the current row
                            $style = $sheet->getStyle('A' . $rows . ':F' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                            $rows++;
                        } break;
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
        }else{

            return view("pages/Billing/bill_os_summary", compact("data", "reftyp_qry", "displayId", "curr_fyrsdt"));



        }

              

    }
    public function bill_followup_letter_billing_addr(){    // get error for temp db//

       

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches(session()->userId);

    	$data['requested_url'] = $this->session->requested_end_menu_url;

        $displayId   = ['client_help_id' => '4072', 'initial_help_id' => '4191'] ;



        if($this->request->getMethod() == 'post') {

            $requested_url = base_url($data['requested_url']);
    
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
    
            $date = date('d-m-Y');
    
    
    
            $ason_date      = $_REQUEST['ason_date'] ;
    
            $branch_code    = $_REQUEST['branch_code'] ;
    
            $client_code    = $_REQUEST['client_code'] ;      
    
            if($client_code  == '') {$client_code  = '%';}
    
            $client_name    = $_REQUEST['client_name'] ;      
    
            $initial_code   = $_REQUEST['initial_code'] ;     
    
            if($initial_code == '') {$initial_code = '%';}
    
            $initial_name   = $_REQUEST['initial_name'] ;  
    
            $start_date     = $_REQUEST['start_date'] ;       
    
            if($start_date   != '') {$start_date_ymd = date_conv($start_date);} else {$start_date_ymd = '1901-01-01';}
    
            $end_date       = $_REQUEST['end_date'] ;         
    
            if($end_date     != '') {$end_date_ymd   = date_conv($end_date)  ;} else {$end_date_ymd   = date('Y-m-d') ;}
            
            $output_type      = $_REQUEST['output_type'] ;
    
    
    
            $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];
    
            $user_id = session()->userId ;
    
            $curr_time      = $logdt_qry['current_time'];
    
            $curr_date      = $logdt_qry['current_dmydate'];
    
            $curr_day       = substr($curr_date,0,2) ;
    
            $curr_month     = substr($curr_date,3,2) ; 
    
            $curr_year      = substr($curr_date,6,4) ;
    
            if($output_type == 'Report' || $output_type == 'Pdf') {
    
                $temp_id        = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);
        
                //echo '<pre>';print_r($user_id);die;
        
                $branch_qry    = $this->db->query("select * from branch_master where branch_code = '$branch_code' ")->getResultArray()[0] ;
        
                $branch_name   = $branch_qry['branch_name'] ;
        
                $branch_addr1  = strtoupper($branch_qry['address_line_1'].', '.$branch_qry['city'].' - '.$branch_qry['pin_code']) ;
        
                $branch_addr2  = 'TEL : '.$branch_qry['phone_no'].'     FAX : '.$branch_qry['fax_no'] ;
        
                $branch_addr3  = 'E-Mail : '.$branch_qry['email_id'] ;
        
                $branch_panno  = $branch_qry['pan_no'] ;
        
                //
        
                $x25thLogoYear   = get_parameter_value('20') ;
        
                $x25thLogoInd    = (substr($date,6,4) == $x25thLogoYear) ? 'Y' : 'N' ; 
        
                
        
                //------
        
                $mytable = $temp_id.'pending_bill_count' ; 
        
                // echo '<pre>';print_r($mytable);die;
        
                $this->temp_db->query("drop table if exists $mytable") ;
        
                $this->temp_db->query("create table if not exists $mytable (attention_code int(8), bill_count int(8))") ;
        
        
        
               $sinha_db_data = "select a.attention_code, count(a.bill_no) AS billC_no
        
               from bill_detail a, client_master b, client_attention c, client_address d 
        
               where a.branch_code like '$branch_code'
        
               and a.client_code like '$client_code'
        
               and a.initial_code like '$initial_code'
        
               and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
        
               and ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
        
               (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) -
        
               (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) -
        
               (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
        
               and a.client_code = b.client_code
        
               and a.attention_code = c.attention_code
        
               and a.address_code = d.address_code
        
               and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
        
               group by a.attention_code ";
        
               
        
               $data = $this->db->query($sinha_db_data)->getResultArray();
        
               foreach ($data as  $value) {
        
                    $attention_code1=$value['attention_code'];
        
                    $billC_no1=$value['billC_no'];
        
                    $pendcnt_stmt  = "insert into $mytable (attention_code, bill_count) values ('$attention_code1','$billC_no1' )";
        
                
        
                    //echo '<pre>';print_r($pendcnt_stmt);die;
        
                    $this->temp_db->query($pendcnt_stmt);
        
               }
        
         
        
                $sqlTempTb="select * from $mytable";
        
                $temp_db_data = $this->temp_db->query($sqlTempTb)->getResultArray();
        
                $a = array();
        
                $c = array();
        
                //echo $a = explode(",",$TempTbdata['attention_code']);die;
        
                if(!empty($temp_db_data)) {
        
                    foreach ($temp_db_data as $key=> $value) {
        
                        //array_push($a,$value['attention_code']);
        
                        $tempattention_code[]=$value['attention_code'];
        
                        //$tempattention_code = $value['attention_code'];
        
                        $tempbill_count[] = $value['bill_count'];
        
                        $string_version = implode(',', $tempattention_code);
        
                        array_push($a,$string_version);
        
                        $string_version2 = implode(',', $tempbill_count);
        
                        array_push($c,$string_version2);
        
                    }  
        
                    $pendbill_stmt = "select a.initial_code,a.client_code,b.client_name,a.attention_code,c.attention_name,c.sex,a.address_code,d.address_line_1,d.address_line_2,d.address_line_3,d.address_line_4,d.city,d.pin_code,concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.bill_cause,
        
                            (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0))  billamount,
        
                            (((ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))+(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)))) realamount,
        
                            (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0) - ((ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))+(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0))))  osamount from bill_detail a, client_master b, client_attention c, client_address d 
        
                            where a.branch_code like '$branch_code'
        
                            and a.client_code like '$client_code'
        
                            and a.initial_code like '$initial_code'
        
                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
        
                            and ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
        
                            (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) -
        
                            (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) -
        
                            (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
        
                            and a.client_code = b.client_code
        
                            and a.attention_code = c.attention_code
        
                            and a.address_code = d.address_code
        
                            and a.attention_code in ($a[$key])
        
                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
        
                            order by a.initial_code,b.client_name,a.attention_code,a.bill_date,a.serial_no";
        
                                
        
             
        
                    // echo $pendbill_stmt;echo ';';echo '<br>';die;
        
                    // echo '<pre>';print_r($combineData);
        
                    //}
        
            
        
                    $reports = $this->db->query($pendbill_stmt)->getResultArray();
        
                    //array_push($a,$reports);
        
                    //  echo '<pre>';print_r($a);die;
        
                        
        
                        
        
                    //$reports  = $this->db->query($pendbill_stmt)->getResultArray();
        
            
        
                    $pendbill_cnt  = count($reports);
        
                    // echo '<pre>';print_r($pendbill_cnt);die;
        
                    $bill_count    = $pendbill_cnt ;
        
                }
        
        
        
                if(empty($reports)) {
        
                     session()->setFlashdata('message', 'No Records Found !!');
        
        			return redirect()->to($requested_url);
        
                }
        
            
        
                    $params = [
        
                        "branch_name" => $branch_name,
        
                        //"report_desc" => $report_desc,
        
                        "ason_date" => $ason_date,
        
                        //"bill_cnt" => $bill_cnt,
        
                        //"period_desc" => $period_desc,
        
                        "client_code" => $client_code,
        
                        "client_name" => $client_name,
        
                        "initial_code" => $initial_code,
        
                        "initial_name" => $initial_name,
        
                        //"old_matter_desc" => $old_matter_desc,
        
                        "bill_count" => $bill_count,
        
                        "branch_addr1" => $branch_addr1,
        
                        "branch_addr2" => $branch_addr2,
        
                        "branch_addr3" => $branch_addr3,
        
                        "branch_panno" => $branch_panno,
        
                        "pendbill_cnt" => $pendbill_cnt,
        
                        "date" => $date,
        
                        "requested_url" => $requested_url,
        
            
        
                    ];
        
                    if ($output_type == 'Pdf') {
                        $options = new \Dompdf\Options();
                        $options->set('isRemoteEnabled', true);
                        $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf($options); 
                        $reportHTML = view("pages/Billing/bill_followup_letter_billing_addr", compact("reports", "params", "c", "report_type"));
                        // echo '<pre>';print_r(htmlspecialchars($reportHTML));die;
                        $dompdf->loadHtml($reportHTML);
                        $dompdf->setPaper('A4', 'landscape'); // portrait
                        $dompdf->render();
                        $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                    } else return view("pages/Billing/bill_followup_letter_billing_addr", compact("reports", "params","c"));
                }

        }else{

            return view("pages/Billing/bill_followup_letter_billing_addr", compact("data", "displayId"));



        }

    }
    public function bill_followup_letter_specific_addr(){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches(session()->userId);

    	$data['requested_url'] = $this->session->requested_end_menu_url;

        $displayId   = ['client_help_id' => '4072', 'initial_help_id' => '4191', 'adratn_help_id' => '4550'] ;



        if($this->request->getMethod() == 'post') {

            $requested_url = base_url($data['requested_url']);
    
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
    
    
    
            $date = date('d-m-Y');
    
            $ason_date       = $_REQUEST['ason_date'] ;
    
            $branch_code     = $_REQUEST['branch_code'] ;
    
            $client_code     = $_REQUEST['client_code'] ;  
    
            $client_name     = $_REQUEST['client_name'] ;
    
            $client_name     = str_replace('_|_','&',$client_name) ;
    
            $client_name     = str_replace('-|-',"'",$client_name) ;   
    
            $initial_code    = $_REQUEST['initial_code'] ;     if($initial_code == '') {$initial_code = '%';}
    
            $initial_name    = $_REQUEST['initial_name'] ;  
    
            $start_date      = $_REQUEST['start_date'] ;       if($start_date   != '') {$start_date_ymd = date_conv($start_date);} else {$start_date_ymd = '1901-01-01';}
    
            $end_date        = $_REQUEST['end_date'] ;         if($end_date     != '') {$end_date_ymd   = date_conv($end_date)  ;} else {$end_date_ymd   = date('Y-m-d') ;}
    
            $unadjadv_ind    = $_REQUEST['unadjadv_ind'] ;
    
            $attention_name  = $_REQUEST['attention_name'] ;
    
            $attention_code  = $_REQUEST['attention_code'] ;
            
            $output_type  = $_REQUEST['output_type'] ;
    
    
            $address_line_1  = $_REQUEST['address_line_1'] ;
    
            $address_line_2  = $_REQUEST['address_line_2'] ;
    
            $address_line_3  = $_REQUEST['address_line_3'] ;
    
            $address_line_4  = $_REQUEST['address_line_4'] ;
    
            $address_line_5  = $_REQUEST['address_line_5'] ;
    
            if($output_type == 'Report' || $output_type == 'Pdf') {
    
                $branch_qry     = $this->db->query("select * from branch_master where branch_code = '$branch_code' ")->getResultArray()[0] ;
        
                $branch_name    = $branch_qry['branch_name'] ;
        
                $branch_addr1   = strtoupper($branch_qry['address_line_1'].', '.$branch_qry['city'].' - '.$branch_qry['pin_code']) ;
        
                $branch_addr2   = 'TEL : '.$branch_qry['phone_no'].'     FAX : '.$branch_qry['fax_no'] ;
        
                $branch_addr3   = 'E-Mail : '.$branch_qry['email_id'] ;
        
                $branch_panno   = $branch_qry['pan_no'] ;
        
        
                $attn_qry     = $this->db->query("select * from client_attention where attention_code = '$attention_code' ")->getRowArray() ;
        
                $attention_name = stripslashes($_REQUEST['attention_name']) ;
        
                $attention_name   = str_replace('_|_','&',$attention_name) ;
        
                $attention_name   = str_replace('-|-',"'",$attention_name) ;
        
                $attention_sex    = isset($attn_qry['sex']) ? $attn_qry['sex'] : '' ;
        
                $designation      = isset($attn_qry['designation']) ? $attn_qry['designation'] : '' ;
        
                
        
                if($attention_sex == 'F') { $attn_name = $attention_name ;}
        
                if($attention_sex == 'M') { $attn_name = 'Mr. '.$attention_name ; }
        
                //
        
                $report_desc   = 'LIST OF ADVANCE UN-ADJUSTED AS ON DATE' ;
        
        
        
                // $payee_type     = $_REQUEST['payee_type'] ;  
        
                // $payee_code     = $_REQUEST['client_code'] ;     
        
                // $advance_type   = $_REQUEST['advance_type'] ;  
        
        
        
                $financial_year = session()->financialYear;
        
                $letter_ref_no  = 'S/'.strtoupper($client_code).'/'.$financial_year.'/' ;
        
                $letter_ref_dt  = $date ;
        
                //
        
                $x25thLogoYear   = get_parameter_value('20') ;
        
                $x25thLogoInd    = (substr($letter_ref_dt,6,4) == $x25thLogoYear) ? 'Y' : 'N' ; 
        
                //
        
                if ($unadjadv_ind == 'N') 
        
                {
        
                    $pendbill_stmt = "select '1' level_ind,concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.matter_code,if(b.matter_desc1 != '', concat(b.matter_desc1,' : ',b.matter_desc2), b.matter_desc2) matter_desc,(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billamt,((ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))+(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0))) realamt
        
                                from bill_detail a left outer join fileinfo_header b on b.matter_code = a.matter_code
        
                                where a.branch_code like '$branch_code'
        
                                and a.client_code like '$client_code'
        
                                and a.initial_code like '$initial_code'
        
                                and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
        
                                and ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
        
                                (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) -
        
                                (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) -
        
                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))) > 0
        
                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
        
                                order by a.bill_date, a.serial_no";
        
                } 
        
                else 
        
                {
        
                    $pendbill_stmt = "select '1' level_ind,concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.matter_code,if(b.matter_desc1 != '', concat(b.matter_desc1,' : ',b.matter_desc2), b.matter_desc2) matter_desc,(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billamt,((ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))+(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0))) realamt
        
                                from bill_detail a left outer join fileinfo_header b on b.matter_code = a.matter_code
        
                                where a.branch_code    like '$branch_code'
        
                                and a.client_code    like '$client_code'
        
                                and a.initial_code   like '$initial_code'
        
                                and a.bill_date   between '$start_date_ymd' and '$end_date_ymd'
        
                                and ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0)) -
        
                                (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) -
        
                                (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) -
        
                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))) > 0
        
                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
        
                                union all
        
                                select '2' level_ind,'Unadjusted Adv' bill_number,'' bill_date,'' matter_code,concat('Instr No ',a.instrument_no,' Dated ',date_format(a.instrument_dt,'%d-%m-%Y')) matter_desc,0 bill_amount,(ifnull(a.gross_amount,0)-ifnull(a.adjusted_amount,0)) realamt
        
                                from advance_details a left outer join fileinfo_header b on b.matter_code = a.matter_code and b.initial_code like '$initial_code'
        
                                where a.branch_code    like '$branch_code'
        
                                and a.client_code    like '$client_code'
        
                                and (ifnull(a.gross_amount,0)-ifnull(a.adjusted_amount,0)) > 0
        
                                order by 1,3,2"; 
        
                }
        
                // echo '<pre>';print_r($pendbill_stmt);die;
        
                $reports  = $this->db->query($pendbill_stmt)->getResultArray();
        
                $bill_count  = count($reports);
        
                
        
                if(empty($reports)) {
        
        			session()->setFlashdata('message', 'No Records Found !!');
        
        			return redirect()->to($requested_url);
        
        		}
        
            
        
                    $params = [
        
                        "branch_name" => $branch_name,
        
                        "report_desc" => $report_desc,
        
                        "ason_date" => $ason_date,
        
                        "bill_count" => $bill_count,
        
                        //"period_desc" => $period_desc,
        
                        "client_code" => $client_code,
        
                        "client_name" => $client_name,
        
                        "initial_code" => $initial_code,
        
                        "initial_name" => $initial_name,
        
                        "letter_ref_no" => $letter_ref_no,
        
                        "letter_ref_dt" => $letter_ref_dt,
        
                        "address_line_1" => $address_line_1,
        
                        "address_line_2" => $address_line_2,
        
                        "address_line_3" => $address_line_3,
        
                        "address_line_4" => $address_line_4,
        
                        "address_line_5" => $address_line_5,
        
                        "attn_name" => isset($attn_name) ? $attn_name : '' ,
        
                        "designation" => $designation,
        
                        "branch_panno" => $branch_panno,
        
                        "date" => $date,
        
                        "requested_url" => $requested_url,
        
            
        
                    ];
        
                    if ($output_type == 'Pdf') {
                        $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                        $reportHTML = view("pages/Billing/bill_followup_letter_specific_addr", compact("reports", "params", "report_type"));
                        // echo '<pre>';print_r($reportHTML);die;
                        $dompdf->loadHtml($reportHTML);
                        $dompdf->setPaper('A4', 'landscape'); // portrait
                        $dompdf->render();
                        $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                    } else return view("pages/Billing/bill_followup_letter_specific_addr", compact("reports", "params"));
        
            }

        }else{

            return view("pages/Billing/bill_followup_letter_specific_addr", compact("data", "displayId"));



        }

    }
    public function activity_cost_statement($option = null){
        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

       	$data = branches(session()->userId);

    	$data['requested_url'] = $this->session->requested_end_menu_url;
        $finsub     = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
        $displayId   = ['client_help_id' => '4072', 'adratn_help_id' => '4534'] ;

        $user_option = $option;
        $params = [];

        if($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            $branch_code    = $_REQUEST['branch_code'] ;            if($branch_code    == '') { $branch_code    = '%' ; }
            $start_date     = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:null;
            $end_date       = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:null;
            $client_code    = $_REQUEST['client_code'] ;            if($client_code    == '') { $client_code    = '%' ; }
            $attention_code = $_REQUEST['attention_code'] ;         if($attention_code == '') { $attention_code = '%' ; }
            $attention_name = stripslashes($_REQUEST['attention_name']) ;
            $attention_name = str_replace('_|_', '&', $attention_name) ;
            $attention_name = str_replace('-|-', "'", $attention_name) ;
            $address_line_1 = ($_REQUEST['address_line_1']) ;       $address_line_1 = str_replace('_|_','&',$address_line_1) ;
            $address_line_2 = ($_REQUEST['address_line_2']) ;       $address_line_2 = str_replace('_|_','&',$address_line_2) ;
            $address_line_3 = ($_REQUEST['address_line_3']) ;       $address_line_3 = str_replace('_|_','&',$address_line_3) ;
            $address_line_4 = ($_REQUEST['address_line_4']) ;       $address_line_4 = str_replace('_|_','&',$address_line_4) ;
            $address_line_5 = ($_REQUEST['address_line_5']) ;       $address_line_5 = str_replace('_|_','&',$address_line_5) ;
            $print_type     = ($_REQUEST['print_type']) ; 
            $unadj_adv_ind  = isset($_REQUEST['unadj_adv_ind'])  ?$_REQUEST['unadj_adv_ind']  :null;                
            $bill_cnt       = isset($_REQUEST['bill_cnt'])?$_REQUEST['bill_cnt']:null;
            // echo $print_type;die;   
            $date = date('d-m-Y');

            if ($start_date      != '') { $start_date_ymd = date_conv($start_date) ; } else { $start_date_ymd = '1901-01-01' ; } 
            if ($end_date        != '') { $end_date_ymd   = date_conv($end_date)   ; } else { $end_date_ymd   = date('Y-m-d') ; } 

            //dl page
            $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];
            $user_id        = session()->userId ;
            $curr_time      = $logdt_qry['current_time'];
            $curr_date      = $logdt_qry['current_dmydate'];
            $curr_day       = substr($curr_date,0,2) ;
            $curr_month     = substr($curr_date,3,2) ; 
            $curr_year      = substr($curr_date,6,4) ;
            //$temp_id        = "sinhaco_temp.".$user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);
            $temp_id        = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);
            $mytable  = $temp_id.'_activity_cost_statement';
            $this->temp_db->query("drop table if exists $mytable") ;
            $this->temp_db->query("create table if not exists $mytable(level_ind varchar(1), branch_code varchar(4), doc_no varchar(14), doc_date date, doc_narr varchar(200), doc_amount double(13,2), adj_amount double(13,2), bal_amount double(13,2))") ;
            
            //----- Insertion of Bill Record(s) selected in Previous Page 
            for ($i=1; $i<=$bill_cnt; $i++) 
            {  
                // echo $_POST['print_ind'.$i];die;   
                $print_ind = isset($_POST['print_ind'.$i]) ? $_POST['print_ind'.$i] : '';
                if ($print_ind == 'Y') 
                {
                    $level_ind     = '1' ;
                    $doc_no        = isset($_REQUEST['bill_number'.$i])?$_REQUEST['bill_number'.$i]:null;
                    $doc_date      = isset($_REQUEST['bill_date'.$i])?date_conv($_REQUEST['bill_date'.$i]):null;
                    $doc_narr      = isset($_REQUEST['bill_cause'.$i])?$_REQUEST['bill_cause'.$i]:null;
                    $doc_amount    = isset($_REQUEST['bill_amount'.$i])?$_REQUEST['bill_amount'.$i]:null;
                    $adj_amount    = '';
                    $bal_amount    = '';
                    $this->temp_db->query("insert into $mytable(level_ind, branch_code, doc_no, doc_date, doc_narr, doc_amount, adj_amount, bal_amount) 
                                values ('$level_ind', '$branch_code', '$doc_no', '$doc_date', '$doc_narr', '$doc_amount', '$adj_amount', '$bal_amount') ") ;
                }
            }
              
            //----- Insertion of Unadjusted Advance Record(s)
            if ($unadj_adv_ind == 'Y')
            {
                $unadjadv_sql = "insert into $mytable(level_ind, branch_code, doc_no, doc_date, doc_narr, doc_amount, adj_amount, bal_amount) 
                                   select '2','$branch_code',a.instrument_no,a.instrument_dt,ifnull(b.bank_name,' '),ifnull(a.gross_amount,0),ifnull(a.adjusted_amount,0),(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0) - ifnull(a.booked_amount,0))
                                     from advance_details a left outer join ledger_trans_hdr b on b.serial_no = a.ref_ledger_serial_no
                                    where a.advance_type         = 'R'
                                      and a.payee_payer_type     = 'C'
                                      and a.payee_payer_code  like '$client_code' 
                                      and (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0) - ifnull(a.booked_amount,0)) > 0 " ;
                $this->temp_db->query($unadjadv_sql)  ;
            } 

            //report page
            $branch_sql      = $this->db->query("select * from branch_master where branch_code = '$branch_code' ")->getRowArray();
            $branch_addr1    = $branch_sql['address_line_1'].', '.$branch_sql['city'].' - '.$branch_sql['pin_code'] ;
            $branch_addr2    = 'TEL : '.$branch_sql['phone_no'].'     FAX : '.$branch_sql['fax_no'] ;
            $branch_addr3    = 'E-Mail : '.$branch_sql['email_id'] ;
            $branch_pan_no   = 'PAN : '.$branch_sql['pan_no'] ;
            $service_tax_no  = $branch_sql['pan_no'].'SD001' ;
            $nature_of_serv  = 'LEGAL CONSULTANT`S SERVICE' ;
          
            //
            $client_qry     = $this->db->query("select * from client_master where client_code = '$client_code' ")->getRowArray() ;
            $client_name    = $client_qry['client_name'] ;
            //
            $attention_qry  = $this->db->query("select * from client_attention where attention_code = '$attention_code' ")->getRowArray() ;
            $attn_sex       = $attention_qry['sex'] ;   if($attn_sex == 'F') { $attn_desc = 'Madam,' ; } if($attn_sex == 'M') {  $attn_desc = 'Sir,' ; }
            $address_code   = $attention_qry['address_code'] ;
            $title          = $attention_qry['title'] ;
          //$attention_name = $title.'. '.stripslashes($attention_name) ;
            if($title != 'ORS') { $attention_name = $title.' '.stripslashes($attention_name) ;} 
            if($title == 'ORS') { $attention_name = stripslashes($attention_name) ;} 
          //$attention_name = $attn_addr.stripslashes($attention_name) ;

            //-------------------------------------------------------------
            $actycost_sql = "select * from $mytable order by level_ind, doc_date, doc_no " ;    
            $actycost_qry = $this->temp_db->query($actycost_sql)->getResultArray();
            // echo '<pre>';print_r($actycost_qry);die;
            $actycost_cnt = count($actycost_qry);
            
            $st_qry = $this->temp_db->query("select * from $mytable order by level_ind, doc_date, doc_no ")->getRowArray(); 
            $bill_dt = isset($st_qry['doc_date']) ? $st_qry['doc_date'] : '';
            //
            $x25thLogoYear   = get_parameter_value('20') ;
            $x25thLogoInd    = (substr($date,6,4) == $x25thLogoYear) ? 'Y' : 'N' ; 

            $params = [
                'x25thLogoInd' => $x25thLogoInd,
                'branch_addr1' => $branch_addr1,
                'branch_addr2' => $branch_addr2,
                'branch_addr3' => $branch_addr3,
                'branch_pan_no' => $branch_pan_no,
                'service_tax_no' => $service_tax_no,
                'nature_of_serv' => $nature_of_serv,
                'client_code' => $client_code,
                'client_name' => $client_name,
                'address_line_1' => $address_line_1,
                'address_line_2' => $address_line_2,
                'address_line_3' => $address_line_3,
                'address_line_4' => $address_line_4,
                'address_line_5' => $address_line_5,
                'attention_code' => $attention_code,
                'attn_desc' => $attn_desc,
                'attention_name' => $attention_name,
                'date' => $date,
                'print_type' => $print_type,
                'bill_dt' => $bill_dt,

            ];

            return view("pages/Billing/activity_cost_statement", compact("actycost_qry", "actycost_cnt", "params", "data"));
        }
        if($finsub=="" || $finsub!="fsub")
        {

            $requested_url = base_url($data['requested_url']);

            $display_id     = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

            $param_id       = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;

            $my_menuid      = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;

            $menu_id        = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

            $user_option    = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;

            $screen_ref     = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

            $index          = isset($_REQUEST['index'])?$_REQUEST['index']:null;

            $ord            = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;

            $pg             = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;

            $search_val     = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;

            $selemode       = isset($_REQUEST['selemode'])?$_REQUEST['selemode']:'Y';

            $date = date('d-m-Y') ;



                if ($selemode != 'Y')

                {

                    $branch_code    =  $data ;

                    $start_date     = $date ;

                    $end_date       = $date ;

                    $redv           = '';

                    $disv           = '';

                    $disb_proc      = '';

                    $disb_prin      = 'disabled';

                    $disb_rese      = '';

                    $disb_back      = '';

                    $bill_cnt       = 0 ;	

                }	

                else

                {

                    $redv           = 'readonly';

                    $disv           = 'disabled';

                    $disb_proc      = 'disabled';

                    $disb_prin      = '';

                    $disb_rese      = '';

                    $disb_back      = '';

                    //

                    $branch_code    = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null;

                    $start_date     = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:null;

                    $end_date       = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:null;

                    $unadj_adv_ind  = isset($_REQUEST['unadj_adv_ind'])?$_REQUEST['unadj_adv_ind']:null;

                    $client_code    = isset($_REQUEST['client_code'])?$_REQUEST['client_code']:null;

                    $client_name    = isset($_REQUEST['client_name'])?stripslashes($_REQUEST['client_name']):null;

                    $attention_code = isset($_REQUEST['attention_code'])?$_REQUEST['attention_code']:null;

                    $address_code = isset($_REQUEST['address_code'])?$_REQUEST['address_code']:null;

                    $attention_name = isset($_REQUEST['attention_name'])?stripslashes($_REQUEST['attention_name']):null;

                    $address_line_1 = isset($_REQUEST['address_line_1'])?stripslashes($_REQUEST['address_line_1']):null;

                    $address_line_2 = isset($_REQUEST['address_line_2'])?stripslashes($_REQUEST['address_line_2']):null;

                    $address_line_3 = isset($_REQUEST['address_line_3'])?stripslashes($_REQUEST['address_line_3']):null;

                    $address_line_4 = isset($_REQUEST['address_line_4'])?stripslashes($_REQUEST['address_line_4']):null;

                    $address_line_5 = isset($_REQUEST['address_line_5'])?stripslashes($_REQUEST['address_line_5']):null;

                    $print_type = isset($_REQUEST['print_type']) ? stripslashes($_REQUEST['print_type']) :null;

                    //

                    if ($start_date      != '') { $start_date_ymd = date_conv($start_date) ; } else { $start_date_ymd = '1901-01-01' ; } 

                    if ($end_date        != '') { $end_date_ymd   = date_conv($end_date)   ; } else { $end_date_ymd   = '2023-05-04'; } 

                    if ($client_code     == '') { $client_code    = '%' ; }

                    if ($attention_code  == '') { $attention_code = '%' ; }

                    if ($address_code    == '') { $address_code   = '%' ; }

                    //replace(bill_cause,'&','|') 

                    $user_id = session()->userId;

                    if($user_id != 'abhijit') {

                        $bill_sql       = "select concat(fin_year,'/',bill_no) bill_number, bill_date, matter_code, bill_cause, (ifnull(bill_amount_inpocket,0)+ifnull(bill_amount_outpocket,0)+ifnull(bill_amount_counsel,0)+ifnull(service_tax_amount,0)) bill_amount

                                            from bill_detail 

                                            where branch_code     like    '$branch_code'

                                            and bill_date       between '$start_date_ymd' and '$end_date_ymd' 

                                            and client_code     like    '$client_code' 

                                            and address_code    like    '$address_code' 

                                            and attention_code  like    '$attention_code' 

                                            and ifnull(cancel_ind,'X')    <>  'Y'

                                            and ifnull(part_full_ind,'P') <>  'F'    

                                            and ((ifnull(advance_amount_inpocket,0)+ifnull(advance_amount_outpocket,0)+ifnull(advance_amount_counsel,0)+ifnull(advance_amount_service_tax,0))

                                            +(ifnull(realise_amount_inpocket,0)+ifnull(realise_amount_outpocket,0)+ifnull(realise_amount_counsel,0)+ifnull(realise_amount_service_tax,0))) = 0 

                                            order by 2,1 ";	

                                            }

                                            

                    if($user_id == 'abhijit') {

                        $bill_sql       = "select concat(fin_year,'/',bill_no) bill_number, bill_date, matter_code, bill_cause, (ifnull(bill_amount_inpocket,0)+ifnull(bill_amount_outpocket,0)+ifnull(bill_amount_counsel,0)+ifnull(service_tax_amount,0)) bill_amount

                                            from bill_detail 

                                            where branch_code     like    '$branch_code'

                                            and bill_date       between '$start_date_ymd' and '$end_date_ymd' 

                                            and client_code     like    '$client_code' 

                                            and address_code    like    '$address_code' 

                                            and attention_code  like    '$attention_code' 

                                            and ifnull(cancel_ind,'X')    <>  'Y' ";	

                    }



                    $reports = $this->db->query($bill_sql)->getResultArray() ;

                    $bill_cnt = count($reports) ;

                    // echo '<pre>';print_r($params['bill_cnt']);die;

                    

                    if(empty($reports)) {

                        session()->setFlashdata('message', 'No Records Found !!');

                        return redirect()->to($requested_url);

                    }
                    $params = [
                        "branch_code" => $branch_code,   
                        "start_date" => $start_date,    
                        "end_date" => $end_date,      
                        "client_code" => $client_code,   
                        "attention_code" => $attention_code,
                        "attention_name" => $attention_name,
                        "address_line_1" => $address_line_1,
                        "address_line_2" => $address_line_2,
                        "address_line_3" => $address_line_3,
                        "address_line_4" => $address_line_4,
                        "address_line_5" => $address_line_5,
                        "bill_cnt" => $bill_cnt,
                        "unadj_adv_ind" => $unadj_adv_ind,
                        "print_type" => $print_type,
                        "client_name" => $client_name,
                    ];
                

                return view("pages/Billing/activity_cost_statement", compact("reports", "params", "data", "displayId"));
        }
        } 
        }   
        else {
            if ($user_option == null) {

                return view("pages/Billing/activity_cost_statement", compact("data", "displayId", "params"));
            }

            } 
    }
    public function bill_ledger(){  // use temp table that's why code not implemented//

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches(session()->userId);

    	$data['requested_url'] = $this->session->requested_end_menu_url;

        $fin_year = session()->financialYear;

        $curr_fyrsdt = '01-04-'.substr($fin_year,0,4);

        $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4220'] ;



        if($this->request->getMethod() == 'post') {

            $requested_url = base_url($data['requested_url']);
    
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
    
    
    
            $branch_code    = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null;
    
            $start_date     = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:null;
    
            $end_date       = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:null;
    
            $client_code    = isset($_REQUEST['client_code'])?$_REQUEST['client_code']:null;  if ($client_code == '') {$client_code = '%'; } 
    
            $client_name    = isset($_REQUEST['client_name'])?$_REQUEST['client_name']:null;  if ($client_name == '') {$client_name = 'ALL'; } 
    
            $client_name   = str_replace('_|_', '&', $client_name) ;
    
            $client_name   = str_replace('-|-',"'", $client_name) ;
    
            $matter_code    = isset($_REQUEST['matter_code'])?$_REQUEST['matter_code']:null;  if ($matter_code == '') {$matter_code = '%'; } 
    
            $matter_desc    = isset($_REQUEST['matter_desc'])?$_REQUEST['matter_desc']:null;  if ($matter_desc == '') {$matter_desc = 'ALL'; } 
    
            $report_desc    = "BILL LEDGER" ;
    
            $period_desc    = $start_date.' - '.$end_date ; 
            $output_type    = $_REQUEST['output_type'] ; 
    
    
            // 
    
            $branch_qry     = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code'")->getResultArray()[0];
    
            $branch_name    = $branch_qry['branch_name'];
    
    
    
            //---- Date Conversion (in yyyy-mm-dd format)
    
            $date_from = date_conv($start_date) ;
    
            $date_upto = date_conv($end_date);
    
            
    
            //---- Financial Year Calculation
    
            $fin_month  = substr($date_from,5,2);
    
            $fin_year   = substr($date_from,0,4);
    
            // echo '<pre>';print_r($fin_year);die;
    
            if($fin_month <= 3) { $fin_yr = ($fin_year - 1).'-'.$fin_year ; } else { $fin_yr = $fin_year.'-'.($fin_year+1) ; } 
    
            $fin_start_date = substr($fin_yr,0,4).'-04-01' ;
    
    
    
            //---- Temporary Table creation
    
            $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];
    
            $user_id        = session()->userId ;
    
            $curr_time      = $logdt_qry['current_time'];
    
            $curr_date      = $logdt_qry['current_dmydate'];
    
            $curr_day       = substr($curr_date,0,2) ;
    
            $curr_month     = substr($curr_date,3,2) ; 
    
            $curr_year      = substr($curr_date,6,4) ;
    
            $temp_id  = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);
    
            $mytable1 = $temp_id.'_bill_ledger_opbal';
    
            $mytable2 = $temp_id.'_bill_ledger_detail';
    
            $this->temp_db->query("DROP TABLE IF EXISTS $mytable1");
    
            $this->temp_db->query("DROP TABLE IF EXISTS $mytable2");
    
    
    
            $create_sql1 = "CREATE TABLE $mytable1(client_code  varchar(6)
    
    					   			         ,client_name  varchar(50)
    
    								         ,amount_dr    double(12,2)
    
    								         ,amount_cr    double(12,2)
    
    								         ,amount_def   double(12,2)
    
    										 ,ind          varchar(1)
    
    								         )";
    
            $this->temp_db->query($create_sql1); 
    
    
    
            $create_sql2 = "CREATE TABLE $mytable2(client_code   varchar(6)
    
                                    ,client_name varchar(50)
    
                                    ,daybook_code varchar(2)
    
                                    ,doc_type varchar(4)
    
                                    ,doc_date date
    
                                    ,doc_no varchar(20)
    
                                    ,instr_no varchar(20)
    
                                    ,instr_dt date
    
                                    ,narration varchar(200)
    
                                    ,amount_dr double(12,2)
    
                                    ,amount_cr double(12,2)
    
                                    ,amount_def double(12,2)
    
                                    ,serial_no int(8)
    
                                    ,ind varchar(1)
    
                                    )";
    
            $this->temp_db->query($create_sql2);
    
    
    
            $sinha_db_data = "select a.client_code, b.client_name, sum(ifnull(a.bill_amount_inpocket,0.00)+ifnull(a.bill_amount_outpocket,0.00)+ifnull(a.bill_amount_counsel,0.00)+ifnull(a.service_tax_amount,0.00)) as amount_sum, 0.00, 0.00, '1'
    
                        from bill_detail a, client_master b 
    
                    where a.branch_code      = '$branch_code'
    
                        and a.bill_date        < '$date_from'
    
                        and a.client_code   like '$client_code'
    
                        and a.matter_code   like '$matter_code'
    
                        and a.client_code      = b.client_code
    
                        and (a.cancel_ind      = '' or a.cancel_ind is null or a.cancel_ind = 'N')
    
                        group by a.client_code, b.client_name
    
                    union all
    
                    select a.client_code,b.client_name,(sum(ifnull(a.adjusted_amount,0.00))), 0.00, 0.00, '1'
    
                        from bill_realisation_detail a, client_master b, bill_realisation_header c, ledger_trans_hdr d 
    
                    where a.branch_code                = '$branch_code'
    
                            and a.client_code             like '$client_code'
    
                        and a.matter_code             like '$matter_code'
    
                        and a.ref_realisation_serial_no  = c.serial_no
    
                        and c.ref_ledger_serial_no       = d.serial_no
    
                        and d.doc_date                   < '$date_from'
    
                        and d.status_code                = 'C'
    
                        and a.client_code                = b.client_code
    
                        and a.adjusted_amount            > 0
    
                    group by a.client_code,b.client_name 
    
                    union all
    
                        select a.client_code, b.client_name, 0.00, sum(ifnull(a.realised_amount,0.00)), sum(ifnull(a.deficit_amount_inpocket,0)+ifnull(a.deficit_amount_outpocket,0)+ifnull(a.deficit_amount_counsel,0)+ifnull(a.deficit_amount_service_tax,0)), '1'
    
                        from bill_realisation_detail a, client_master b, bill_realisation_header c, ledger_trans_hdr d 
    
                    where a.branch_code                = '$branch_code' 
    
                        and a.client_code             like '$client_code'
    
                        and a.matter_code             like '$matter_code'
    
                        and a.ref_realisation_serial_no  = c.serial_no
    
                        and c.ref_ledger_serial_no       = d.serial_no
    
                        and d.doc_date                   < '$date_from'
    
                        and d.status_code                = 'C'
    
                        and a.client_code                = b.client_code
    
                        group by a.client_code, b.client_name
    
                    order by 1" ;
    
    
    
    
    
                    $data1 = $this->db->query($sinha_db_data)->getResultArray();
    
                    // echo '<pre>';print_r($data1);die;
    
                    foreach ($data1 as  $value) {
    
                            $client_code = $value['client_code'];
    
                            $client_name = $value['client_name'];
    
                            $amount_dr   = $value['amount_sum'];
    
                            $amount_cr   = $value['0.00'];
    
                            $amount_def  = $value['0.00'];
    
                            $ind         = $value['1'];
    
                            $insert_sql1 = "insert into $mytable1 (client_code,client_name,amount_dr,amount_cr,amount_def,ind) values ('$client_code','$client_name', '$amount_dr', '$amount_cr', '$amount_def', '$ind' )";
    
                        
    
                            $this->temp_db->query($insert_sql1);
    
                    }
    
    
    
            	$insert_sql2 = "insert into $mytable2
    
                           (client_code,client_name,daybook_code,doc_type,doc_date,doc_no,instr_no,instr_dt,narration,amount_dr,amount_cr,amount_def,serial_no,ind)
    
                       select 
    
    				        client_code,client_name,'','','$date_from','','','','Opening Balance',sum(ifnull(amount_dr,0.00)-ifnull(amount_cr,0.00)-ifnull(amount_def,0.00)),0.00, 0.00,'','1'
    
                         from $mytable1
    
                        group by client_code,client_name 
    
    				   having sum(ifnull(amount_dr,0.00)-ifnull(amount_cr,0.00)-ifnull(amount_def,0.00)) >= 0.00 
    
    				   union all 
    
                       select 
    
    				        client_code,client_name,'','','$date_from','','','','Opening Balance',0.00,abs(sum(ifnull(amount_dr,0.00)-ifnull(amount_cr,0.00)-ifnull(amount_def,0.00))), 0.00,'','1'
    
                         from $mytable1
    
                        group by client_code,client_name 
    
    				   having sum(ifnull(amount_dr,0.00)-ifnull(amount_cr,0.00)-ifnull(amount_def,0.00)) < 0.00 " ;
    
            	$this->temp_db->query($insert_sql2);
    
    
    
           		$sinha_db_data2 = "select a.client_code, b.client_name,'01','SB',a.bill_date,concat(a.fin_year,'/',a.bill_no) as doc_no,'','',a.bill_cause,(ifnull(a.bill_amount_inpocket,0.00)+ifnull(a.bill_amount_outpocket,0.00)+ifnull(a.bill_amount_counsel,0.00)+ifnull(a.service_tax_amount,0.00)) as bill_amount, 0.00, 0.00, a.serial_no, '2'
    
                        from bill_detail a, client_master b 
    
                    where a.branch_code       = '$branch_code'
    
                        and a.bill_date   between '$date_from'  and '$date_upto'
    
                        and a.client_code    like '$client_code'
    
                        and a.matter_code    like '$matter_code'
    
                        and a.client_code       = b.client_code
    
                        and (a.cancel_ind      = '' or a.cancel_ind is null or a.cancel_ind = 'N')
    
                    union all
    
                    select a.client_code,b.client_name,d.daybook_code,d.doc_type,d.doc_date,d.doc_no,d.instrument_no,d.instrument_dt,'ADJUSTMENT OF BIIL(S) AGAINST ADVANCE ..',(sum(ifnull(a.adjusted_amount,0.00))), 0.00, 0.00, a.ref_realisation_serial_no, '3'
    
                        from bill_realisation_detail a, client_master b, bill_realisation_header c, ledger_trans_hdr d 
    
                    where a.branch_code                = '$branch_code'
    
                        and a.client_code             like '$client_code'
    
                        and a.matter_code             like '$matter_code'
    
                        and a.ref_realisation_serial_no  = c.serial_no
    
                        and c.ref_ledger_serial_no       = d.serial_no
    
                        and d.doc_date             between '$date_from'  and '$date_upto'
    
                        and d.status_code                = 'C'
    
                        and a.client_code                = b.client_code
    
                        and a.adjusted_amount            > 0
    
                    group by a.client_code,b.client_name,d.daybook_code,d.doc_type,d.doc_date,d.doc_no,d.instrument_no,d.instrument_dt 
    
                    union all
    
                    select a.client_code,b.client_name,d.daybook_code,d.doc_type,d.doc_date,d.doc_no,d.instrument_no,d.instrument_dt,if(a.ref_bill_year='ON AC','ADVANCE ..',concat(if(d.doc_type='JV','ADJUSTMENT : ','COLLECTION : '),a.ref_bill_year,'/',a.ref_bill_no)),0.00,(ifnull(a.realised_amount,0.00)), (ifnull(a.deficit_amount_inpocket,0)+ifnull(a.deficit_amount_outpocket,0)+ifnull(a.deficit_amount_counsel,0)+ifnull(a.deficit_amount_service_tax,0)), a.ref_realisation_serial_no, '4'
    
                        from bill_realisation_detail a, client_master b, bill_realisation_header c, ledger_trans_hdr d 
    
                        where a.branch_code = '$branch_code'
    
                        and a.client_code like '$client_code'
    
                        and a.matter_code like '$matter_code'
    
                        and a.ref_realisation_serial_no = c.serial_no
    
                        and c.ref_ledger_serial_no = d.serial_no
    
                        and d.doc_date between '$date_from'  and '$date_upto'
    
                        and d.status_code = 'C'
    
                        and a.client_code = b.client_code
    
                    order by 1";
    
    
    
                    $data2 = $this->db->query($sinha_db_data2)->getResultArray();
    
                    // echo '<pre>';print_r($data2);die;
    
                    foreach ($data2 as  $value) {
    
                        $client_code = $value['client_code'];
    
                        $client_name = $value['client_name'];
    
                        $daybook_code   = $value['01'];
    
                        $doc_type   = $value['SB'];
    
                        $doc_date  = $value['bill_date'];
    
                        $doc_no = $value['doc_no'];
    
                        $instr_no = $value[''];
    
                        $instr_dt = $value[''];
    
                        $narration = $value['bill_cause'];
    
                        $amount_dr = $value['bill_amount'];
    
                        $amount_cr = $value['0.00'];
    
                        $amount_def = $value['0.00'];
    
                        $serial_no = $value['serial_no'];
    
                        $ind = $value['2'];
    
    
    
                        $insert_sql3  = "insert into $mytable2
    
                        (client_code,client_name,daybook_code,doc_type,doc_date,doc_no,instr_no,instr_dt,narration,amount_dr,amount_cr,amount_def,serial_no,ind) 
    
                        values ('$client_code','$client_name', '$daybook_code', '$doc_type', '$doc_date', '$doc_no', '$instr_no', '$instr_dt', '$narration', '$amount_dr', '$amount_cr', '$amount_def', '$serial_no', '$ind')";
    
                        //echo '<pre>';print_r($pendcnt_stmt);die;
    
                        $this->temp_db->query($insert_sql3);
    
                    }
    
    
            if($output_type == 'Report' || $output_type == 'Pdf') {
                $report_qry  = $this->temp_db->query("select * from $mytable2 order by client_code,doc_date,doc_no,ind ")->getResultArray();
        
                // echo '<pre>';print_r($report_qry);die;
        
                $report_cnt  = count($report_qry) ;
        
                $date = date('d-m-Y');
        
                
        
                if(empty($report_qry)) {
        
                    session()->setFlashdata('message', 'No Records Found !!');
        
                    return redirect()->to($requested_url);
        
                }
        
                $params = [
        
                    "report_cnt" => $report_cnt,
        
                    "report_desc" => $report_desc,
        
                    "branch_name" => $branch_name,
        
                    "date" => $date,
        
                    "period_desc" => $period_desc,
        
                    "client_name" => $client_name,
        
                    "period_desc" => $period_desc,
        
                    "requested_url" => $requested_url,
        
                    // "client_name" => $client_name,
        
                    // "client_name" => $client_name,
        
        
        
                ];
                    if ($output_type == 'Pdf') {
                        $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                        $reportHTML = view("pages/Billing/bill_ledger", compact("report_qry", "params", "data", "curr_fyrsdt", "displayId", "report_type"));
                        // echo '<pre>';print_r($reportHTML);die;
                        $dompdf->loadHtml($reportHTML);
                        $dompdf->setPaper('A4', 'landscape'); // portrait
                        $dompdf->render();
                        $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                    } else return view ("pages/Billing/bill_ledger", compact("report_qry", "params", "data", "curr_fyrsdt", "displayId"));
        
        
            } if($output_type == 'Excel') { 

                $c_opbal    = 0 ;
                $c_tdramt   = 0 ; 
                $c_tcramt   = 0 ;
                $c_tdefamt  = 0 ;
                $c_clbal    = 0 ;

                $excels  = $this->temp_db->query("select * from $mytable2 order by client_code,doc_date,doc_no,ind ")->getResultArray();
                // echo '<pre>';print_r($excels);die;
                $bill_cnt  = count($excels);

                try {
                    $excels[0];
                    if($bill_cnt == 0)  throw new \Exception('No Records Found !!');

                } catch (\Exception $e) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }
                $fileName = 'BILL_LEDGER-'.date('d-m-Y').'.xlsx';  
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $headings = ['Doc Date', 'Doc Number', 'Instrument Number', 'Instrument Date', 'Particulars', 'Debit', 'Credit', 'Deficit'];

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

                            if($excel['ind'] == '1') { 

                            $sheet->setCellValue('A' . $rows, date_conv($excel['doc_date']));
                            $sheet->setCellValue('E' . $rows, strtoupper($excel['narration']));
                            $sheet->setCellValue('F' . $rows, ($excel['amount_dr']  >= 0) ? number_format($excel['amount_dr'],2,'.','') : '');
                            $sheet->setCellValue('G' . $rows, ($excel['amount_cr']  >  0) ? number_format($excel['amount_cr'],2,'.','') : '');
                            
                            } else {
                                $sheet->setCellValue('A' . $rows, date_conv($excel['doc_date']));
                                $sheet->setCellValue('B' . $rows, $excel['doc_no']);
                                $sheet->setCellValue('C' . $rows, $excel['instr_no']);
                                $sheet->setCellValue('D' . $rows, isset($excel['instr_date']) ? ($excel['instr_date'] != '' && $excel['instr_date'] != '0000-00-00') ? date_conv($excel['instr_date']) : '' : '');
                                $sheet->setCellValue('E' . $rows, strtoupper($excel['narration']));
                                $sheet->setCellValue('F' . $rows, ($excel['amount_dr']  >= 0) ? number_format($excel['amount_dr'],2,'.','') : '');
                                $sheet->setCellValue('G' . $rows, ($excel['amount_cr']  >  0) ? number_format($excel['amount_cr'],2,'.','') : '');
                                $sheet->setCellValue('H' . $rows, ($excel['amount_def'] > 0) ? number_format($excel['amount_def'],2,'.','') : '');
                            }
                            // Apply border to the current row
                            $style = $sheet->getStyle('A' . $rows . ':H' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                            if ($excel['ind'] == '1') 
                            {
                            $c_opbal = $excel['amount_dr'] - $excel['amount_cr'] ;   
                            }
                            else
                            {
                            if($excel['amount_dr']  > 0) { $c_tdramt  += $excel['amount_dr'] ;  } 
                            if($excel['amount_cr']  > 0) { $c_tcramt  += $excel['amount_cr'] ;  }
                            if($excel['amount_def'] > 0) { $c_tdefamt += $excel['amount_def'] ; }
                            }

                            $rows++;
                        }
                        
                        $c_clbal = $c_opbal + $c_tdramt - $c_tcramt - $c_tdefamt ;

                        $sheet->setCellValue('E' . $rows, 'Total');
                        $sheet->setCellValue('F' . $rows, number_format($c_tdramt,2,'.',''));
                        $sheet->setCellValue('G' . $rows, number_format($c_tcramt,2,'.',''));
                        $sheet->setCellValue('H' . $rows, number_format($c_tdefamt,2,'.',''));

                        $style = $sheet->getStyle('A' . $rows . ':H' . $rows);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $rows++;

                        $sheet->setCellValue('E' . $rows, 'Closing Balance');
                        $sheet->setCellValue('F' . $rows, ($c_clbal >= 0) ? number_format($c_clbal,2,'.','') : '');
                        $sheet->setCellValue('G' . $rows, ($c_clbal <  0) ? number_format(abs($c_clbal),2,'.','') : '');
                        $style = $sheet->getStyle('A' . $rows . ':H' . $rows);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
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

            return view ("pages/Billing/bill_ledger", compact("data", "displayId", "curr_fyrsdt"));

        }

    }
    public function bill_print(){



        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

        $data = branches(session()->userId);

    	$data['requested_url'] = $this->session->requested_end_menu_url;

        $displayId   = ['client_help_id' => '4079', 'matter_help_id' => '4213'] ;



        if($this->request->getMethod() == 'post') {

        $requested_url = base_url($data['requested_url']);

        $display_id  = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

        $param_id    = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;

        $my_menuid   = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;

        $menu_id     = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

        $screen_ref  = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;

        $index       = isset($_REQUEST['index'])?$_REQUEST['index']:null;

        $ord         = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;

        $pg          = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;

        $search_val  = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;



        $client_matter = isset($_REQUEST['client_matter'])  ? $_REQUEST['client_matter'] : NULL;

        $range_from    = isset($_REQUEST['range_from'])     ? $_REQUEST['range_from']   : NULL;

        $range_to      = isset($_REQUEST['range_to'])       ? $_REQUEST['range_to']     : NULL;

        $input_code    = isset($_REQUEST['input_code'])     ? $_REQUEST['input_code']   : NULL;

        $input_range    = isset($_REQUEST['input_range'])   ? $_REQUEST['input_range']   : NULL;

        //$input_year    = isset($_REQUEST['input_year'])   ? $_REQUEST['input_year']   : NULL;

        $input_year    = '2009-2010';



        $input_stmt = ""; $stmt = ""; $input_year = "";

        switch($client_matter) {

            case 'Slected' : 

                $input_stmt = " bill_no in ('HM05','HP76','ID02','ID03','ID04','ID05','ID27','IF24','IF87','IF88','IF89','IG04','IG05','IG06','IG07',

                    'IG08','IG09','IG10','IG11','IG12','IG13','IG35','IG36','IG37','IG38','IG39','IG62','IG63','IG64','IG65','IJ45','IJ46','IJ47','IJ48',

                    'IJ49','IN34','IO90','IO91','IP52','JB69','JC19','JC62','JC89','JC90','JG30','JG31','JG32','JG33','JG34','JG35','JG36','JG37','JG43',

                    'JG44','JG45','JG46','JG47','JG48','JG49','JG50','JG51','JG52','JG53','JI09','JI33','JI34','JI35','JI47','JI48','JI49','JI50','JI76',

                    'JI86','JM59','JM76','JM77','JM78','JM79','JR71','JR72','JR73','JR74','JR81','JR82','JR83','JR84','JR85')" ;



                $stmt = "select serial_no,concat(fin_year,'/',bill_no) bill_number,date_format(bill_date,'%d-%m-%Y') bill_date,client_code,matter_code

                    from bill_detail    

                    where " . $input_stmt . " and fin_year = " . $input_year . " order by serial_no" ;

                break;



            case 'Range' : 

                $input_stmt = " concat(fin_year,'/',bill_no) >= '".$range_from."' and concat(fin_year,'/',bill_no) <= '".$range_to."' " ;



                $stmt = "select serial_no,concat(fin_year,'/',bill_no) bill_number,date_format(bill_date,'%d-%m-%Y') bill_date,client_code,matter_code

                    from bill_detail    

                    where " . $input_stmt . " order by serial_no" ;

                break;   



            case 'Client' : 

                $input_stmt = " client_code = '".$input_code."'" ;



                $stmt = "select serial_no,concat(fin_year,'/',bill_no) bill_number,date_format(bill_date,'%d-%m-%Y') bill_date,client_code,matter_code

                    from bill_detail    

                    where " . $input_stmt . " order by serial_no" ;

                break; 

                

            case 'Matter' : 

                $input_stmt = " matter_code = '".$input_code."'" ;



                $stmt = "select serial_no,concat(fin_year,'/',bill_no) bill_number,date_format(bill_date,'%d-%m-%Y') bill_date,client_code,matter_code

                    from bill_detail    

                    where " . $input_stmt . " order by serial_no" ;

                break; 

            }



            // echo '<pre>';print_r($stmt);die;

            $reports = $this->db->query($stmt)->getResultArray() ;

            $params['bill_cnt'] = count($reports) ;



            foreach( $reports as $report){

                $cl_res = $this->db->query("select client_name from client_master where client_code = '".$report['client_code']."'")->getResultArray()[0];

                $params['client_name'] = $cl_res['client_name'];

    

                $mt_res = $this->db->query("select concat(matter_desc1,matter_desc2) matter_name from fileinfo_header where matter_code = '".$report['matter_code']."'")->getResultArray();

                try {

                    $mt_res = $mt_res[0];

                    $params['matter_name'] = $mt_res['matter_name'];

                    

                } catch (\Exception $e) {

                    $params['matter_name'] = '';

                }

            }



            if(empty($reports)) {

            	session()->setFlashdata('message', 'No Records Found !!');

            	return redirect()->to($requested_url);

        	}



        return view("pages/Billing/bill_print", compact("reports", "params", "displayId", "data"));



        }else{



            return view ("pages/Billing/bill_print", compact("data", "displayId"));



        }

    }
    //only need excel pattern//
    public function excel_bill_list(){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

      	$data = branches(session()->userId);

    	$data['requested_url'] = $this->session->requested_end_menu_url;

        $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4220', 'court_help_id' => '4221', 'initial_help_id' => '4191'] ;



        if($this->request->getMethod() == 'post') {

            $requested_url = base_url($data['requested_url']);
    
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
    
    
    
            //$ason_date       = $_REQUEST['ason_date'] ;
    
            $branch_code     = $_REQUEST['branch_code'] ;
    
            $start_date      = $_REQUEST['start_date'] ;      if($start_date != '')   { $start_date_ymd = date_conv($start_date); } else { $start_date_ymd = '1901-01-01'; }
    
            $end_date        = $_REQUEST['end_date'] ;        if($end_date   != '')   { $end_date_ymd   = date_conv($end_date);   } else { $end_date_ymd   = date('Y-m-d') ; }
    
            $court_code      = $_REQUEST['court_code'] ;      if(empty($court_code))  { $court_code   = '%' ; }
    
            $court_name      = $_REQUEST['court_name'] ;
    
            $client_code     = $_REQUEST['client_code'] ;     if(empty($client_code)) { $client_code  = '%' ; }
    
            $client_name     = $_REQUEST['client_name'] ;
    
            $matter_code     = $_REQUEST['matter_code'] ;     if(empty($matter_code)) { $matter_code  = '%' ; }
    
            $matter_desc     = $_REQUEST['matter_desc'] ;
    
            $initial_code    = $_REQUEST['initial_code'] ;    if(empty($initial_code)){ $initial_code = '%' ; }
    
            $initial_name    = $_REQUEST['initial_name'] ;
    
            $output_type     = $_REQUEST['output_type'] ;
    
            $billfor_ind     = isset($_REQUEST['billfor_ind']) ? $_REQUEST['billfor_ind'] : '' ;
    
            $billfor_ind     = 'N' ;
            $activity_date     = isset($_REQUEST['activity_date']) ? $_REQUEST['activity_date'] : '' ;
            $judge_name     = isset($_REQUEST['judge_name']) ? $_REQUEST['judge_name'] : '' ;
            $prev_fixed_for     = isset($_REQUEST['prev_fixed_for']) ? $_REQUEST['prev_fixed_for'] : '' ;
            $prev_date     = isset($_REQUEST['prev_date']) ? $_REQUEST['prev_date'] : '' ;
            $next_fixed_for     = isset($_REQUEST['next_fixed_for']) ? $_REQUEST['next_fixed_for'] : '' ;
            $next_date     = isset($_REQUEST['next_date']) ? $_REQUEST['next_date'] : '' ;
            $remarks     = isset($_REQUEST['remarks']) ? $_REQUEST['remarks'] : '' ;
            
    
    
    
            if($start_date    == '' ) {$period_desc = "UPTO ".$end_date ;} else {$period_desc = $start_date.' - '.$end_date ;}
    
    
    
            // $report_seqn     = $_REQUEST['report_seqn'] ;
    
            // $report_type     = $_REQUEST['report_type'] ;
    
            //$branch_name     = getBranchName($branch_code,$link) ;
    
            //
    
            if ($start_date == '') { $period_desc = 'UPTO '.$end_date ; } else { $period_desc = $start_date.' - '.$end_date ; } 
    
            //
    
            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0] ;
    
            $branch_name   = $branch_qry['branch_name'] ;
    
            if($output_type == 'Report' || $output_type == 'Pdf') { 
        
              $case_sql="select *,concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.matter_code,ifnull(a.bill_amount_inpocket, 0) ipamt,ifnull(a.bill_amount_outpocket,0) opamt,ifnull(a.bill_amount_counsel,  0) cnamt,ifnull(a.service_tax_amount,   0) stamt,(ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt,
    
                (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
    
                (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,
    
                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,
    
                ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,d.code_desc court_name,
                b.client_name,c.matter_desc1,c.matter_desc2,c.reference_desc,c.requisition_no,c.date_of_filing,c.court_code,c.initial_code,d.code_desc court_name,f.code_desc case_type,c.case_no,c.case_year,g.name complainant_name
    
                    FROM 
                        bill_detail a
                    LEFT OUTER JOIN 
                        client_master b ON a.client_code = b.client_code
                    LEFT OUTER JOIN  
                        fileinfo_header c ON a.matter_code = c.matter_code
                    LEFT OUTER JOIN  
                        code_master d ON c.court_code = d.code_code 
                    LEFT OUTER JOIN  
                        code_master f ON c.case_type_code = f.code_code	
                    LEFT OUTER JOIN  
                        fileinfo_details g ON a.matter_code = g.matter_code
                    WHERE  a.branch_code like '$branch_code'
    
                    and a.client_code like '$client_code'
    
                    and a.matter_code like '$matter_code'
    
                    and ifnull(c.initial_code,'N') like '$initial_code' 
    
                    and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 
    
                    and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
    
                    and c.court_code like '$court_code'
    
                    and ifnull(g.matter_code,'N') = a.matter_code and g.record_code = '10'
    
                    and c.court_code = d.code_code and d.type_code = '001' 
    
                    and c.case_type_code = f.code_code and f.type_code = '006'
    
                    and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                    GROUP BY 
                        bill_number
                    ORDER BY 
                        a.bill_date ASC limit 50";  

                 
    
            // $case_sql = "select concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.matter_code,ifnull(a.bill_amount_inpocket, 0) ipamt,ifnull(a.bill_amount_outpocket,0) opamt,ifnull(a.bill_amount_counsel,  0) cnamt,ifnull(a.service_tax_amount,   0) stamt,(ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt,
    
            //     (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
    
            //      (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,
    
            //      (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,
    
            //      ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,
    
            //      e.activity_date, e.judge_name, e.prev_fixed_for, e.prev_date, e.next_fixed_for, e.next_date, e.remarks, b.client_name,c.matter_desc1,c.matter_desc2,c.reference_desc,c.requisition_no,c.date_of_filing,c.court_code,c.initial_code,d.code_desc court_name,f.code_desc case_type,c.case_no,c.case_year,g.name complainant_name
    
            //      from bill_detail a, client_master b, fileinfo_header c, code_master d, case_header e, code_master f, fileinfo_details g					
    
            //      where a.branch_code like '$branch_code'
    
            //      and a.client_code like '$client_code'
    
            //      and a.matter_code like '$matter_code'
    
            //      and e.client_code = b.client_code
    
            //      and a.matter_code = e.matter_code
    
            //      and ifnull(c.initial_code,'N') like '$initial_code' 
    
            //      and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 
    
            //      and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
    
            //      and a.client_code = b.client_code
    
            //      and a.matter_code = c.matter_code
    
            //      and c.court_code like '$court_code'
    
            //      and ifnull(g.matter_code,'N') = a.matter_code and g.record_code = '10'
    
            //      and c.court_code = d.code_code and d.type_code = '001' 
    
            //      and c.case_type_code = f.code_code and f.type_code = '006'
    
            //      and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
    
            //      group by bill_number  
    
            //      order by e.activity_date desc, a.bill_date asc  " ; 
    
                            
    
    
            $reports  = $this->db->query($case_sql)->getResultArray();
            //$reports2  = $this->db->query($case_sql2)->getResultArray();
            //  echo '<pre>';print_r($reports);die;

             $new_reports = [];
            foreach ($reports as $key => $value) {
            // for($key = 0; $key < count($reports))
                $matterCode = $value['matter_code'];
                $case_sql2="select 
                    e.activity_date, e.judge_name, e.prev_fixed_for, e.prev_date, e.next_fixed_for, e.next_date, e.remarks
                FROM case_header e
                WHERE e.client_code like '$client_code' AND matter_code ='".$matterCode."'";
                $reports2 = $this->db->query($case_sql2)->getResultArray();
                // echo "<pre>"; print_r($reports2); die;

                foreach ($reports2 as $index => $subArray) {
                    array_push($new_reports, array_merge($reports[$key], $subArray) );
                }
                
                //$reports2 = $this->db->query($case_sql2)->getResult();
                if($key == 50) {
                    break; //echo "<pre>"; print_r($reports); die;
                }
                // $reports22=array_merge($reports);
            }
            $reports = $new_reports;
            // echo "<pre>"; print_r($new_reports); die;

        //      $reports22[]="";
        //      foreach ($reports as $key => $value) {
        //      $matterCode[]=$value['matter_code'];
        //      $case_sql2="select 
        //         e.activity_date, e.judge_name, e.prev_fixed_for, e.prev_date, e.next_fixed_for, e.next_date, e.remarks
        //     FROM case_header e
        //     WHERE e.client_code like '$client_code' AND matter_code ='".$value['matter_code']."'";
        //     $reports2 = $this->db->query($case_sql2)->getResultArray();
        //     //$reports2 = $this->db->query($case_sql2)->getResult();
        //    // $reports22=array_merge($reports);
        //      }
             
            // echo '<pre>';print_r($ar);die;
            //  $matter=implode(",",$matterCode);
            // $case_sql2="select 
            //              e.activity_date, e.judge_name, e.prev_fixed_for, e.prev_date, e.next_fixed_for, e.next_date, e.remarks
            //          FROM case_header e
            //          WHERE e.client_code like '$client_code' AND matter_code in ($matter)   
    
            //          order by e.activity_date desc ";
            //echo $case_sql2;die;
            //echo '<pre>';print_r($reports2);die;
            $case_cnt  = count($reports);
    
            $date = date('d-m-Y');
    
    
    
            if(empty($reports)) {
    
                session()->setFlashdata('message', 'No Records Found !!');
    
                return redirect()->to($requested_url);
    
            }
    
        
    
                $params = [
    
                    "branch_name" => $branch_name,
    
                    // "report_desc" => $report_desc,
    
                    "case_cnt" => $case_cnt,
    
                    "period_desc" => $period_desc,
    
                    "client_code" => $client_code,
    
                    "client_name" => $client_name,
    
                    "initial_code" => $initial_code,
    
                    "initial_name" => $initial_name,
    
                    "matter_code" => $matter_code,
    
                    "matter_desc" => $matter_desc,
    
                    "court_code" => $court_code,
    
                    "court_name" => $court_name,
    
                    "billfor_ind" => $billfor_ind,
    
                    //"report_seqn" => $report_seqn,
    
                    //"report_type" => $report_type,
    
                    "date" => $date,
    
                    "requested_url" => $requested_url,

    
                ];
                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/Billing/excel_bill_list", compact("reports", "params", "report_type"));
                    // echo '<pre>';print_r($reportHTML);die;
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/Billing/excel_bill_list", compact("reports", "params"));
                    
            } 
            // if($output_type == 'Excel') { 

            //     $bill_sql = "select concat(a.fin_year,'/',a.bill_no) bill_number, a.bill_date, a.client_code, a.matter_code,
			// 			 ifnull(a.bill_amount_inpocket, 0) ipamt, ifnull(a.bill_amount_outpocket,0) opamt, ifnull(a.bill_amount_counsel,  0) cnamt, ifnull(a.service_tax_amount,   0) stamt,
			// 			 (ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt,
            //              (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
            //              (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,
            //              (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,
            //              ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,
            //              e.activity_date, e.judge_name, e.prev_fixed_for, e.prev_date, e.next_fixed_for, e.next_date, e.remarks, b.client_name,c.matter_desc1,c.matter_desc2,c.reference_desc,c.requisition_no,c.date_of_filing,c.court_code,c.initial_code,d.code_desc court_name,f.code_desc case_type,c.case_no,c.case_year,g.name complainant_name
            //         from bill_detail a, client_master b, fileinfo_header c, code_master d, case_header e, code_master f, fileinfo_details g					
            //         where a.branch_code like '$branch_code'
            //         and a.client_code like '$client_code'
            //         and a.matter_code like '$matter_code'
            //         and e.client_code = b.client_code
            //         and a.matter_code = e.matter_code
            //         and ifnull(c.initial_code,'N') like '$initial_code' 
            //         and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 
            //         and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
            //         and a.client_code = b.client_code
            //         and a.matter_code = c.matter_code
            //         and c.court_code like '$court_code'
            //         and ifnull(g.matter_code,'N') = a.matter_code and g.record_code = '10'
            //         and c.court_code = d.code_code and d.type_code = '001' 
            //         and c.case_type_code = f.code_code and f.type_code = '006'
            //         and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
			// 	    group by bill_number  
            //         order by e.activity_date desc, a.bill_date asc" ;
                    
            //     $excels  = $this->db->query($bill_sql)->getResultArray() ;
            //     // echo '<pre>';print_r($excels);die;
            //     $bill_cnt  = count($excels);

            //     try {
            //         $excels[0];
            //         if($bill_cnt == 0)  throw new \Exception('No Records Found !!');

            //     } catch (\Exception $e) {
            //         session()->setFlashdata('message', 'No Records Found !!');
            //         return redirect()->to($this->requested_url());
            //     }
            //     $fileName = 'EXCEL_BILL_LIST-'.date('d-m-Y').'.xlsx';  
            //     $spreadsheet = new Spreadsheet();
            //     $sheet = $spreadsheet->getActiveSheet();

            //     $headings = ['Bill No', 'Bill Date', 'MATTER CODE', 'CASE NO', 'PROPOSAL NO', 'REQUISITION NO', 'CASE FILING DATE', 'COMPLAINANT NAME', 'ADVOCATE NAME', 'ACCUSED', 'COURT NO', 'SERVICE MONTH', 'LDOH', 'NDOH', 'STATUS', 'INSTRUMENT DATE', 'INSTRUMENT TYPE', 'PREVIOUS ACTIVITY', 'FEE', 'EXP', 'COURT FEE', 'TOTAL', 'CASE REMARKS'];

            //             // Loop through the headings and set the formatting
            //             $column = 'A';
            //             foreach ($headings as $heading) {
            //                 $cell = $column . '1';

            //                 // Set the cell value
            //                 $sheet->setCellValue($cell, $heading);

            //                 // Apply formatting
            //                 $style = $sheet->getStyle($cell);
            //                 $style->getFont()->setBold(true);
            //                 $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            //                 $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
            //                 // Add borders
            //                 $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            //                 $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            //                 $style->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            //                 $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            //                 // Move to the next column
            //                 ++$column;
            //             }
                        
            //             $rows = 2;
            //             foreach ($excels as $excel){

            //                 $balance_amount = $excel['billed_amount'] - $excel['realised_amount'] ;

            //                 $sheet->setCellValue('A' . $rows, $excel['bill_number']);
            //                 $sheet->setCellValue('B' . $rows, date_conv($excel['bill_date']));
            //                 $sheet->setCellValue('C' . $rows, strtoupper($excel['matter_code']));
            //                 $sheet->setCellValue('D' . $rows, strtoupper($excel['case_type'].'/'.$excel['case_no'].'/'.substr($excel['case_year'],2,4)));
            //                 $sheet->setCellValue('E' . $rows, "'".strtoupper($excel['reference_desc']));
            //                 $sheet->setCellValue('F' . $rows, strtoupper($excel['requisition_no']));
            //                 $sheet->setCellValue('G' . $rows, date_conv($excel['date_of_filing']));
            //                 $sheet->setCellValue('H' . $rows, strtoupper($excel['complainant_name']));
            //                 $sheet->setCellValue('I' . $rows, strtoupper('SINHA & COMPANY'));
            //                 $sheet->setCellValue('J' . $rows, strtoupper($excel['matter_desc2']));
            //                 $sheet->setCellValue('K' . $rows, strtoupper($excel['judge_name']));
            //                 $sheet->setCellValue('L' . $rows, isset($excel['service_month']) ? strtoupper($excel['service_month']) : '');
            //                 $sheet->setCellValue('M' . $rows, date_conv($excel['activity_date']));
            //                 $sheet->setCellValue('N' . $rows, date_conv($excel['next_date']));
            //                 $sheet->setCellValue('O' . $rows, strtoupper($excel['next_fixed_for']));
            //                 $sheet->setCellValue('P' . $rows, '');
            //                 $sheet->setCellValue('Q' . $rows, '');
            //                 $sheet->setCellValue('R' . $rows, strtoupper($excel['prev_fixed_for']));
            //                 $sheet->setCellValue('S' . $rows, ($excel['ipamt'] > 0) ? number_format($excel['ipamt'], 2,'.','') : '' );
            //                 $sheet->setCellValue('T' . $rows, ($excel['opamt'] > 0) ? number_format($excel['opamt'], 2,'.','') : '');
            //                 $sheet->setCellValue('U' . $rows, ($excel['cnamt'] > 0) ? number_format($excel['cnamt'], 2,'.','') : '');
            //                 $sheet->setCellValue('V' . $rows, ($excel['totamt'] > 0) ? number_format($excel['totamt'], 2,'.','') : '');
            //                 $sheet->setCellValue('W' . $rows, strtoupper($excel['remarks']));
                            
            //                 // Apply border to the current row
            //                 $style = $sheet->getStyle('A' . $rows . ':W' . $rows);
            //                 $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            //                 $rows++;
            //             }
            //             $writer = new Xlsx($spreadsheet);
            //             $writer->save($fileName);
            //             header('Content-Type: application/vnd.ms-excel');
            //             header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
            //             header('Expires: 0');
            //             header('Cache-control: must-revalidate');
            //             header('Pragma: public');
            //             header('Content-Length:'.filesize($fileName));
            //             flush();
            //             readfile($fileName); 
            // }

        }else{

            return view("pages/Billing/excel_bill_list", compact("data", "displayId"));



        }

    }
    public function bill_register_court_initial(){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

       	$data = branches(session()->userId);

    	$data['requested_url'] = $this->session->requested_end_menu_url;

        $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4220', 'court_help_id' => '4221', 'initial_help_id' => '4191'] ;



        if($this->request->getMethod() == 'post') {

        $requested_url = base_url($data['requested_url']);

        $display_id    = isset($_REQUEST['display_id'])  ?$_REQUEST['display_id']  :null;

        $param_id      = isset($_REQUEST['param_id'])    ?$_REQUEST['param_id']    :null;

        $my_menuid     = isset($_REQUEST['my_menuid'])   ?$_REQUEST['my_menuid']   :null;

        $menu_id       = isset($_REQUEST['menu_id'])     ?$_REQUEST['menu_id']     :null;	

        $user_option   = isset($_REQUEST['user_option']) ?$_REQUEST['user_option'] :null;

        $screen_ref    = isset($_REQUEST['screen_ref'])  ?$_REQUEST['screen_ref']  :null;

        $index         = isset($_REQUEST['index'])       ?$_REQUEST['index']       :null;

        $ord           = isset($_REQUEST['ord'])         ?$_REQUEST['ord']         :null;

        $pg            = isset($_REQUEST['pg'])          ?$_REQUEST['pg']          :null;

        $search_val    = isset($_REQUEST['search_val'])  ?$_REQUEST['search_val']  :null;



        $ason_date       = $_REQUEST['ason_date'] ;

        $branch_code     = $_REQUEST['branch_code'] ;

        $start_date      = $_REQUEST['start_date'] ;      if($start_date != '')   { $start_date_ymd = date_conv($start_date); } else { $start_date_ymd = '1901-01-01'; }

        $end_date        = $_REQUEST['end_date'] ;        if($end_date   != '')   { $end_date_ymd   = date_conv($end_date);   } else { $end_date_ymd   = date('Y-m-d') ; }

        $court_code      = $_REQUEST['court_code'] ;      if(empty($court_code))  { $court_code   = '%' ; }

        $court_name      = $_REQUEST['court_name'] ;

        $client_code     = $_REQUEST['client_code'] ;     if(empty($client_code)) { $client_code  = '%' ; }

        $client_name     = $_REQUEST['client_name'] ;     $client_name = str_replace('_|_','&',$client_name) ;  $client_name = str_replace('-|-',"'",$client_name) ;

        $matter_code     = $_REQUEST['matter_code'] ;     if(empty($matter_code)) { $matter_code  = '%' ; }

        $matter_desc     = $_REQUEST['matter_desc'] ;

        $initial_code    = $_REQUEST['initial_code'] ;    if(empty($initial_code)) { $initial_code  = '%' ; }  

        $billfor_ind     = $_REQUEST['billfor_ind'] ;

        $report_seqn     = $_REQUEST['report_seqn'] ;

        $report_type     = $_REQUEST['report_type'] ;

        $output_type     = $_REQUEST['output_type'] ;
 
        $branch_qry      = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0];

        $branch_name   	 = $branch_qry["branch_name"] ;

        //$get_financial_year = session()->financialYear ;

        //

        $xMonStartYmd    = substr($start_date_ymd,0,7).'-01' ;

        $xMonEndYmd      = $end_date_ymd ;

        $xFinStartYmd    = substr(get_fin_year($start_date_ymd),0,4).'-04-01' ; //$global_curr_finyr_fymddate  ;

        $xFinEndYmd      = $end_date_ymd ;

        $xCalStartYmd    = substr($end_date_ymd,0,4).'-01-01'  ;

        $xCalEndYmd      = $end_date_ymd ;

        // 

        if($start_date == '')  {$period_desc = "UPTO ".$end_date ;} else {$period_desc = $start_date.' - '.$end_date ;}

      

        if($court_code  == '%') { $court_heading   = 'COURT : ALL'  ; } else { $court_heading   = 'COURT : SELECTIVE'  ; }

        if($client_code == '%') { $client_heading  = 'CLIENT : ALL' ; } else { $client_heading  = 'CLIENT : SELECTIVE' ; }

        if($matter_code == '%') { $matter_heading  = 'MATTER : ALL' ; } else { $matter_heading  = 'MATTER : SELECTIVE' ; }

        if($client_code == '%') { $client_name     = 'ALL' ;          } else { $client_name     = $client_name ; }

      

        $report_sub_desc = '[ '.$court_heading.' / '.$client_heading.' / '.$matter_heading.' ]' ;

      if($output_type == 'Report' || $output_type == 'Pdf') {

        $month_sql = ''; $finyr_sql = ''; $calyr_sql = ''; $bill_sql = '';  

        $params = []; 

        switch($report_type) {

            case 'S' :

                //---- Court-wise Summary

                if($report_seqn == 'I'){ 

                    $report_desc      = "BILL REGISTER : COURT/CLIENT/INITIAL/MATTER-WISE (SUMMARY)" ;



                    $bill_sql   = "select c.court_code,d.code_desc court_name,sum(ifnull(a.bill_amount_inpocket, 0)) ipamt,sum(ifnull(a.bill_amount_outpocket,0)) opamt,sum(ifnull(a.bill_amount_counsel,  0)) cnamt,sum(ifnull(a.service_tax_amount,   0)) stamt,sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) colamt,sum(ifnull(a.deficit_amount_inpocket, 0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) defamt

                            from bill_detail a, fileinfo_header c, code_master d

                            where a.branch_code like '$branch_code'

                            and a.client_code like '$client_code'

                            and a.matter_code like '$matter_code'

                            and a.initial_code like '$initial_code'

                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                            and a.matter_code = c.matter_code

                            and c.court_code like '$court_code'

                            and c.court_code = d.code_code 

                            and d.type_code = '001' 

                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                            group by c.court_code,d.code_desc 

                            order by d.code_desc " ; 

                

                    //---- Monthly Summary

                    $month_sql   = "select sum(ifnull(a.bill_amount_inpocket, 0)) ipamt,sum(ifnull(a.bill_amount_outpocket,0)) opamt,sum(ifnull(a.bill_amount_counsel,  0)) cnamt,sum(ifnull(a.service_tax_amount,   0)) stamt,sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) colamt,sum(ifnull(a.deficit_amount_inpocket, 0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) defamt

                            from bill_detail a, fileinfo_header c, code_master d

                            where a.branch_code like '$branch_code'

                            and a.client_code like '$client_code'

                            and a.matter_code like '$matter_code'

                            and a.initial_code like '$initial_code'

                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                            and a.bill_date between '$xMonStartYmd' and '$xMonEndYmd'

                            and a.matter_code = c.matter_code

                            and c.court_code like '$court_code'

                            and c.court_code = d.code_code 

                            and d.type_code = '001' 

                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '') " ; 

                    $month_row = $this->db->query($month_sql)->getResultArray()[0] ;

                    $month_ipamt   = $month_row['ipamt'] ;

                    $month_opamt   = $month_row['opamt'] ;

                    $month_cnamt   = $month_row['cnamt'] ;

                    $month_stamt   = $month_row['stamt'] ;

                    $month_totamt  = $month_row['totamt'] ;

                    $month_colamt  = $month_row['colamt'] ;

                    $month_defamt  = $month_row['defamt'] ;

                    $month_balamt  = ($month_row['totamt'] - $month_row['colamt'] - $month_row['defamt']);

                    //---- Fin Year Summary

                    $finyr_sql   = "select sum(ifnull(a.bill_amount_inpocket, 0)) ipamt,sum(ifnull(a.bill_amount_outpocket,0)) opamt,sum(ifnull(a.bill_amount_counsel,  0)) cnamt,sum(ifnull(a.service_tax_amount,   0)) stamt,sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) colamt,sum(ifnull(a.deficit_amount_inpocket, 0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) defamt

                            from bill_detail a, fileinfo_header c, code_master d

                            where a.branch_code like '$branch_code'

                            and a.client_code like '$client_code'

                            and a.matter_code like '$matter_code'

                            and a.initial_code like '$initial_code'

                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                            and a.bill_date between '$xFinStartYmd' and '$xFinEndYmd'

                            and a.matter_code = c.matter_code

                            and c.court_code like '$court_code'

                            and c.court_code = d.code_code 

                            and d.type_code = '001' 

                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '') " ; 

                    $finyr_row = $this->db->query($finyr_sql)->getResultArray()[0] ;

                    $finyr_ipamt   = $finyr_row['ipamt'] ;

                    $finyr_opamt   = $finyr_row['opamt'] ;

                    $finyr_cnamt   = $finyr_row['cnamt'] ;

                    $finyr_stamt   = $finyr_row['stamt'] ;

                    $finyr_totamt  = $finyr_row['totamt'] ;

                    $finyr_colamt  = $finyr_row['colamt'] ;

                    $finyr_defamt  = $finyr_row['defamt'] ;

                    $finyr_balamt  = ($finyr_row['totamt'] - $finyr_row['colamt'] - $finyr_row['defamt']);

                    //---- Calendar Year Summary

                    $calyr_sql   = "select sum(ifnull(a.bill_amount_inpocket, 0)) ipamt,sum(ifnull(a.bill_amount_outpocket,0)) opamt,sum(ifnull(a.bill_amount_counsel,  0)) cnamt,sum(ifnull(a.service_tax_amount,   0)) stamt,sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) colamt,sum(ifnull(a.deficit_amount_inpocket, 0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) defamt

                            from bill_detail a, fileinfo_header c, code_master d

                            where a.branch_code like '$branch_code'

                            and a.client_code like '$client_code'

                            and a.matter_code like '$matter_code'

                            and a.initial_code like '$initial_code'

                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                            and a.bill_date between '$xCalStartYmd' and '$xCalEndYmd'

                            and a.matter_code = c.matter_code

                            and c.court_code like '$court_code'

                            and c.court_code = d.code_code 

                            and d.type_code = '001' 

                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '') " ; 

                    $calyr_row = $this->db->query($calyr_sql)->getResultArray()[0] ;

                    $calyr_ipamt   = isset($calyr_row['ipamt']) ? $calyr_row['ipamt'] : 0 ;

                    $calyr_opamt   = isset($calyr_row['opamt']) ? $calyr_row['opamt'] : 0 ;

                    $calyr_cnamt   = isset($calyr_row['cnamt']) ? $calyr_row['cnamt'] : 0 ;

                    $calyr_stamt   = isset($calyr_row['stamt']) ? $calyr_row['stamt'] : 0 ;

                    $calyr_totamt  = isset($calyr_row['totamt']) ? $calyr_row['totamt'] : 0 ;

                    $calyr_colamt  = isset($calyr_row['colamt']) ? $calyr_row['colamt'] : 0 ;

                    $calyr_defamt  = isset($calyr_row['defamt']) ? $calyr_row['defamt'] : 0 ;

                    $calyr_balamt  = ($calyr_row['totamt'] - $calyr_row['colamt'] - $calyr_row['defamt']);



                    $params = [

                        "month_ipamt"   => $month_ipamt,                      

                        "month_opamt"   => $month_opamt,

                        "month_cnamt"   => $month_cnamt,

                        'month_stamt'   => $month_stamt,

                        "month_totamt"  => $month_totamt,

                        "month_colamt"  => $month_colamt,

                        "month_defamt"  => $month_defamt,

                        "month_balamt"  => $month_balamt,

                        "finyr_ipamt"   => $finyr_ipamt,

                        "finyr_opamt"   => $finyr_opamt,

                        "finyr_cnamt"   => $finyr_cnamt,

                        "finyr_stamt"   => $finyr_stamt,

                        "finyr_totamt"  => $finyr_totamt,

                        "finyr_colamt"  => $finyr_colamt,

                        "finyr_defamt"  => $finyr_defamt,

                        "finyr_balamt"  => $finyr_balamt,

                        "calyr_ipamt"  => $calyr_ipamt,

                        "calyr_opamt"  => $calyr_opamt,

                        "calyr_cnamt"  => $calyr_cnamt,

                        "calyr_stamt"  => $calyr_stamt,

                        "calyr_totamt"  => $calyr_totamt,

                        "calyr_colamt"  => $calyr_colamt,

                        "calyr_defamt"  => $calyr_defamt,

                        "calyr_balamt"  => $calyr_balamt,

                    ];



                }else if($report_seqn == 'N'){

                    $report_desc      = "BILL REGISTER : INITIAL-WISE (SUMMARY)" ;



                    $bill_sql = "select a.initial_code,d.initial_name,sum(ifnull(a.bill_amount_inpocket, 0)) ipamt,sum(ifnull(a.bill_amount_outpocket,0)) opamt,sum(ifnull(a.bill_amount_counsel,  0)) cnamt,sum(ifnull(a.service_tax_amount,   0)) stamt,sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) colamt,sum(ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) defamt

                            from bill_detail a, initial_master d

                            where a.branch_code like '$branch_code'

                            and a.client_code like '$client_code'

                            and a.matter_code like '$matter_code'

                            and a.initial_code like '$initial_code'

                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                            and a.initial_code = d.initial_code 

                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                            group by a.initial_code, d.initial_name 

                            order by d.initial_name " ; 

                }else if($report_seqn == 'M' || $report_seqn == 'C' ){

                    $report_desc      = "BILL REGISTER : MONTH-WISE (SUMMARY)" ;

                    $order_by_clause = ($report_seqn == 'C') ? "a.month_code" : "b.month_serial" ;

                    $bill_sql = "select a.month_code,b.month_descl month_name,sum(ifnull(a.bill_amount_inpocket, 0)) ipamt,sum(ifnull(a.bill_amount_outpocket,0)) opamt,sum(ifnull(a.bill_amount_counsel,  0)) cnamt,sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket, 0) + ifnull(a.bill_amount_counsel, 0)) totamt,sum(ifnull(a.realise_amount_inpocket, 0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) colamt,sum(ifnull(a.deficit_amount_inpocket, 0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) defamt,b.month_serial

                    from bill_detail a, months b

                    where a.branch_code like '$branch_code'

                    and a.client_code like '$client_code'

                    and a.matter_code like '$matter_code'

                    and a.initial_code like '$initial_code'

                    and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                    and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                    and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                    and a.month_code = b.month_no

                    group by a.branch_code,b.month_serial 

                    order by ".$order_by_clause ; 	   

                   

                }else if($report_seqn == 'Y'){

                    $report_desc     = "BILL REGISTER : YEAR-WISE (SUMMARY)" ;



                    $bill_sql = "select substring(a.bill_date,1,4) calyr,b.client_name,sum(ifnull(a.bill_amount_inpocket, 0)) ipamt,sum(ifnull(a.bill_amount_outpocket,0)) opamt,sum(ifnull(a.bill_amount_counsel,  0)) cnamt,sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket, 0) + ifnull(a.bill_amount_counsel, 0)) totamt,sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) colamt,sum(ifnull(a.deficit_amount_inpocket, 0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) defamt

                        from bill_detail a , client_master b

                        where a.client_code = b.client_code 

                        and a.branch_code like '$branch_code'

                        and a.client_code like '$client_code'

                        and a.matter_code like '$matter_code'

                        and a.initial_code like '$initial_code'

                        and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                        and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                        and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                        group by substring(a.bill_date,1,4) 

                        order by 1 " ; 	   

                }

                break;

            case 'D' : 



                $attention_code  = isset($_REQUEST['attention_code']) ? $_REQUEST['attention_code'] : '' ;  if(empty($attention_code)) { $attention_code  = '%' ; }

                $attention_name  = get_attention_name($attention_code) ;



                if($attention_code == '%') { $attention_heading  = 'ATTENTION : ALL' ; } else { $attention_heading  = 'ATTENTION : SELECTIVE' ; }

                if($initial_code   == '%') { $initial_heading   = 'INITIAL : ALL'    ; } else { $initial_heading    = 'INITIAL : SELECTIVE'  ; }

                if($attention_code == '%') { $attention_name    = 'ALL'              ; } else { $attention_name     = $attention_name ; }



                $report_desc      = "BILL REGISTER : COURT/CLIENT/INITIAL/MATTER-WISE (DETAILS)" ;

                $report_sub_desc  = '[ '.$court_heading.' / '.$client_heading.' / '.$attention_heading.' / '.$matter_heading.' ]' ;



                if($report_seqn == 'B')      { $order_by_clause = "a.bill_date,a.fin_year,a.bill_no" ; }

                else if($report_seqn == 'C') { $order_by_clause = "b.client_name,a.bill_date,a.fin_year,a.bill_no" ; }

                else if($report_seqn == 'M') { $order_by_clause = "a.matter_code,a.bill_date,a.fin_year,a.bill_no" ; }

                else if($report_seqn == 'I') { $order_by_clause = "d.code_desc,a.bill_date,a.fin_year,a.bill_no" ; }

                else if($report_seqn == 'N') { $order_by_clause = "c.initial_code,d.code_desc,a.bill_date,a.fin_year,a.bill_no" ; }

                else if($report_seqn == 'Y') { session()->setFlashdata('message', 'No Records Found !!'); return redirect()->to($requested_url);}


                $bill_sql = "select concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.attention_code,a.matter_code,

                        ifnull(a.bill_amount_inpocket, 0) ipamt,ifnull(a.bill_amount_outpocket,0) opamt,ifnull(a.bill_amount_counsel,  0) cnamt,ifnull(a.service_tax_amount,   0) stamt,

                        (ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt,

                        (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,

                        (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,

                        (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,

                        ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,b.client_name,

                        if(c.matter_desc1 != '', concat(c.matter_desc1,' : ',c.matter_desc2),c.matter_desc2) matter_desc,c.court_code,c.initial_code,d.code_desc court_name

                        from bill_detail a , client_master b, fileinfo_header c, code_master d

                        where a.branch_code like '$branch_code'

                        and a.client_code like '$client_code'

                        and a.attention_code like '$attention_code'

                        and a.matter_code like '$matter_code'

                        and ifnull(c.initial_code,'N') like '$initial_code' 

                        and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 

                        and a.bill_date between '$start_date_ymd' and '$end_date_ymd'

                        and a.client_code = b.client_code

                        and a.matter_code = c.matter_code

                        and c.court_code like '$court_code'

                        and c.court_code = d.code_code and d.type_code = '001' 

                        and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')

                        order by ".$order_by_clause ; 

                    $params = [

                        "attention_name" => $attention_name,

                    ];

                    break;

        }

        $reports = $this->db->query($bill_sql)->getResultArray() ;

        // echo '<pre>';print_r($reports);die;

        $bill_cnt = count($reports) ;

        $date = date('d-m-Y');



        if(empty($reports)) {

            session()->setFlashdata('message', 'No Records Found !!');

            return redirect()->to($requested_url);

        }



        $params += [

            "branch_name"   => $branch_name,

            "report_desc"   => $report_desc,

            "report_sub_desc"=> $report_sub_desc,

            "ason_date"     => $ason_date,

            "bill_cnt"      => $bill_cnt,

            "period_desc"   => $period_desc,

            "court_code"    => $court_code,

            "court_name"    => $court_name,

            "client_code"   => $client_code,

            "client_name"   => $client_name,

            "matter_code"   => $matter_code,

            "matter_desc"   => $matter_desc,

            "initial_code"  => $initial_code,

            //"initial_name"  => $initial_name,

            "xMonStartYmd"  => $xMonStartYmd,

            "xMonEndYmd"    => $xMonEndYmd,

            "xFinStartYmd"  => $xFinStartYmd,

            "xFinEndYmd"    => $xFinEndYmd,

            "xCalStartYmd"  => $xCalStartYmd,

            "xCalEndYmd"    => $xCalEndYmd,

            //"bill_cnt"     => $bill_cnt,

            "date"          => $date,

            "start_date_ymd"=> $start_date_ymd,

            "end_date_ymd"  => $end_date_ymd,

            "report_seqn"  => $report_seqn,

            "report_type"  => $report_type,

            "requested_url" => $requested_url,



        ];

        if ($output_type == 'Pdf') {
            $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
            $reportHTML = view("pages/Billing/bill_register_court_initial", compact("reports", "params", "report_type"));
            // echo '<pre>';print_r($reportHTML);die;
            $dompdf->loadHtml($reportHTML);
            //$dompdf->setPaper('A4', 'landscape'); // portrait
            
            $dompdf->render();
            $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
        } else return view("pages/Billing/bill_register_court_initial", compact("reports", "params"));
                
            } if($output_type == 'Excel') { 

                $attention_code  = isset($_REQUEST['attention_code']) ? $_REQUEST['attention_code'] : '' ;  if(empty($attention_code)) { $attention_code  = '%' ; }
                $attention_name  = get_attention_name($attention_code) ;

                if($report_type == 'D') {
                    if($report_seqn == 'B')      { $order_by_clause = "a.bill_date,a.fin_year,a.bill_no" ; }
                    else if($report_seqn == 'C') { $order_by_clause = "b.client_name,a.bill_date,a.fin_year,a.bill_no" ; } 
                    else if($report_seqn == 'M') { $order_by_clause = "a.matter_code,a.bill_date,a.fin_year,a.bill_no" ; }
                    else if($report_seqn == 'I') { $order_by_clause = "d.code_desc,a.bill_date,a.fin_year,a.bill_no" ; }
                    else if($report_seqn == 'N') { $order_by_clause = "c.initial_code,d.code_desc,a.bill_date,a.fin_year,a.bill_no" ; }
                    else if($report_seqn == 'Y') { session()->setFlashdata('message', 'No Records Found !!'); return redirect()->to($_SERVER['REQUEST_URI']); }

                } else {
                    if($report_seqn == 'C')      { $group_by_clause = "b.client_name"  ; $order_by_clause = "b.client_name" ; }
                    else if($report_seqn == 'M') { $group_by_clause = "a.matter_code"  ; $order_by_clause = "a.matter_code" ; }
                    else if($report_seqn == 'I') { $group_by_clause = "c.court_code" ; $order_by_clause = "d.code_desc" ;}
                    else if($report_seqn == 'N') { $group_by_clause = "c.initial_code" ; $order_by_clause = "c.initial_code" ; }
                    else if($report_seqn == 'Y') { session()->setFlashdata('message', 'No Records Found !!'); return redirect()->to($_SERVER['REQUEST_URI']); }

                }

                $bill_sql = '';
                if ($report_type == 'D') {
                        $bill_sql = "select concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.attention_code,a.matter_code,
                                ifnull(a.bill_amount_inpocket, 0) ipamt,ifnull(a.bill_amount_outpocket,0) opamt,ifnull(a.bill_amount_counsel,  0) cnamt,ifnull(a.service_tax_amount,   0) stamt,
                                (ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt,
                                (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
                                (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,
                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,
                                ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,
                                b.client_name,c.matter_desc1,c.matter_desc2,c.reference_desc,c.requisition_no,c.date_of_filing,c.court_code,c.initial_code,d.code_desc court_name
                                from bill_detail a , client_master b, fileinfo_header c, code_master d
                                where a.branch_code like '$branch_code'
                                and a.client_code like '$client_code'
                                and a.attention_code like '$attention_code'
                                and a.matter_code like '$matter_code'
                                and ifnull(c.initial_code,'N') like '$initial_code' 
                                and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 
                                and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
                                and a.client_code = b.client_code
                                and a.matter_code = c.matter_code
                                and c.court_code like '$court_code'
                                and c.court_code = d.code_code and d.type_code = '001' 
                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                                order by ".$order_by_clause ;   

                } if($report_type != 'D' && $report_seqn == 'I') { 

                   $bill_sql = "select c.court_code,d.code_desc court_name,sum(ifnull(a.bill_amount_inpocket, 0)) ipamt,sum(ifnull(a.bill_amount_outpocket,0)) opamt,sum(ifnull(a.bill_amount_counsel,  0)) cnamt,sum(ifnull(a.service_tax_amount,   0)) stamt,
                            sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,
                            sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
                            sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,
                            sum(ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,
                            sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount
                            from bill_detail a , client_master b, fileinfo_header c, code_master d,initial_master e
                            where a.branch_code like '$branch_code'
                            and a.client_code like '$client_code'
                            and a.attention_code like '$attention_code'
                            and a.matter_code like '$matter_code'
                            and ifnull(c.initial_code,'N') like '$initial_code' 
                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 
                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
                            and a.client_code = b.client_code
                            and a.matter_code = c.matter_code
                            and c.initial_code = e.initial_code
                            and c.court_code like '$court_code'
                            and c.court_code = d.code_code and d.type_code = '001' 
                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                            group by c.court_code order by court_name" ; 

                } if($report_type != 'D' && $report_seqn == 'C') { 

                    $bill_sql = "select a.client_code,a.matter_code,a.attention_code, c.court_code,d.code_desc court_name,c.initial_code,e.initial_name,concat(c.matter_desc1,' ',c.matter_desc2) matter_name,
                            sum(ifnull(a.bill_amount_inpocket, 0)) ipamt,sum(ifnull(a.bill_amount_outpocket,0)) opamt,sum(ifnull(a.bill_amount_counsel,  0)) cnamt,sum(ifnull(a.service_tax_amount,   0)) stamt,
                            sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,
                            sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
                            sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,
                            sum(ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,
                            sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,c.reference_desc,b.client_name
                            from bill_detail a , client_master b, fileinfo_header c, code_master d,initial_master e
                            where a.branch_code like '$branch_code'
                            and a.client_code like '$client_code'
                            and a.attention_code like '$attention_code'
                            and a.matter_code like '$matter_code'
                            and ifnull(c.initial_code,'N') like '$initial_code' 
                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 
                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
                            and a.client_code = b.client_code
                            and a.matter_code = c.matter_code
                            and c.initial_code = e.initial_code
                            and c.court_code like '$court_code'
                            and c.court_code = d.code_code and d.type_code = '001' 
                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                            group by ".$group_by_clause." order by ".$order_by_clause ; 

                } if($report_type != 'D' && $report_seqn == 'M') { 

                   $bill_sql = "select a.client_code,a.attention_code,a.matter_code,c.court_code,d.code_desc court_name,c.initial_code,e.initial_name,concat(c.matter_desc1,' ',c.matter_desc2) matter_name,
                            sum(ifnull(a.bill_amount_inpocket, 0)) ipamt,sum(ifnull(a.bill_amount_outpocket,0)) opamt,sum(ifnull(a.bill_amount_counsel,  0)) cnamt,sum(ifnull(a.service_tax_amount,   0)) stamt,
                            sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,
                            sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
                            sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,
                            sum(ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,
                            sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,c.reference_desc,b.client_name
                            from bill_detail a , client_master b, fileinfo_header c, code_master d,initial_master e
                            where a.branch_code like '$branch_code'
                            and a.client_code like '$client_code'
                            and a.attention_code like '$attention_code'
                            and a.matter_code like '$matter_code'
                            and ifnull(c.initial_code,'N') like '$initial_code' 
                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 
                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
                            and a.client_code = b.client_code
                            and a.matter_code = c.matter_code
                            and c.initial_code = e.initial_code
                            and c.court_code like '$court_code'
                            and c.court_code = d.code_code and d.type_code = '001' 
                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                            group by a.matter_code order by matter_code" ;  

                } if($report_type != 'D' && $report_seqn == 'N') { 
                   $bill_sql = "select c.initial_code,d.initial_name,sum(ifnull(a.bill_amount_inpocket, 0)) ipamt,sum(ifnull(a.bill_amount_outpocket,0)) opamt,sum(ifnull(a.bill_amount_counsel,  0)) cnamt,sum(ifnull(a.service_tax_amount,   0)) stamt,
                            sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,
                            sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
                            sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,
                            sum(ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,
                            sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount
                            from bill_detail a , client_master b, fileinfo_header c, initial_master d
                            where a.branch_code like '$branch_code'
                            and a.client_code like '$client_code'
                            and a.attention_code like '$attention_code'
                            and a.matter_code like '$matter_code'
                            and a.initial_code like '$initial_code'
                            and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 
                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
                            and a.client_code = b.client_code
                            and a.matter_code = c.matter_code
                            and c.initial_code = d.initial_code 
                            and a.initial_code = c.initial_code
                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                            group by c.initial_code, d.initial_name 
                            order by d.initial_name "  ; 
                }     

                $excels  = $this->db->query($bill_sql)->getResultArray() ;
                // echo '<pre>'; print_r($excels);die;
                $bill_cnt  = count($excels);

                try {
                $excels[0];
                if($bill_cnt == 0)  throw new \Exception('No Records Found !!');

                } catch (\Exception $e) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($_SERVER['REQUEST_URI']);
                }
                $fileName = 'BILL_REGISTER_COURTWISE-'.date('d-m-Y').'.xlsx';  
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                switch($report_type) {
                    case 'D' :
                        // Define the headings
                        $headings = ['Court', 'Bill No', 'Bill Date', 'Initial', 'Client', 'Attention', 'Matter', 'Case', 'Description', 'Reference', 'Inpocket', 'Outpocket', 'Counsel', 'Service Tax', 'Total', 'Realised', 'Deficit', 'O/s'];

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
                            $attention_code = get_attention_name($excel['attention_code']);

                            $sheet->setCellValue('A' . $rows, strtoupper($excel['court_name']));
                            $sheet->setCellValue('B' . $rows, $excel['bill_number']);
                            $sheet->setCellValue('C' . $rows, date_conv($excel['bill_date']));
                            $sheet->setCellValue('D' . $rows, strtoupper($excel['initial_code']));
                            $sheet->setCellValue('E' . $rows, strtoupper($excel['client_name']));
                            $sheet->setCellValue('F' . $rows, strtoupper($attention_code));
                            $sheet->setCellValue('G' . $rows, strtoupper($excel['matter_code']));
                            $sheet->setCellValue('H' . $rows, strtoupper($excel['matter_desc1']));
                            $sheet->setCellValue('I' . $rows, strtoupper($excel['matter_desc2']));
                            $sheet->setCellValue('J' . $rows, "'".strtoupper(stripslashes($excel['reference_desc'])));
                            $sheet->setCellValue('K' . $rows, ($excel['ipamt'] > 0) ? number_format($excel['ipamt'], 2,'.','') : '');
                            $sheet->setCellValue('L' . $rows, ($excel['opamt'] > 0) ? number_format($excel['opamt'], 2,'.','') : '');
                            $sheet->setCellValue('M' . $rows, ($excel['cnamt'] > 0) ? number_format($excel['cnamt'], 2,'.','') : '');
                            $sheet->setCellValue('N' . $rows, ($excel['stamt'] > 0) ? number_format($excel['stamt'], 2,'.','') : '');
                            $sheet->setCellValue('O' . $rows, ($excel['totamt'] > 0) ? number_format($excel['totamt'], 2,'.','') : '');
                            $sheet->setCellValue('P' . $rows, ($excel['realised_amount'] > 0) ? number_format($excel['realised_amount'], 2,'.','') : '');
                            $sheet->setCellValue('Q' . $rows, ($excel['deficit_amount'] > 0) ? number_format($excel['deficit_amount'], 2,'.','') : '');
                            $sheet->setCellValue('R' . $rows, ($excel['balance_amount'] > 0) ? number_format($excel['balance_amount'], 2,'.','') : '');
                            
                            // Apply border to the current row
                            $style = $sheet->getStyle('A' . $rows . ':R' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                            $rows++;
                        } break;
                    case 'S' :
                        // Define the headings
                        $headings = ['Name', 'Inpocket', 'Outpocket', 'Counsel', 'Service Tax', 'Total', 'Realised', 'Deficit', 'O/s'];

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
                            if($report_seqn == 'C') {$level_code = $excel['client_code']   ; $level_name = $excel['client_name']  ; }
                            if($report_seqn == 'M') {$level_code = $excel['matter_code']   ; $level_name = $excel['matter_name']  ; }
                            if($report_seqn == 'I') {$level_code = $excel['court_code']    ; $level_name = $excel['court_name']  ; }
                            if($report_seqn == 'N') {$level_code = $excel['initial_code']  ; $level_name = $excel['initial_name']  ; }

                            $sheet->setCellValue('A' . $rows, strtoupper($level_name));
                            $sheet->setCellValue('B' . $rows, ($excel['ipamt'] > 0) ? number_format($excel['ipamt'], 2,'.','') : '');
                            $sheet->setCellValue('C' . $rows, ($excel['opamt'] > 0) ? number_format($excel['opamt'], 2,'.','') : '');
                            $sheet->setCellValue('D' . $rows, ($excel['cnamt'] > 0) ? number_format($excel['cnamt'], 2,'.','') : '');
                            $sheet->setCellValue('E' . $rows, ($excel['stamt'] > 0) ? number_format($excel['stamt'], 2,'.','') : '');
                            $sheet->setCellValue('F' . $rows, ($excel['totamt'] > 0) ? number_format($excel['totamt'], 2,'.','') : '');
                            $sheet->setCellValue('G' . $rows, ($excel['realised_amount'] > 0) ? number_format($excel['realised_amount'], 2,'.','') : '');
                            $sheet->setCellValue('H' . $rows, ($excel['deficit_amount'] > 0) ? number_format($excel['deficit_amount'], 2,'.','') : '');
                            $sheet->setCellValue('I' . $rows, ($excel['balance_amount'] > 0) ? number_format($excel['balance_amount'], 2,'.','') : '');
                            
                            // Apply border to the current row
                            $style = $sheet->getStyle('A' . $rows . ':I' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                            $rows++;
                        } break;
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
        }else{

            return view("pages/Billing/bill_register_court_initial", compact("data", "displayId"));



        }



    }
    //only need excel pattern//
    public function bill_send(){

        $arr['leftMenus'] = menu_data(); 

        $arr['menuHead'] = [0];

       	$data = branches(session()->userId);

    	$data['requested_url'] = $this->session->requested_end_menu_url;

        $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4220', 'court_help_id' => '4221', 'initial_help_id' => '4191'] ;



        if($this->request->getMethod() == 'post') {

            $requested_url = base_url($data['requested_url']);
    
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
    
    
    
            $ason_date       = $_REQUEST['ason_date'] ;
    
            $branch_code     = $_REQUEST['branch_code'] ;
    
            $start_date      = $_REQUEST['start_date'] ;      if($start_date != '')   { $start_date_ymd = date_conv($start_date); } else { $start_date_ymd = '1901-01-01'; }
    
            $end_date        = $_REQUEST['end_date'] ;        if($end_date   != '')   { $end_date_ymd   = date_conv($end_date);   } else { $end_date_ymd   = date('Y-m-d') ; }
    
            $court_code      = $_REQUEST['court_code'] ;      if(empty($court_code))  { $court_code   = '%' ; }
    
            $court_name      = $_REQUEST['court_name'] ;
    
            $client_code     = $_REQUEST['client_code'] ;     if(empty($client_code)) { $client_code  = '%' ; }
    
            $client_name     = $_REQUEST['client_name'] ;
    
            $matter_code     = $_REQUEST['matter_code'] ;     if(empty($matter_code)) { $matter_code  = '%' ; }
    
            $matter_desc     = $_REQUEST['matter_desc'] ;
    
            $initial_code    = $_REQUEST['initial_code'] ;    if(empty($initial_code)){ $initial_code = '%' ; }
    
            $initial_name    = $_REQUEST['initial_name'] ;
    
            $billfor_ind     = $_REQUEST['billfor_ind'] ;
    
            $report_seqn     = $_REQUEST['report_seqn'] ;
    
            $report_type     = $_REQUEST['report_type'] ;
    
            $output_type     = $_REQUEST['output_type'] ;
    
            $branch_qry         = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0];
    
            $branch_name   = $branch_qry["branch_name"] ;
    
          
    
            if($report_type == 'D') 
    
            {
    
              if($report_seqn == 'B')      { $order_by_clause = "a.bill_date,a.fin_year,a.bill_no" ; }
    
              else if($report_seqn == 'C') { $order_by_clause = "b.client_name,a.bill_date,a.fin_year,a.bill_no" ; }
    
              else if($report_seqn == 'M') { $order_by_clause = "a.matter_code,a.bill_date,a.fin_year,a.bill_no" ; }
    
              else if($report_seqn == 'I') { $order_by_clause = "d.code_desc,a.bill_date,a.fin_year,a.bill_no" ; }
    
              else if($report_seqn == 'N') { $order_by_clause = "c.initial_code,d.code_desc,a.bill_date,a.fin_year,a.bill_no" ; }
    
          
    
            }
    
            else
    
            {
    
              if($report_seqn == 'C')      { $group_by_clause = "b.client_name"  ; $order_by_clause = "b.client_name" ; }
    
              else if($report_seqn == 'M') { $group_by_clause = "a.matter_code"  ; $order_by_clause = "a.matter_code" ; }
    
              else if($report_seqn == 'I') { $group_by_clause = "c.court_code" ; $order_by_clause = "d.code_desc" ;}
    
              else if($report_seqn == 'N') { $group_by_clause = "c.initial_code" ; $order_by_clause = "c.initial_code" ; }
    
          
    
            }
      

            if($output_type == 'Report' || $output_type == 'Pdf' ) {
    
                if ($report_type == 'D') 
        
                { 
        
                 $bill_sql = "select concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.matter_code,
        
                        ifnull(a.bill_amount_inpocket, 0) ipamt,ifnull(a.bill_amount_outpocket,0) opamt,ifnull(a.bill_amount_counsel,  0) cnamt,ifnull(a.service_tax_amount,   0) stamt,
        
                        (ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt,
        
                        (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
        
                        (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,
        
                        (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,
        
                        ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,b.client_name,
        
                        ifnull(c.matter_desc1, '') matter_desc1,c.matter_desc2,c.reference_desc,c.requisition_no,c.date_of_filing,c.court_code,c.initial_code,d.code_desc court_name
        
                        from bill_detail a , client_master b, fileinfo_header c, code_master d
        
                        where a.branch_code like '$branch_code'
        
                        and a.client_code like '$client_code'
        
                        and a.matter_code like '$matter_code'
        
                        and ifnull(c.initial_code,'N') like '$initial_code' 
        
                        and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
        
                        and a.client_code = b.client_code
        
                        and a.matter_code = c.matter_code
        
                        and a.initial_code != 'AR'
        
                        and a.initial_code != 'BKG'
        
                        and a.initial_code != 'GM'
        
                        and c.court_code like '$court_code'
        
                        and c.court_code = d.code_code and d.type_code = '001' 
        
                        and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
        
                        order by ".$order_by_clause ; 
        
                                 
        
                } 
        
                else
        
                { 
        
                   $bill_sql = "select a.client_code,a.matter_code,c.court_code,d.code_desc court_name,c.initial_code,e.initial_name,concat(c.matter_desc1,' ',c.matter_desc2) matter_name,
        
                        sum(ifnull(a.bill_amount_inpocket, 0)) ipamt,sum(ifnull(a.bill_amount_outpocket,0)) opamt,sum(ifnull(a.bill_amount_counsel,  0)) cnamt,sum(ifnull(a.service_tax_amount,   0)) stamt,
        
                        sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) totamt,
        
                        sum(ifnull(a.bill_amount_inpocket, 0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
        
                        sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,
        
                        sum(ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,
        
                        sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,c.reference_desc,b.client_name
        
                        from bill_detail a , client_master b, fileinfo_header c, code_master d,initial_master e
        
                        where a.branch_code like '$branch_code'
        
                        and a.client_code like '$client_code'
        
                        and a.matter_code like '$matter_code'
        
                        and ifnull(c.initial_code,'N') like '$initial_code' 
        
                        and ifnull(a.court_fee_bill_ind,'N') like '$billfor_ind' 
        
                        and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
        
                        and a.client_code = b.client_code
        
                        and a.matter_code = c.matter_code
        
                        and a.initial_code != 'AR'
        
                        and a.initial_code != 'BKG'
        
                        and c.initial_code = e.initial_code
        
                        and c.court_code like '$court_code'
        
                        and c.court_code = d.code_code and d.type_code = '001' 
        
                        and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
        
                        group by ".$group_by_clause." order by ".$order_by_clause ; 
        
                } 
        
                $reports  = $this->db->query($bill_sql)->getResultArray();
        
                $bill_cnt = count($reports);
        
                $date = date('d-m-Y');
        
        
        
                if(empty($reports)) {
        
                    session()->setFlashdata('message', 'No Records Found !!');
        
                    return redirect()->to($requested_url);
        
                }
        
                
        
                $params = [
        
                    "branch_name"   => $branch_name,
        
                    // "report_desc"   => $report_desc,
        
                    // "report_sub_desc"=> $report_sub_desc,
        
                    "ason_date"     => $ason_date,
        
                    "bill_cnt"      => $bill_cnt,
        
                    //"period_desc"   => $period_desc,
        
                    "court_code"    => $court_code,
        
                    "court_name"    => $court_name,
        
                    "client_code"   => $client_code,
        
                    "client_name"   => $client_name,
        
                    "matter_code"   => $matter_code,
        
                    "matter_desc"   => $matter_desc,
        
                    "initial_code"  => $initial_code,
        
                    "initial_name"  => $initial_name,
        
                    "bill_cnt"      => $bill_cnt,
        
                    "date"          => $date,
        
                    "start_date_ymd"=> $start_date_ymd,
        
                    "end_date_ymd"  => $end_date_ymd,
        
                    "report_seqn"   => $report_seqn,
        
                    "report_type"  => $report_type,
        
                    "requested_url" => $requested_url,
        
        
        
                ];
                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/Billing/bill_send", compact("reports", "params", "report_type"));
                    // echo '<pre>';print_r($reportHTML);die;
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/Billing/bill_send", compact("reports", "params"));
        
            } if($output_type == 'Excel') { 
                    if ($report_type == 'D') { 
    
                    $bill_sql = "select concat(a.fin_year,'/',a.bill_no) bill_number,a.bill_date,a.client_code,a.matter_code,ifnull(a.bill_amount_inpocket, 0) ipamt,ifnull(a.bill_amount_outpocket,0) opamt,ifnull(a.bill_amount_counsel,  0) cnamt,ifnull(a.service_tax_amount,   0) stamt,
                            (ifnull(a.bill_amount_inpocket, 0)+ifnull(a.bill_amount_outpocket,0)+ifnull(a.bill_amount_counsel,0)+ifnull(a.service_tax_amount,0)) totamt,
                            (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
                            (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,
                            (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,
                            ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,
                            b.client_name,ifnull(c.matter_desc1, '') matter_desc1,c.matter_desc2,c.reference_desc,c.requisition_no,c.date_of_filing,c.court_code,c.initial_code,d.code_desc court_name
                            from bill_detail a , client_master b, fileinfo_header c, code_master d
                            where a.branch_code like '$branch_code'
                            and a.client_code like '$client_code'
                            and a.matter_code like '$matter_code'
                            and ifnull(c.initial_code,'N') like '$initial_code' 
                            and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
                            and a.client_code = b.client_code
                            and a.matter_code = c.matter_code
                            and a.initial_code != 'AR'
                            and a.initial_code != 'BKG'
                            and a.initial_code != 'GM'
                            and c.court_code like '$court_code'
                            and c.court_code = d.code_code and d.type_code = '001' 
                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                        order by ".$order_by_clause ; 
                    } 
                    $excels  = $this->db->query($bill_sql)->getResultArray() ;
                    // echo '<pre>'; print_r($excels);die;
                    $bill_cnt  = count($excels);
    
                    try {
                    $excels[0];
                    if($bill_cnt == 0)  throw new \Exception('No Records Found !!');
    
                    } catch (\Exception $e) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($_SERVER['REQUEST_URI']);
                    }
                    $fileName = 'BILL_SEND-'.date('d-m-Y').'.xlsx';  
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
    
                    $headings = ['Sl#', 'Bill No', 'Bill Date', 'Initial', 'Client', 'Matter', 'Case', 'Description', 'Total', 'Send On'];
    
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
                            foreach ($excels as $key => $excel){
    
                                $sheet->setCellValue('A' . $rows, $key+1);
                                $sheet->setCellValue('B' . $rows, $excel['bill_number']);
                                $sheet->setCellValue('C' . $rows, date_conv($excel['bill_date']));
                                $sheet->setCellValue('D' . $rows, strtoupper($excel['initial_code']));
                                $sheet->setCellValue('E' . $rows, strtoupper($excel['client_name']));
                                $sheet->setCellValue('F' . $rows, strtoupper($excel['matter_code']));
                                $sheet->setCellValue('G' . $rows, ($excel['matter_desc1'] == '') ?  '-' : strtoupper($excel['matter_desc1']));
                                $sheet->setCellValue('H' . $rows, strtoupper($excel['matter_desc2']));
                                $sheet->setCellValue('I' . $rows, ($excel['totamt']  > 0) ? currency_format($excel['totamt'],2,'.','') : '');
                                
                                // Apply border to the current row
                                $style = $sheet->getStyle('A' . $rows . ':I' . $rows);
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
            
        }else{

            return view("pages/Billing/bill_send", compact("data", "displayId"));



        }

    }

}

?>