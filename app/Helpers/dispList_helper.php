<?php

// function display_list($display_id, $menu_id, $page_no, $search_val) {
    function display_list($output_type = null) {
        //==============================================dispmaster.php===============================================
        $db = \config\database::connect();
       // echo $_REQUEST['display_id'];die;
        $display_id     = isset($_REQUEST['params'])?$_REQUEST['params']:$_REQUEST['display_id'];  
        $menu_id        = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
        $my_menuid      = $menu_id;
        
        $sql            = "select * from display_header where display_id = ".$display_id;
        $header         = $db->query($sql)->getRowArray();
        $manual_id      = $header['manual_id'];
        $master_id      = $header['master_id'];
        $disp_heading   = $header['disp_heading'];
        $col_heading    = explode(",",$header['col_heading']);
        $col_align      = explode(",",$header['col_align']);
        $col_total_ind  = explode(",",$header['col_total_ind']);
        $col_width      = explode(",",$header['col_width']);
        $total_width    = 0;
        for($i = 0; $i < count($col_width); $i++)
        $total_width += $col_width[$i]; 
        $col_height     = empty($header['col_height'])?20:$header['col_height'];
        $grid_height    = empty($header['grid_height'])?350:$header['grid_height'];
        $cols_per_row   = empty($header['cols_per_row'])?5:$header['cols_per_row'];
        $rows_per_pg    = empty($header['rows_per_pg'])?10:$header['rows_per_pg'];
        $search_by      = explode(",",$header['search_by']);
        $return_object  = explode(",",$header['return_object']);
        $return_val_seq = explode(",",$header['return_val_seq']);
        $display_type   = $header['display_type'];
        $total_col      = count($col_heading);
        
        $param_name     = explode(",",$header['ext_param_name']);
        $param_value    = explode(",",$header['ext_param_value']);
        $session = session();
        $permission[]=$session->Accpermission;
        
        // Added by Surajit Naskar on 26-10-2023
        if(isset($_REQUEST['rows_per_page'])) session()->set('rowsPerPage', $_REQUEST['rows_per_page']);
        else if (!isset(session()->rowsPerPage)) session()->set('rowsPerPage', $rows_per_pg);
        $rows_per_pg = (session()->rowsPerPage != 'All') ? session()->rowsPerPage : 'All';
        
        // echo "<pre>"; print_r($header); die; // myclient_code=A00281 // where_clause| a.initial_code <> 'AOR' AND a.status_code <> 'Old'

        if ($display_type == 'Help'){
           
            return disp_master_help ($header, $search_by, $param_name, $param_value, $rows_per_pg, $col_heading, $disp_heading,$permission, $output_type);
        }
        else if($display_type == 'Query') { 
            //==============================================dispmasterlist.php===============================================
            return disp_master_list ($display_id, $header, $disp_heading, $col_heading, $rows_per_pg, $search_by, $param_name, $param_value,$permission, $output_type);
        }
        else if($display_type == 'Report') {
            return disp_master_report ($cols_per_row, $rows_per_pg, $header, $param_name, $param_value, $disp_heading, $col_heading, $total_col);
        }
    }
    
    function disp_master_help($header, $search_by, $param_name, $param_value, $rows_per_pg, $col_heading, $disp_heading,$permission, $output_type) {
        $db = \config\database::connect();

        $row_no         = isset($_REQUEST['row_no'])?$_REQUEST['row_no']:null;
        if(strtolower($row_no) == "null") $row_no = null ;
        $search_val     = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;  //search value   ..........
        $index          = isset($_REQUEST['index'])?$_REQUEST['index']:null;            //sorting column ..........
        $ord            = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;                //sorting order ...........

        $clause         = "";
        $rows_per_pg    = 20;
        
        if (!empty($header['where_clause'])) 
        {
            
            $clause       = " where ".$header['where_clause'];
            
            if (isset($param_name)) 
            {
                
                for($i = 0; $i < count($param_name); $i++) 
                {
                    //            $$param_value[$i] = isset($$param_value[$i])?$$param_value[$i]:(isset($_REQUEST[$param_value[$i]])?$_REQUEST[$param_value[$i]]:$_SESSION[$param_value[$i]]);
                    $p_value = isset($_REQUEST[$param_value[$i]])?$_REQUEST[$param_value[$i]]: 'B001'; //$_SESSION[$param_value[$i]];
                    if($param_name[$i] != '')
                        $clause          .= " and ".$param_name[$i]." like '".$p_value."'";

                    // $_SESSION[$param_value[$i]] = $$param_value[$i];
                    // echo '<pre>'; print_r($clause);die;
                }
                
            }
        }
        else if (isset($param_name)) 
        {
            // echo "<pre>"; print_r ($param_name); die;
            
            //    $$param_value[0] = isset($$param_value[0])?$$param_value[0]:(isset($_REQUEST[$param_value[0]])?$_REQUEST[$param_value[0]]:$_SESSION[$param_value[0]]);
            $p_value = isset($_REQUEST[$param_value[0]])?$_REQUEST[$param_value[0]]: ''; // $_SESSION[$param_value[0]];
            if($param_name[0] != '')
                $clause          = " where ".$param_name[0]." like '".$p_value."'";
            
            // $_SESSION[$param_value[0]] = $p_value;
            for($i = 1; $i < count($param_name); $i++) 
            {
                //        $$param_value[$i] = isset($$param_value[$i])?$$param_value[$i]:(isset($_REQUEST[$param_value[$i]])?$_REQUEST[$param_value[$i]]:$_SESSION[$param_value[$i]]);
                // $$param_value[$i] = isset($_REQUEST[$param_value[$i]])?$_REQUEST[$param_value[$i]]:$_SESSION[$param_value[$i]];
                $p_value = isset($_REQUEST[$param_value[$i]])?$_REQUEST[$param_value[$i]]: ''; // $_SESSION[$param_value[0]];

                // $clause          .= " and ".$param_name[$i]." like '".$$param_value[$i]."'";
                if($param_name[$i] != '')
                    $clause          .= " and ".$param_name[$i]." like '".$p_value."'";
                // $_SESSION[$param_value[$i]] = $$param_value[$i];

            }
        }

        if (!empty($header['group_by_clause']))
        $clause    .= " group by ".$header['group_by_clause'];
        if (!empty($header['having_clause']))
            $clause    .= " having ".$header['having_clause'];
            
            $data_sql       = " select ".$header['select_clause'];
            $data_sql      .= " from ".$header['from_clause'];

    		$count_sql       = " select COUNT(*) AS count";
            $count_sql      .= " from ".$header['from_clause'];
            // $count_sql = "select count(*) as count from ".$header['from_clause'];

            // if($param_name[0] != '') $data_sql .= $clause;
            $data_sql .= $clause;
    		$count_sql .= $clause;

            // echo '<pre>'; print_r($data_sql);die;
            // echo '<pre>'; print_r($header['select_clause']);die;

        $selectedOption = '';
        if (!empty($search_val)) {
            if (strpos($data_sql,'where') > 0) {
                $data_sql .= " and ( "; 
            	$count_sql .= " and ( ";
            } else {
                $data_sql .= " where ( ";
             	$count_sql .= " where ( ";
            }
            $searchParams = explode('@', $search_val);
            if($searchParams[0] == '') {
                for ($i = 0; $i < count($search_by); $i++) {
                    if ($i == count($search_by) - 1){
                        $data_sql .= $search_by[$i]." like '%".$searchParams[1]."%' )";
                    	$count_sql .= $search_by[$i]." like '%".$searchParams[1]."%' )";
                    } else {
                        $data_sql .= $search_by[$i]." like '%".$searchParams[1]."%' or ";
                    	$count_sql .= $search_by[$i]." like '%".$searchParams[1]."%' or ";
                    }
                }
            } else {
                $data_sql .= $searchParams[0]." like '%".$searchParams[1]."%' )";
                $count_sql .= $searchParams[0]." like '%".$searchParams[1]."%' )";
                $selectedOption = $searchParams[0];
            }
        }
            
        // loading problem
        // echo '<pre>'; print_r($data_sql);die;
        
        // if (empty($index)) {
        //     if (!empty($header['order_by_clause'])) {
        //         $data_sql  .= " order by ".$header['order_by_clause'];
        //     	$count_sql  .= " order by ".$header['order_by_clause'];
        //     } else {
        //         $data_sql  .= " order by 1";
        //     	$count_sql  .= " order by 1";
        //     }
        // }
        // else if ($index == $ord) {
        //     $data_sql  .= " order by ".$index." desc";
        //  	$count_sql  .= " order by ".$index." desc";
        //     $ord        = "";
        // }
        // else {
        //     $data_sql  .= " order by ".$index." asc";
        // 	$count_sql  .= " order by ".$index." asc";
        //     $ord        = $index;
        // }
        
        //********************* Pagination *****************************
        $pg        = isset($_REQUEST['pg'])?$_REQUEST['pg']:1; 
        // $pg        = 39180; 
        if ($pg <= 0) $pg    = 1;
        
        $max       = $rows_per_pg;
        
        
        // $total_row = mysql_num_rows(mysql_query($data_sql,$link));
        $total_row = $db->query($count_sql)->getRowArray()['count']; 
        $total_pg  = ceil($total_row/$max);
        // echo '<pre>'; print_r($count_sql);die;
    
        if ($pg > $total_pg) $pg    = $total_pg;
        
        $start     = $pg > 0?($pg - 1)*$max:0;
        $page      = "Page ".$pg." of ".$total_pg;
        
        $go_to_page = isset($_REQUEST['go_to_page']) ? $_REQUEST['go_to_page'] : 1;
        $go_to_page = $pg ;
        $start      = $go_to_page > 0?($go_to_page - 1)*$max:0;

        $data_sql .= " limit ".$start.",".$max; 
// echo $data_sql; die;
        $data_qry  = $db->query($data_sql)->getResultArray();
     
        // $data_cnt  = count($data_qry);
        
        // echo '<pre>'; print_r($search_by);die;
        
        $data = [
            "th" => $col_heading,
            "td" => $data_qry, 
            "pg" => $pg,
            "searchParams" => $search_by,
            "selectedOption" => $selectedOption,
            "totalRecords" => $total_row,
            "totalPage" => $total_pg,
            "heading" => $disp_heading
        ];
        // echo '<pre>'; print_r($data_qry);die;

        return $data;
    }
    
    function disp_master_list ($display_id, $header, $disp_heading, $col_heading, $rows_per_pg, $search_by, $param_name, $param_value,$permission, $output_type){   
        $db = \config\database::connect();
        if (empty($param_name[0])) unset($param_name);
        $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;            //-- parameters needed to fetch the same record
        $ord = isset($_REQUEST['ord']) ? $_REQUEST['ord'] : null;                //-- after visiting another url
        $pg = isset($_REQUEST['pg'])?$_REQUEST['pg']:1;
        $search_val = isset($_REQUEST['query'])?$_REQUEST['query']:null;
        $clause = ""; $date = "";
        
        if (!empty($header['where_clause'])) {
            $clause       = " where ".$header['where_clause'];
        if (isset($param_name)) {
            for($i = 0; $i < count($param_name); $i++) {
                $$param_value[$i] = isset($$param_value[$i]) ? $$param_value[$i] : $_REQUEST[$param_value[$i]];
                $clause    .= " and ".$param_name[$i]." like '".$$param_value[$i]."'";
            }
        }
        }
        else if (isset($param_name)) {
            $$param_value[0] = isset($$param_value[0]) ? $$param_value[0] : $_REQUEST[$param_value[0]];
            $clause       = " where ".$param_name[0]." like '".$$param_value[0]."'";
            for($i = 1; $i < count($param_name); $i++) {
                $$param_value[$i] = isset($$param_value[$i])?$$param_value[$i]:$_REQUEST[$param_value[$i]];
                $clause    .= " and ".$param_name[$i]." like '".$$param_value[$i]."'";
                }
        }
        if (!empty($search_val)) {                                              //-- to fetch the same records after visiting another url
            global $date;
            if(preg_match_all('/\d{2}\-\d{2}\-\d{4}|\d{2}\/\d{2}\/\d{4}|\d{2}\.\d{2}\.\d{4}/', $search_val, $matches)) {
                $date = date_conv($matches[0][0]);
            }
            $query = ($date != "") ? $date : $search_val;
            if (!empty($header['where_clause'])) 
                $clause .= " and ( ";
                else
                $clause .= " where ( ";
            for ($i = 0; $i < count($search_by); $i++) {
                if ($i == count($search_by) - 1)
                    $clause .= $search_by[$i]." like '%".$query."%' )";
                else
                    $clause .= $search_by[$i]." like '%".$query."%' or ";
            }
        }

        if (!empty($header['group_by_clause']))
        $clause    .= " group by ".$header['group_by_clause'];
        if (!empty($header['having_clause']))
        $clause    .= " having ".$header['having_clause'];
    
        $data_sql       = " select ".$header['select_clause'];
        $data_sql      .= " from ".$header['from_clause'];
        $data_sql      .= $clause;
            
            $data_qry       = $db->query($data_sql)->getResultArray();
            $data_cnt       = count($data_qry);
            $rows_per_pg    = ($rows_per_pg == 'All') ? $data_cnt : $rows_per_pg;
            
        $pg             = empty($pg) ? 1 : $pg;
        if ($data_cnt%$rows_per_pg == 0) $total_page = $data_cnt/$rows_per_pg;
        else                             $total_page = (int)($data_cnt/$rows_per_pg) + 1;
        $page_string    = "Page ".$pg." of ".$total_page;
    
        if ($index == "") {                                                          //-- order will be same after visiting another url
            if (!empty($header['order_by_clause']))
                $data_sql  .= " order by ".$header['order_by_clause'];
            else
                $data_sql  .= " order by 1";
        }
        else if ($index == $ord) {
            $data_sql  .= " order by ".($index+1)." asc";
            $ord        = "";
        }
        else {
            $data_sql  .= " order by ".($index+1)." desc";
            $ord        = $index;
        }
    
        $start          = empty($pg) ? 0 : ($pg-1)*$rows_per_pg;

        $data_sql .= " limit ".$start.",".$rows_per_pg;
        
        $data_qry       = $db->query($data_sql)->getResultArray();
    
        $sql    = "select count(*) ,sum(link_width) from display_link_detail where display_id = ".$display_id." and link_type = 'D' and activity_id in  ( ".implode( ',' , $permission).")";
        $detail = $db->query($sql)->getResultArray();
    
        $sql    = "select count(*) as count ,sum(link_width) as sum from display_link_detail where display_id = ".$display_id." and link_type = 'D' and activity_id in  ( ".implode( ',' , $permission).")";
        $detail = $db->query($sql)->getRowArray();
        $detail_link_no = $detail['count'];
        $sql    = "select * from display_link_detail where display_id = ".$display_id." and link_type = 'H' and display_in_pos ='H' and activity_id in  ( ".implode( ',' , $permission).") order by activity_id";
       
        $hlink  = $db->query($sql)->getResultArray();
        $h_cnt  = count($hlink);
        $sql    = "select * from display_link_detail where display_id = ".$display_id." and link_type = 'D' and activity_id in  ( ".implode( ',' , $permission).") order by activity_id";
        $dLink  = $db->query($sql)->getResultArray();
        $d_cnt  = count($dLink);

        $sql    = "select * from display_link_detail where display_id = ".$display_id." and link_type = 'C'  and activity_id in  ( ".implode( ',' , $permission).") order by activity_id";
        
        $Clink  = $db->query($sql)->getResultArray();
        $C_cnt  = count($Clink);
       // echo '<pre>';print_r($dLink);die;
        $dData  = [];
        foreach ($dLink as $key => $row) {
            $dData[$key]['width']    = $row['link_width'];
            $dData[$key]['desc']     = $row['link_desc'];
            $dData[$key]['prog']     = $row['link_prog'];
            $dData[$key]['link_val'] = $row['link_val'];
            $dData[$key]['new_win']  = $row['new_win_ind'];
            $dData[$key]['access']   = $row['access_ind'];
            $dData[$key]['link_icon']   = $row['link_icon'];
            $dData[$key]['param']    = explode(",",$row['link_param']);
            $dData[$key]['seq']      = explode(",",$row['val_seq']);
            $dData[$key]['display_id']      = explode(",",$row['display_id']);
            $dData[$key]['link_type']  = $row['link_type'];
        }


        //------------------- Header Links Info -------------------------------
        $session = session();
        $sessionName=$session->userId;
        $sessionRole=$session->roleId;
        $first_login_id = $sessionName;
        $display_id     = isset($_REQUEST['params'])?$_REQUEST['params']:$_REQUEST['display_id'];  
        $menu_id        = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
        $my_menuid      = $menu_id;
        
        $sql            = "select * from display_header where display_id = ".$display_id;
        $header         = $db->query($sql)->getRowArray();
        $manual_id      = $header['manual_id'];
        $master_id      = $header['master_id'];
        $disp_heading   = $header['disp_heading'];
        $col_heading    = explode(",",$header['col_heading']);
        $total_col      = count($col_heading);
        $col_align      = explode(",",$header['col_align']);
        $col_total_ind  = explode(",",$header['col_total_ind']);
        $col_width      = explode(",",$header['col_width']);
        $total_width    = 0;
        for($i = 0; $i < count($col_width); $i++)
        $total_width += $col_width[$i]; 
        $col_height     = empty($header['col_height'])?20:$header['col_height'];
        $grid_height    = empty($header['grid_height'])?350:$header['grid_height'];
        $cols_per_row   = empty($header['cols_per_row'])?5:$header['cols_per_row'];
        $rows_per_pg    = empty($header['rows_per_pg'])?10:$header['rows_per_pg'];
        $search_by      = explode(",",$header['search_by']);
        $return_object  = explode(",",$header['return_object']);
        $return_val_seq = explode(",",$header['return_val_seq']);
        $display_type   = $header['display_type'];
        
        $param_name     = explode(",",$header['ext_param_name']);
        $param_value    = explode(",",$header['ext_param_value']);
        $header_link = ""; $detail_link = null;
        $link[] = "";
       $links[][]='';
       // echo "<pre>"; print_r($hlink); die;
       if($hlink!='')
        {
        foreach ($hlink as $key => $row) 
            {
            $href = '' ;
                   $href = $row['link_prog']."?"."&display_id=".$display_id."&menu_id=".$menu_id."&my_menuid=".$my_menuid."&master_id=".$master_id."&user_option=".$row['link_desc']."&index=".$index."&ord=".$ord."&pg=".$pg."&search_val=".$search_val."&params=".$row['link_val'];
               
                   $links[$key]['link'] = '/sinhaco/'.$href; 			  
            
            }
        }
        else
        {
            $links[0]['link'] = ''; 
        } 
            
        




            //---------------------------- Detail Link Info.....................
            unset($row);
			$lin = 0 ;
            $col = '';
            $detail_linkparam = $link_type = [];
            foreach ($data_qry as $key => $row) {
                $assoc_keys = array_keys( $row );
                for($d = 0; $d < $d_cnt; $d++) { 
                    $href = '' ;
                    
                    if ($dData[$d]['access'] == 'Y') { 
                        //  $mapCnt = $db->query("select count(*) as count from permission a, role_permission_details b  where  a.id in ('$permission') and role = '".$sessionRole."'")->getRowArray()['count'];
                        // echo '<pre>'; print_r($mapCnt);die;
                          if ($d_cnt > 0) {
                            if ($dData[$d]['new_win'] == 'Y') {                              
                                $href = $dData[$d]['prog']."?".$dData[$d]['link_val']."&display_id=".$display_id."&menu_id=".$menu_id."&my_menuid=".$my_menuid."&master_id=".$master_id."&user_option=".$dData[$d]['desc']."&index=".$index."&ord=".$ord."&pg=".$pg."&search_val=".$search_val;
                                for($p = 1; $p <= count($dData[$d]['param'])-1; $p++) 
                                $href .= "&".$dData[$d]['param'][$p]."=".$row[$assoc_keys[$dData[$d]['seq'][$p]]]; 
                                // $href .= "','xx')";
                            }
                            else {
                                $href = $dData[$d]['prog']."?".$dData[$d]['link_val']."&display_id=".$display_id."&menu_id=".$menu_id."&my_menuid=".$my_menuid."&master_id=".$master_id."&user_option=".$dData[$d]['desc']."&index=".$index."&ord=".$ord."&pg=".$pg."&search_val=".$search_val;
                                for($p = 0; $p <= count($dData[$d]['param'])-1; $p++) 
                                $href .= "&".$dData[$d]['param'][$p]."=".$row[$assoc_keys[$dData[$d]['seq'][$p]]]; 
                                // $href .= "'";
                               
                            }
                            $detail_link[$key][$d]['link'] = '/sinhaco/'.$href;
                            $detail_link[$key][$d]['desc'] = $dData[$d]['desc'];
                            $detail_link[$key][$d]['icon'] = $dData[$d]['link_icon'];
                            $detail_linkparam = $dData[$d]['display_id'];
                            $link_type =  $dData[$d]['link_type'];
                            
                        }
                        // else 
                        // {
                        //     $detail_link[$key][$d]['link'] = '';
                        //     $detail_link[$key][$d]['desc'] = '';
                        //     $detail_link[$key][$d]['icon'] = '';

                        //     // $detail_link[$key]['link'] = "<td class='grid_col_detail' align='center' width='{$dData[$d]['width']}'>&nbsp;</td>";
                        // } 
                    }
                    else 
                    {
                        if ($dData[$d]['new_win'] == 'Y') {                              
                            $href = $dData[$d]['prog']."?".$dData[$d]['link_val']."&display_id=".$display_id."&menu_id=".$menu_id."&my_menuid=".$my_menuid."&master_id=".$master_id."&user_option=".$dData[$d]['desc']."&index=".$index."&ord=".$ord."&pg=".$pg."&search_val=".$search_val;
                            for($p = 0; $p <= count($dData[$d]['param'])-1; $p++) 
                                $href .= "&".$dData[$d]['param'][$p]."=".$row[$assoc_keys[$dData[$d]['seq'][$p]]]; 
                            // $href .= "','xx')";
                        }
                        else {
                            $href = $dData[$d]['prog']."?".$dData[$d]['link_val']."&display_id=".$display_id."&menu_id=".$menu_id."&my_menuid=".$my_menuid."&master_id=".$master_id."&user_option=".$dData[$d]['desc']."&index=".$index."&ord=".$ord."&pg=".$pg."&search_val=".$search_val;
                            for($p = 0; $p <= count($dData[$d]['param'])-1; $p++)
                                $href .= "&".$dData[$d]['param'][$p]."=".$row[$assoc_keys[$dData[$d]['seq'][$p]]]; 
                            // $href .= "'";
                        }
                        $detail_link[$key][$d]['link'] = '/sinhaco/'.$href;
                        $detail_link[$key][$d]['desc'] = $dData[$d]['desc'];
                        $detail_link[$key][$d]['icon'] = $dData[$d]['link_icon'];
                        $detail_linkparam = $dData[$d]['display_id'];
                        $link_type =  $dData[$d]['link_type'];
                        

                    }
                } 
            
                // echo "<pre>"; print_r($row[$assoc_keys[$dData[$d]['seq'][$p]]]); die; //$row[$assoc_keys[$dData[$d]['seq'][$p]]]
                //--------------------------------------------------------------------            
                $lin++ ; 
            }   
      
        
        //echo "<pre>"; print_r($detail_link); die;


        $data = [
            "th" => $col_heading,
            "td" => $data_qry, 
            "pg" => $pg,
            "totalRecords" => $data_cnt,
            "totalPage" => $total_page,
            "heading" => $disp_heading,
            "query" => $search_val,
            "headerLink" => $header_link,
            "actionLinks" => $detail_link,
            "detail_linkparam" => $detail_linkparam,
            "link_type" => $link_type,

        ];
        $dlink=[
            "adlink" => $links,
        ];
    //echo '<pre>'; print_r($data);die;
        // $arr = menu_data();
    
        //echo '<pre>';print_r($data);die;echo '</pre>';die;
        return array_merge($data,$dlink);
        // echo view("masterList",  compact("data","arr"));
    }

    function disp_master_report ($cols_per_row, $rows_per_pg, $header, $param_name, $param_value, $disp_heading, $col_heading, $total_col) {
        $db = \config\database::connect();
        $cols = $cols_per_row; $rows = $rows_per_pg; $clause = "";

        if ($param_name[0] == '' && $param_value[0] == '')
            $param_name = $param_value = [];

        if (!empty($header['where_clause'])) {
          $clause = " where ".$header['where_clause'];
          if (!empty($param_name)) {
              for($i = 0; $i < count($param_name); $i++) 
                  $clause .= " and ".$param_name[$i]." = '".$param_value[$i]."'";
          }
        }
        else if (!empty($param_name)) {
          $clause = " where ".$param_name[0]." = '".$param_value[0]."'";
          for($i = 1; $i < count($param_name); $i++) 
            $clause .= " and ".$param_name[$i]." = '".$param_value[$i]."'";
        }
        if (!empty($header['group_by_clause']))
            $clause .= " group by ".$header['group_by_clause'];
        if (!empty($header['having_clause']))
            $clause .= " having ".$header['having_clause'];
      
        $data_sql  = " select ".$header['select_clause'];
        $data_sql .= " from ".$header['from_clause'];
        $data_sql .= $clause;
        if (!empty($header['order_by_clause']))
            $data_sql .= " order by ".$header['order_by_clause'];
        else
            $data_sql .= " order by 1";

        $data_qry = $db->query($data_sql)->getResultArray();
        $page = 0;
        
        return [
            'report_type' => true,
            'data_qry' => $data_qry,
            'page' => $page,
            'cols' => $cols,
            'rows' => $rows,
            'heading' => $disp_heading,
            'col_heading' => $col_heading,
            'total_col' => $total_col,
        ];
        // echo "<pre>"; print_r($data_qry); die;
    }

    // function view_list($code = "P069") {
        
    //     $db = \config\database::connect();
    //     $sql = "SELECT * FROM `associate_master` WHERE `associate_code`= '$code'";
    //     $data       = $db->query($sql)->getResultArray();
    //     echo "<pre>"; print_r($data); die;
    // }

    function branches($user_id) {
        $db = \config\database::connect();
        $sql = "select a.branch_code,b.branch_name from system_user_branch_permission a, branch_master b where a.user_id = '$user_id' and a.branch_code = b.branch_code order by b.branch_name";
        $data['branches'] = $db->query($sql)->getResultArray();
        
        $sql = "select a.branch_code,b.branch_name,b.branch_abbr_name,c.company_code,c.company_name,c.company_abbr_name from system_user_branch_permission a, branch_master b, company_master c where a.user_id = '$user_id' and a.default_access_ind = 'Y' and a.branch_code = b.branch_code and b.company_code = c.company_code";
        
        $data['branch_code'] = $db->query($sql)->getRowArray();
        // echo "<pre>"; print_r($data); die;

        return $data;
    }
?>