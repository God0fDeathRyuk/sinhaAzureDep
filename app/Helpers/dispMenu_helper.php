<?php

    // display menu by tree structure
    function display_menu($arr,$parent,$level = 0,$prelevel = -1, $menuHead = []) { 
        foreach($arr['leftMenus'] as $id => $data){
            if($parent == $data['menu_head']){

                if($level>$prelevel) { 
                    $style = (array_search($data['menu_head'], $arr['menuHead'])) ? "style='display: block'" : "";
                    echo '<ul '.$style.'>'; 
                }
                if($level==$prelevel) echo '</li>'; 

                if($data['menu_head'] == 0) {
                    $status = (array_search($id, $arr['menuHead'])) ? "active" : "";
                    $arrow = (array_search($id, $arr['menuHead'])) ? "bi-chevron-up" : "bi-chevron-down";
                    echo '<li class="mb-3 mnuCntnr mnmnuLst mnmnu-hovr '.$status.'"><a class="mnuMn d-inline-block w-100"><i class="fa-regular fa-folder-open me-2"></i> <span>'.$data['menu_desc'].'</span><i class="bi '.$arrow.' ms-2 arw"></i></a>'; 
                } 
                else if($data['menu_type'] == "M") {  // "M" for Main menus
                    $status = (array_search($data['menu_head'], $arr['menuHead'])) ? "active" : "";
                    $arrow = (array_search($id, $arr['menuHead'])) ? "bi-chevron-up" : "bi-chevron-down";
                    echo '<li><a class="mnuMn '.$status.'" href="javascript:void(0);"><i class="fa-regular fa-square-check me-2"></i> <span>'.$data['menu_desc'].'</span><i class="bi '.$arrow.' ms-2 arw"></i></a>'; 
                } 
                else {  // "P" for End Links
                    $url =  base_url("{$data['menu_prog']}?display_id={$data['params']}&menu_id={$id}"); $status = '';
                    if (isset($arr['menuHead'][0])) $status = ($id == $arr['menuHead'][0]) ? "text-decoration-underline dddd" : "";
                    echo '<li class="endurl-hvr '.$status.'"><a  href='.$url.'><i class="fa-solid fa-file"></i><span>'.$data['menu_desc'].'</span></a>'; 
                }
                if($level>$prelevel) $prelevel=$level; $level++;
                display_menu($arr,$id,$level,$prelevel); $level--;	
            }
        }
        if($level==$prelevel) echo '</li></ul>';
    }
    
    //dynamically fetching data from database for listing menu
    function menu_data(){
      $session = session(); 
        $db = \config\database::connect();
        $user_id=$session->userId;
        $roleId=$session->roleId;
        $data = $db->query("SELECT a.menu_head, a.menu_id, a.menu_desc, a.menu_type, a.menu_prog, a.params,a.program_screen_header_name, a.program_help_id
                FROM system_menu a, system_user_menu_permission b 
                WHERE a.menu_id = b.menu_id AND b.role = '$roleId' AND a.status_code = 'Active'
                ORDER BY a.menu_id;")->getResultArray(); 
        $arr=[];
        foreach ($data as $row) {
            $arr[$row['menu_id']]['menu_desc']=$row['menu_desc'];
            $arr[$row['menu_id']]['menu_head']=$row['menu_head'];
            $arr[$row['menu_id']]['menu_type']=$row['menu_type'];
            $arr[$row['menu_id']]['params']=$row['params'];
            $arr[$row['menu_id']]['menu_prog']=$row['menu_prog'];
        }
        return $arr;
    }

    function selected_menu() {
      $session = session();
      $db = \config\database::connect();
      $menuHead = []; $id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : $session->menuId;
      $session->set('menuId', $id); $url = '';
    
      if (isset($_REQUEST['menu_id'])) {
      	$url = base_url(substr($_SERVER['REQUEST_URI'], 8));
        
      	$urlSlice = explode(base_url().'/', $url);
      	$url = $urlSlice[0] . '/' . $urlSlice[1];
        if (strpos($url, '/master') !== false) {
          $session->set('last_selected_end_menu', $url);
        }
      } else {
      	$url = $session->requested_end_menu_url;
      }
      $session->set('requested_end_menu_url', $url);

      while ($id != 0) {
            array_push($menuHead, $id);
            $id = $db->query("SELECT `menu_head` FROM `system_menu` WHERE `menu_id` = $id")->getRowArray()['menu_head'];
        }
      return $menuHead;
    }

    function date_conv($p_date) {
        if ($p_date == '') return; 
        else {
          $len = strlen($p_date); $n   = '-';
          for($i = 0; $i < $len; $i++) {
              $char = substr($p_date,$i,1);
              if($char == '-') {$n = '-'; break;}
              if($char == '/') {$n = '/'; break;}
              if($char == '.') {$n = '.'; break;}
          }
          $date_elements = explode($n, $p_date);
          return ($date_elements[2].'-'.$date_elements[1].'-'.$date_elements[0]);
        }
    }
   
    function get_fixed_for($matter_code, $acty_date){
        $db = \config\database::connect();
    
        $result =  $db->query("select next_fixed_for from case_header where matter_code = '$matter_code' and next_date = '$acty_date' ")->getRowArray();
        return isset($result['next_fixed_for']) ? $result['next_fixed_for'] : '' ;
    }	
   
    function get_temp_id($login_id) {
        $db = \config\database::connect();

        $result = $db->query("select date_format(now(),'%Y%m%d%h%i%s') timestamp")->getRowArray() ;
        return isset($result['timestamp']) ? "sinhaco_temp."."zz_".$login_id."_".$result['timestamp'] : '' ;
    }

    function get_code_desc($typecd,$codecd) {
        $db = \config\database::connect();

        $result = $db->query("select code_desc from code_master where type_code = '$typecd' and code_code = '$codecd' ")->getRowArray() ;
        return isset($result['code_desc']) ? $result['code_desc'] : '' ;
    }

    function get_initial_name($intlcd) {
      $db = \config\database::connect();
    
      $result = $db->query("select initial_name from initial_master where initial_code = '$intlcd' " )->getRowArray();
      return isset($result['initial_name']) ? $result['initial_name'] : '' ;
    }

    function get_parameter_value($paramcd){
      $db = \config\database::connect();

      $result  = $db->query("select parameter_value from system_parameter where parameter_code = '$paramcd' ")->getRowArray() ;
      return isset($result['parameter_value']) ? $result['parameter_value'] : '' ;
    }

    function get_attention_name($attn_code){
      $db = \config\database::connect();
      
      $result  = $db->query("select attention_name from client_attention where attention_code = '$attn_code'")->getRowArray() ;
      // echo '<pre>';print_r($result);die;
      return isset($result['attention_name']) ? $result['attention_name'] : '' ; $attn_name ;
    } 
   
    function text_justify($text_desc,$tot_char) {
        $total_str_len = strlen($text_desc);
        if(substr($text_desc,($total_str_len-6)) == "<br />") {
          $t_desc = $text_desc . substr($text_desc,($total_str_len-6));
        } else {
          $total_space   = $tot_char - $total_str_len;
          $str_array     = explode(' ',$text_desc);
          $str_array_cnt = count($str_array);
          for ($i=0;$i<$str_array_cnt-1;$i++) {
            $str_array[$i] = $str_array[$i] . '&nbsp;' ;
          }
          $cnt = 0 ;
          for($i=1;$i<=$total_space;$i++) {
            if($cnt>$str_array_cnt-2) {
              $cnt = 0 ;
            }
            $str_array[$cnt] = $str_array[$cnt] . '&nbsp;';
            $cnt = $cnt+1;
          }
          $t_desc = implode('',$str_array);
        }
        return $t_desc;
    }


    function get_fin_year($param_date) {
        if($param_date == '') return;
        else {
            $len = strlen($param_date);
          for($i = 0; $i < $len; $i++) {
            $char = substr($param_date,$i,1);
            if($char == '-') { $sep = '-';break; }
            if($char == '/') { $sep = '/';break; }
            if($char == '.') { $sep = '.';break; }
          }
          $d_array = explode($sep,$param_date);
          if(strlen($d_array[0]) == 4) {
            if(($d_array[1]*1) >= 4) { return $d_array[0].'-'.($d_array[0]+1); } else { return ($d_array[0]-1).'-'.$d_array[0]; }
          } else if(strlen($d_array[2]) == 4) {
            if(($d_array[1]*1) >= 4) { return $d_array[2].'-'.($d_array[2]+1); } else { return ($d_array[2]-1).'-'.$d_array[2]; }
          } else return; 
        }
      }

      function currency_format($num) {
          $chk_num = is_numeric($num); 
          $neg_ind = 0;
          $dec_ind = 1;
          $val     = 0;
          if($chk_num == 1)
          {
              if($num < 0)
              {
                  $neg_ind = 0;
                  $val  = substr($num,1);
              }
              elseif($num >= 0) { $val = $num; $neg_ind = 1; }

                $dec_pos = strpos($val,"."); if($dec_pos == NULL) { $dec_ind = 0; } else { $dec_ind = 1; }

              if($dec_ind == 1)
              {
                  $mantissa = substr($val,0,$dec_pos); 
                  $decimal  = substr($val,$dec_pos); 
              }
              else { $mantissa = $val; $decimal = NULL; }

              $length       = strlen($mantissa);
              $mantissa_rev = strrev($mantissa); 

              $str = '';
              $k = 0;
              $l = 0;
              $gap = 1;
              $flag = 0;
              if($length > 3)
              {
                $flag = 0;
                for($i = 1; $i <= $length ; $i++)
                {
                    $gap = $length - $i;
                    if($i == 3) {  $l = 3;  $str = substr($mantissa_rev,$k,$l).',';  }
                    elseif($i > 3  && $i <= $length) 
                    { 
                        if(($i % 2) == 0 )
                        {
                            $k = $k + $l;
                            $l = 2;
                            if($gap > 1) { $str .= substr($mantissa_rev,$k,$l).',';  }
                          else {         $str .= substr($mantissa_rev,$k,$l);      }
                        }
                    }
                }
              }
              else { $flag = 1; }
              if($dec_ind == 1) { $new_currency = strrev($str).$decimal; } else { $new_currency = strrev($str); }
              if($neg_ind == 0) { $new_currency = '-'.$new_currency; }
              if($flag == 1 && $neg_ind == 0) { $new_currency = '-'.$mantissa.$decimal; } elseif ($flag == 1 && $neg_ind == 1) { $new_currency = $mantissa.$decimal; }
          }
          else { $new_currency = NULL;}
          return $new_currency;
    }
  
    function int_to_words($x) { 
      $nwords = array("Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", 
                "Nineteen", "Twenty", 30 => "Thirty", 40 => "Forty", 50 => "Fifty", 60 => "Sixty", 70 => "Seventy", 80 => "Eighty", 90 => "Ninety" );  
        if(!is_numeric($x)) { 
            $w = '#'; 
        } else if($x%1 != 0) { 
            $w = '#'; 
        } else { 
            if($x < 0) { 
                $w = 'minus '; 
                $x = -$x; 
            } else { 
                $w = ''; 
            } if($x < 21) { 
                $w .= $nwords[$x]; 
            } else if($x < 100) { 
                $w .= $nwords[10 * floor($x/10)]; 
                $r = round($x - (floor($x/10) * 10),0);
                if($r > 0) { $w .= ' '. $nwords[$r]; } 
            } else if($x < 1000) { 
                $w .= $nwords[floor($x/100)] .' Hundred'; 
                $r = $x - (floor($x/100) * 100);
                if($r > 0) { 
                    $w .= ' '. int_to_words($r); 
                } 
            } else if($x < 100000) { 
                $w .= int_to_words(floor($x/1000)) .' Thousand'; 
                $r = $x - (floor($x/1000) * 1000);
                if($r > 0) { 
                    $w .= ' '; 
                    $w .= int_to_words($r); 
                } 
            } else if($x < 10000000) { 
                $w .= int_to_words(floor($x/100000)) .' Lac'; 
                $r = ($x%100000);  
                if($r > 0) { 
                    $w .= ' '; 
                    $w .= int_to_words($r); 
                } 
            } else { 
                $w .= int_to_words(floor($x/10000000)) .' Crore';  
                $r = $x - (floor($x/10000000) * 10000000);
                if($r > 0) { 
                    $w .= ' '; 
                    $w .= int_to_words($r); 
                } 
            } 
        } 
        return $w; 
    } 
    
    function account_master_check() {
      $main_ac_code =document.getElementById('main_ac_code');
      $main_ac_desc =document.getElementById('main_ac_desc');
      $act_group_code =document.getElementById('act_group_code');
      $act_group_desc =document.getElementById('act_group_desc');

      if($main_ac_code =='') {
        showErrorMessage('',41);
        document.getElementById('main_ac_code').focus();
        return false;
      } else if($main_ac_desc =='') {
        showErrorMessage('',13);
        document.getElementById('main_ac_desc').focus();
        return false;
      } else if($act_group_code == "" || $act_group_desc == "") {
            showErrorMessage('',14);
            document.getElementById('main_ac_desc').focus();
            return false;
      }
      document.getElementById('submit').removeAttribute("disabled");
    }
  
    //=============================== FUNCTION billrate =============================//
    function billrate($counsel_code,$client_code,$matter_code,$activity_code) {
      $db = \config\database::connect();
        $rate = 0.00;
        $clause = "counsel_code  = '".$counsel_code."'  AND  client_code   = '".$client_code."'  AND  matter_code   = '".$matter_code."'  AND  activity_code = '".$activity_code."'";
        $condition = "SELECT  * FROM  billing_rate WHERE  $clause";

        $res = $db->query($condition)->getResultArray();
        $numRow1 = count($res);
        
        if($numRow1 > 0) {
          foreach($res as $row) {
            $rate = $row['rate'];
            return array($rate,$clause);
          }
        } else {
          $clause = "counsel_code  = '".$counsel_code."'  AND  client_code   = '".$client_code."'  AND  matter_code   = '".$matter_code."'  AND  activity_code = 'A00'";
          $condition = "SELECT  * FROM  billing_rate WHERE  $clause";
          
          $res = $db->query($condition)->getResultArray();
          $numRow2 = count($res);
          
          if($numRow2 > 0) {
            foreach($res as $row) {
              $rate = $row['rate'];
              return array($rate,$clause);
            }
          } else {
            $clause = "counsel_code  = '".$counsel_code."'  AND  client_code   = '".$client_code."'  AND  matter_code   = '000000'  AND  activity_code = '".$activity_code."'";
            $condition = "SELECT  * FROM  billing_rate WHERE  $clause";
            
            $res = $db->query($condition)->getResultArray();
            $numRow3 = count($res);
            
            if($numRow3 > 0) {
              foreach($res as $row) {
                $rate = $row['rate'];
                return array($rate,$clause);
              }
            } else {
              $clause = "counsel_code  = '".$counsel_code."'  AND  client_code   = '".$client_code."'  AND  matter_code   = '000000'  AND  activity_code = 'A00'";
              $condition = "SELECT  * FROM  billing_rate WHERE  $clause";
              
              $res = $db->query($condition)->getResultArray(); 
              $numRow4 = count($res);

              if($numRow4 > 0) {
                foreach($res as $row) {
                  $rate = $row['rate'];
                  return array($rate,$clause);
                }
              }
              else {
                $clause = "counsel_code  = '".$counsel_code."'  AND  client_code   = '000000'  AND  matter_code   = '000000'  AND  activity_code = '".$activity_code."'";
                $condition = "SELECT  * FROM  billing_rate WHERE  $clause";
                                                      
                $res = $db->query($condition)->getResultArray();
                $numRow5 = count($res);
                
                if($numRow5 > 0) {
                  foreach($res as $row) {
                    $rate = $row['rate']; 
                    return array($rate,$clause);
                  }
                }
              else {
                $clause = "counsel_code  = '".$counsel_code."'  AND  client_code   = '000000'  AND  matter_code   = '000000'  AND  activity_code = 'A00'";
                $condition = "SELECT  * FROM  billing_rate WHERE  $clause";
                                                                
                $res = $db->query($condition)->getResultArray();
                $numRow6 =count($res);

                if($numRow6 > 0) {
                  foreach($res as $row) {
                    $rate = $row['rate'];
                    return array($rate,$clause);
                  }
                } else {
                    $rate = 0.00; 
                    return array($rate,$clause);             
                }
              }
            }
          }
        }
      }
    }

    function get_last_doc_no($fin_year,$branch_code,$daybook_code,$month_code,$dr_cr_ind) {
      $db = \config\database::connect();
      $serial_number_sql = "select last_serial_no from serial_number where ifnull(fin_year,'') = '$fin_year' and ifnull(branch_code,'') = '$branch_code' and ifnull(daybook_code,'0') = '$daybook_code' and ifnull(month_code,'') = '$month_code' and ifnull(dr_cr_ind,'') = '$dr_cr_ind'" ;

      $serial_no_row = $db->query($serial_number_sql)->getResultArray() ;
                              
      if($serial_no_row < 1) {
        $inst_serial_number = "insert into serial_number (fin_year,branch_code,daybook_code,month_code,dr_cr_ind,last_serial_no) values ('$fin_year','$branch_code','$daybook_code','$month_code','$dr_cr_ind',0)";
        $inst_serial_qry = $db->query($inst_serial_number);
      }

      $updt_serial_stmt = "update serial_number set last_serial_no = last_serial_no + 1 where fin_year = '$fin_year' and branch_code = '$branch_code' and daybook_code = '$daybook_code'  and month_code = '$month_code' and dr_cr_ind = '$dr_cr_ind' " ;
      $updt_serial_qry = $db->query($updt_serial_stmt);

      $last_id_qry = $db->query($serial_number_sql)->getRowArray();
      $last_doc_no = $last_id_qry['last_serial_no'];

      //----------------------------------- Ledger Doc No -------------------------------------
      if($dr_cr_ind == 'D' || $dr_cr_ind == 'C') {
        $temp_no = str_pad($last_doc_no,4,'0',STR_PAD_LEFT);
        $doc_no  = $month_code.$temp_no;
      } else if($daybook_code == 'TD' && $dr_cr_ind == 'X') {
        $doc_no  = $last_doc_no ;
      } else {
        $doc_no  = str_pad($last_doc_no,6,'0',STR_PAD_LEFT);
      }
      return $doc_no;
    }
    
      //----- Current Financial Year 
  function get_current_fin_year() {
    $db = \config\database::connect();
    $current_date_qry = $db->query("select date_format(now(),'%d-%m-%Y') AS formatted_date")->getRowArray();
     $current_date      = $current_date_qry['formatted_date'];
     $current_day       = substr($current_date,0,2) ;
     $current_month     = substr($current_date,3,2) ;
     $current_year      = substr($current_date,6,4) ;

     if($current_month >= 4 and $current_month <= 12) {
        $curr_fy1 = $current_year ;
        $curr_fy2 = $current_year + 1 ;
     }
     if($current_month >= 1 and $current_month <= 3) {
        $curr_fy1 = $current_year - 1  ;
        $curr_fy2 = $current_year  ;
     }

     $fin_year = $curr_fy1 . '-' . $curr_fy2 ;
     return $fin_year ;
  }

	// remove /sinhaco from url
	function remove_char_from_uri($char = '/sinhaco/') {
    	// Get the current request URI
		$currentURI = $_SERVER['REQUEST_URI'];

		// Check if /sinhaco/ is present in the URI
		if (strpos($currentURI, $char) !== false) {
    		// Remove /sinhaco/ from the URI
    		$modifiedURI = str_replace($char, '/', $currentURI);
		} else {
   			// No change is needed
    		$modifiedURI = $currentURI;
		}
		return $modifiedURI;
    }
    
    
  /* ================= Finance module function [Added by Surajit Naskar on 22-01-2024] ================== */
  //----- Last Document No 
  function getLastDocNo($fin_year, $branch_code, $daybook_code, $month_code, $dr_cr_ind) {
    $db = \config\database::connect();

    $serial_number_sql = "select last_serial_no from serial_number
                          where ifnull(fin_year,'')      = '$fin_year'
                            and ifnull(branch_code,'')   = '$branch_code'
                            and ifnull(daybook_code,'0') = '$daybook_code' 
                            and ifnull(month_code,'')    = '$month_code'
                            and ifnull(dr_cr_ind,'')     = '$dr_cr_ind'" ;
    $serial_no_row = count($db->query($serial_number_sql)->getResultArray());

    if($serial_no_row < 1) {
      $inst_serial_number = "insert into serial_number (fin_year,branch_code,daybook_code,month_code,dr_cr_ind,last_serial_no)                                 
                            values ('$fin_year','$branch_code','$daybook_code','$month_code','$dr_cr_ind',0)";
      $db->query($inst_serial_number);
    }

    $updt_serial_stmt = "update serial_number set last_serial_no = last_serial_no + 1
                          where fin_year      = '$fin_year'
                            and branch_code   = '$branch_code'
                            and daybook_code  = '$daybook_code' 
                            and month_code    = '$month_code'
                            and dr_cr_ind     = '$dr_cr_ind' " ;
    $db->query($updt_serial_stmt);

    $last_id_qry = $db->query($serial_number_sql)->getRowArray();
    $last_doc_no = $last_id_qry['last_serial_no'];

      //----------------------------------- Ledger Doc No -------------------------------------
      if($dr_cr_ind == 'D' || $dr_cr_ind == 'C') {
        $temp_no = str_pad($last_doc_no,4,'0', STR_PAD_LEFT);
        $doc_no  = $month_code.$temp_no;

      } else if($daybook_code == 'TD' && $dr_cr_ind == 'X') {
        $doc_no  = $last_doc_no ;
      } else {
        $doc_no  = str_pad($last_doc_no,6,'0',STR_PAD_LEFT);
      }

      return $doc_no;
  }

  //----- Get Financial Year
  function getFinYear($param_date) {
    if($param_date == '')
    {
      return;
    }
    else
    {
      $len = strlen($param_date);
	  for($i = 0; $i < $len; $i++)
	  {
	    $char = substr($param_date,$i,1);
	    if($char == '-') { $sep = '-';break; }
	    if($char == '/') { $sep = '/';break; }
	    if($char == '.') { $sep = '.';break; }
	  }
      $d_array = explode($sep,$param_date);
      if(strlen($d_array[0]) == 4)
      {
        if(($d_array[1]*1) >= 4) { return $d_array[0].'-'.($d_array[0]+1); } else { return ($d_array[0]-1).'-'.$d_array[0]; }
      }
      else if(strlen($d_array[2]) == 4)
      {
        if(($d_array[1]*1) >= 4) { return $d_array[2].'-'.($d_array[2]+1); } else { return ($d_array[2]-1).'-'.$d_array[2]; }
      }
      else
      {
        return;
      }
    }
  }

  //----- Get Client Name  
  function getClientName($clnt_code) {
    $db = \config\database::connect();

    $clnt_qry  = $db->query("select client_name from client_master where client_code = '$clnt_code' ")->getRowArray();
    $clnt_name = isset($clnt_qry['client_name']) ? $clnt_qry['client_name'] : '';
    return $clnt_name ;
  }
  
  //----- Get Matter Description  
  function getMatterDesc($clnt_code,$matr_code) {
    $db = \config\database::connect();

    if($clnt_code == $matr_code) {
	    $matr_desc = 'ADVANCE ...';
    } else {
      $clnt_qry  = $db->query("select if(matter_desc1 != '', concat(matter_desc1,' : ',matter_desc2), matter_desc2) matter_desc from fileinfo_header where matter_code = '$matr_code' ")->getRowArray();
      $matr_desc = isset($clnt_qry['matter_desc']) ? $clnt_qry['matter_desc'] : '';
    }
	  return $matr_desc ;
  } 

  //----- Get Matter Initial
  function getMatterInitial($matr_code) {
    $db = \config\database::connect();

    $matter_qry = $db->query("select initial_code from fileinfo_header where matter_code = '$matr_code' ")->getRowArray();
	  $initial_code = isset($matter_qry['initial_code']) ? $matter_qry['initial_code'] : ''; 
    if ($initial_code == '') { $initial_code = 'PS' ; }
      return $initial_code ;
  } 

  //----- Get Associate Name  
  function getAssociateName($associate_code) {
    $db = \config\database::connect();

    $associate_qry  = $db->query("select associate_name from associate_master where associate_code = '$associate_code' ")->getRowArray();
    $associate_name = isset($associate_qry['associate_name']) ? $associate_qry['associate_name'] : '';
    return $associate_name ;
  } 

  //----- Get Associate PAN  
  function getAssociatePan($associate_code) {
    $db = \config\database::connect();

    $associate_pan_qry = $db->query("select pan_no from associate_master where associate_code = '$associate_code' ")->getRowArray();
    $associate_pan = isset($associate_pan_qry['pan_no']) ? $associate_pan_qry['pan_no'] : '';
    return $associate_pan ;
  } 

  //----- Get Branch Name (for Ledger View)
  function getBranchName($brch_code) {
    $db = \config\database::connect();

    $my_qry1   = $db->query("select branch_name from branch_master where branch_code = '$brch_code' ")->getRowArray();
    $brch_name = isset($my_qry1['branch_name']) ? $my_qry1['branch_name'] : '';
    return $brch_name ;
  } 
    //----- Get Client Name (for Ledger View) added on 29-01-2024 by Surajit Naskar
    function get_client_name($clnt_code) {
      $db = \config\database::connect();
    
      $result  = $db->query("select client_name from client_master where client_code = '$clnt_code' ")->getRowArray() ;
      return isset($result['client_name']) ? $result['client_name'] : '' ; 
    } 
    
      //----- Generate Dynamic Excel Columns
  function columnFromIndex($number){
    if($number === 0)
        return "A";
    $name='';
    while($number>0){
        $name=chr(65+$number%26).$name;
        $number=intval($number/26)-1;
        if($number === 0){
            $name="A".$name;
            break;
        }
    }
    return $name;
  }
?>