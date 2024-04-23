<?= $this->extend("layouts/master") ?>  

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>

<main id="main" class="main">
<?php if (!isset($bill_row)) { ?>
    <?php if (session()->getFlashdata('message') !== NULL) : ?>
        <div id="alertMsg">
            <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
            <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="pagetitle">
        <h1>Bill Printing (Final)</h1>       
    </div>
  <form action="" method="post">
  <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
      <div class="frms-sec d-inline-block w-100 bg-white p-3">
          <div class="d-inline-block w-100">
              <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
                  <label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
                  <select class="form-select cstm-inpt" name="branch_code">
                  <?php foreach($data['branches'] as $branch) { ?>
                      <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                  <?php } ?>
                  </select>
              </div>
              <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-4">
                  <label class="d-inline-block w-100 mb-2 lbl-mn">Bill For <strong class="text-danger">*</strong></label>
                  <select class="form-select" name="client_matter" onchange="selectLookup(this)" <?= isset($req_params) ? 'disabled' : '' ?> required>
                      <option value="">- Select -</option>
                      <option value="Range" <?= isset($req_params) ? ($req_params['client_matter'] == 'Range') ? 'selected' : '' : '' ?>>Range</option>
                      <option value="Client" <?= isset($req_params) ? ($req_params['client_matter'] == 'Client') ? 'selected' : '' : '' ?>>Client</option>
                      <option value="Matter" <?= isset($req_params) ? ($req_params['client_matter'] == 'Matter') ? 'selected' : '' : '' ?>>Matter</option>
                  </select>
              </div>
              <div class="frms-sec-insde d-block float-start col-md-7 ps-2 mb-4">
                  <label class="d-inline-block w-100 mb-2 lbl-mn">&nbsp;</label>
                  <span class="float-start mt-2">From</span>
                  <input class="form-control w-42 float-start ms-2" id="rangeFrom" oninput="this.value = this.value.toUpperCase();" value="<?= isset($req_params) ? $req_params['range_from'] : '' ?>" type="text" name="range_from" onBlur="myRangeTo()" disabled/>
                  <span class="float-start mt-2 ms-2">To</span>
                  <input class="form-control w-42 float-start ms-2" id="rangeTo" oninput="this.value = this.value.toUpperCase();" value="<?= isset($req_params) ? $req_params['range_to'] : '' ?>" type="text" name="range_to" disabled/>
              </div>										
          </div>
          <div class="col-md-3 float-start px-2 ps-0 mb-3">
              <label class="d-inline-block w-100 mb-2 lbl-mn ps-2">Bill Date</label>
              <input type="text" placeholder="dd-mm-yyyy"  class="form-control float-start w-100 ms-2 set-date datepicker" name="final_bill_date"  id="finalBillDate" value="<?= isset($params) ? $params['final_bill_date'] : date('d-m-Y') ?>" onBlur="make_date(this)"/>
          </div>
          <div class="col-md-3 float-start px-2 position-relative mb-3">
              <label class="d-inline-block w-100 mb-2 lbl-mn">Format</label>
              <select class="form-select" name="bill_format" id="billFormat" readonly <?= isset($req_params) ? 'disabled' : '' ?>>
                  <option value="S">Service Tax</option>
              </select>
          </div>
          <div class="col-md-6 float-start px-2 mb-3" id="lookupBtn">            
              <div class="float-start px-2 position-relative mb-3 w-35">
                  <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                  <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" value="<?= isset($req_params) ? $req_params['input_code'] : '' ?>" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" readonly/>
              </div>
              <input type="text" class="form-control ms-3 float-start mt-31 w-60" name="input_name" id="inputName" value="<?= isset($req_params) ? $req_params['input_name'] : '' ?>" readonly/>
          </div>
          <?php if(!isset($req_params)) { ?> <button type="submit" class="btn btn-primary cstmBtn mt-31">Proceed</button> 
            <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-31 ms-2 cstmPdng">Reset</button>
          <?php } ?>
      
  </form>

<?php if (isset($report)) { ?>
<form action="" method="post" id="billPrintingFinal">
    <input type="hidden" id="bill_count" value="<?= $params['bill_cnt']?>" >
    <input type="hidden" id="final_bill_date" name="final_bill_date" value="<?= $params['final_bill_date']?>" >
        <div class="d-inline-block w-100 mt-4">
					<div class="d-inline-block w-100 scrlTblMd">
					    <input type="hidden" name="bill_str" id="bill_str" size="2" value='' readonly/>
						<table class="table table-bordered tblhdClr" id="listTable">
							<tbody>
								<tr>
									<th>
										<span></span>
									</th>
									<th>
										<span class="fntSml">Bill No</span>
									</th>
									<th>
										<span class="fntSml">Bill Date</span>
									</th>
									<th>
										<span class="fntSml">Client</span>
									</th>
									<th>
										<span class="fntSml">Name</span>
									</th>
									<th>
										<span class="fntSml">Matter</span>
									</th>
									<th>
										<span class="fntSml">Description</span>
									</th>
									<th>
										<span class="fntSml">Ind</span>
									</th>
								</tr>
                  <?php foreach($report as $key => $row) { 
                    $count=count($report);?> 
                                  
								<tr>
                  <td>
										<span></span>
									</td>
									<td>
										<span><?= $row['bill_number'] ?></span>
									</td>
									<td>
										<span><?= $row['bill_date'] ?></span>
									</td>
									<td>
										<span><?= $row['client_code'] ?></span>
									</td>
									<td style="width:15%;">
										<span><?= $params['client_name'] ?></span>
									</td>
									<td class="brkwrd" style="width:20%;">
										<span><?= $row['matter_code'] ?></span>
									</td>
									<td>
										<span><?= $params['matter_name'] ?></span>
									</td>
									<td>
                    <input type="checkbox" class="child-checkbox" id="print_ind<?php echo $key+1?>" name="print_ind<?php echo $key+1?>" value="Y" >
									</td>
                  <td>
								<input type='hidden' value="<?= $row['serial_no'] ?>" name="bill_serial_no<?php echo $key+1?>" id="bill_serial_no<?php echo $key+1?>"/>
                                <input type="hidden" name="row_count" id="rowCount" size="2" value="<?= $count; ?>" readonly>
                  </td>
								</tr> 
              <?php } ?>
							</tbody>
						</table>
					</div>

					<div class="d-block w-100 mt-1">
						<button type="submit" class="btn btn-primary cstmBtn mt-3 float-start" id="btnLaser" onclick="formOption('/billing/printing-final/', 'laser', 'billPrintingFinal'); bill_data_check('W')">Laser</button>
						<button type="button" class="btn btn-primary cstmBtn mt-3 float-start ms-2" disabled>Dot Matri</button>
						<button type="button" class="btn btn-primary cstmBtn mt-3 float-start ms-2" >PDF</button>
						<a href="<?= base_url($data['requested_url']) ?>" class="btn btn-primary cstmBtn mt-3 float-start ms-2">Back</a>
            
						
						<div class="d-block float-start mt-3 ms-2 cstmRdobtn mb-1">
							<input type="radio" id="select_all"  name="Select" onClick="myselect('S')" />
							<label for="slctAl" class="ms-2">Select All</label>
						</div>
						<div class="d-block float-start mt-3 ms-2 cstmRdobtn mb-1">
							<input type="radio" id="select_all" name="Select" onClick="myselect('D')"/>
							<label for="deslctAl" class="ms-2">De Select All</label>
						</div>
						<div class="d-block float-start mt-3 ms-2 cstmRdobtn mb-1">
							<input type="radio" id="duplInd" name="dupl_ind"/>
							<label for="dpltbl" class="ms-2">Duplicate Bill</label>
						</div>
						<div class="d-block float-start mt-3 ms-2 cstmRdobtn mb-1">
							<input type="radio" id="copyInd" name="copy_ind"/>
							<label for="Cpy" class="ms-2">Copy</label>					
						</div>
						<div class="d-block float-start mt-3 cstmRdobtn mb-1 ms-2">
							<input type="radio" id="revdInd" name="revd_ind"/>
							<label for="rvcdbl" class="ms-2">Revised Bill</label>
						</div>	
						<div class="d-block float-start mt-3 ms-2 cstmRdobtn mb-1">
							<input type="radio" id="propInd" name="prop_ind"/>
							<label for="qtnbl" class="ms-2">Quotation Bill</label>
						</div>	
						<div class="d-block float-start mt-3 ms-2 cstmRdobtn mb-1">
							<input type="radio" id="recdInd" name="recd_ind"/>
							<label for="amrcd" class="ms-2">Amount Recd</label>
						</div>					
					</div>			
				</div>		
</form>
<?php } } else if(isset($bill_row)) { ?>
  <form action="" method="post" id="">
      <a href="<?= base_url($data['requested_url']) ?>" class="text-decoration-none d-block float-end btn btn-dark me-5">Back</a>
      
      <?php $page_no = 1;
      foreach($params as $key => $param) {
      ?>
      <div class="mntblDv">
      <table width="750" style="margin-left:140px;" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td class="cellheight_1" width="" colspan="6">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tbody>
                    <tr style="line-height:2px">
                    <td width="120">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="120">&nbsp;</td>
                  </tr>
                  <tr style="line-height:80px">
                    <td valign="top">
                    
                    <td valign="top">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody><tr><td align="center"><img src="<?= base_url('public/assets/img/logo.jpg') ?>" width="155" height="65" border="0"></td></tr>
                    </tbody></table>
                    </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td class="GroupDetail_band_portrait" style="font-size:12px;" align="center"><b>5 KIRAN SHANKAR ROY ROAD, KOLKATA - 700001</b></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td class="GroupDetail_band_portrait" style="font-size:12px;" align="center"><b>TEL : 22438377, 22101617, 22101625     FAX : 22436176</b></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td class="GroupDetail_band_portrait" style="font-size:12px;" align="center"><b>E-Mail : sinhaco@sinhaco.com/sinhacoadvocates@gmail.com</b></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr><td colspan="3"><hr size="3" noshade="noshade"></td></tr>
                </tbody>
              </table>
            </td>
          </tr>  
          <tr>
            <td class="GroupDetail_band_portrait w-15">&nbsp;<?php if($param['prop_ind'] != 'on') { echo 'Bill No' ; } else { echo 'Ref No' ; }?></td>
            <td class="ReportColumn_portrait w-20">&nbsp;<?php echo $bill_row[$key]['fin_year'].'/'.$bill_row[$key]['bill_no'];?></td>
          </tr>
          <tr>
            <td class="GroupDetail_band_portrait w-15">&nbsp;<?php if($param['prop_ind'] != 'on') { echo 'Bill Date' ; } else { echo 'Ref Date' ; }?></td>
            <td class="ReportColumn_portrait w-20">&nbsp;<?php if(empty($param['final_bill_date'])) echo $bill_row[$key]['bill_date']; else echo $param['final_bill_date'];?></td>
            <td class="ReportTitle_portrait" align="center"><font size="+1"><b><?php if($param['dupl_ind']=='on') {echo 'DUPLICATE ';} if($param['revd_ind']=='on') {echo 'REVISED ';} if($param['prop_ind']=='on') {echo 'PROPOSE / QUOTATION ';} if($param['copy_ind']=='on') {echo 'COPY ';}?>BILL</b></font></td>
            <td class="GroupDetail_band_portrait" colspan="3" align="right" colspan="2">Page :&nbsp;<?php echo $page_no;?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="6"><hr size="1" noshade="noshade"></td>
          </tr>
      </table>
      <div class="tblmn2">
        <table width="750" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <!-- client detail -->
              <td width="375" height="180" style="vertical-align:text-top">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <?php if ($bill_row[$key]['client_code'] == 'K00068') { ?>
                    <tr>
                    <td height="15" class="GroupDetail_band_portrait"><?php echo 'THE CHIEF LAW OFFICER'?></td>
                    </tr>
            <?php } ?>
            <?php if($param['trust_name'] != '') {?>
                        <tr>
                            <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $trust_name;?></td>
                        </tr>
            <?php } ?>
            <?php if(!empty($param['client_name'])) { ?> 
                        <tr>
                            <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $param['client_name'];?></td>
                        </tr>
            <?php } ?>
                  <?php if(!empty($cadr_row[$key]['address_line_1'])) { ?>              
                  <tr>
                    <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $cadr_row[$key]['address_line_1'];?></td>
                  </tr>
                  <?php } if(!empty($cadr_row[$key]['address_line_2'])) { ?>              
                  <tr>
                    <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $cadr_row[$key]['address_line_2'];?></td>
                  </tr>
                  <?php } if(!empty($cadr_row[$key]['address_line_3'])) { ?>              
                  <tr>
                    <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $cadr_row[$key]['address_line_3'];?></td>
                  </tr>
                  <?php } if(!empty($cadr_row[$key]['address_line_4'])) { ?>              
                    <tr>
                      <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $cadr_row[$key]['address_line_4'];?></td>
                    </tr>
                    <?php } if(!empty($cadr_row[$key]['address_line_4'])) { ?>
                  <tr>
                    <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $cadr_row[$key]['city'] .' '.' - '.$cadr_row[$key]['pin_code'];?></td>
                  </tr>
            <?php } if($param['pan_no'] != '') {?>
                  <tr>
                    <td height="15" class="GroupDetail_band_portrait">&nbsp;<b><?php echo 'PAN : ' .' '.$param['pan_no'];?></b></td>
                  </tr>
            <?php } ?>

            <?php if($param['state_name'] != '') {?>
                  <tr>
                    <td height="15" class="GroupDetail_band_portrait">&nbsp;<b><?php echo strtoupper(stripslashes($param['state_name']));?></b></td>
                  </tr>
            <?php }  ?>
            <?php if($bill_row[$key]['b_d'] >= '2017-07-01' || date_conv($param['final_bill_date']) >= '2017-07-01') {?>	
                
                    <?php if($param['client_gst'] != '') {?>
                    <tr>
                        <td height="15" class="GroupDetail_band_portrait">&nbsp;<b><?php echo 'STATE CODE : ' .' '.strtoupper(stripslashes($param['gst_zone_code']));?></b></td>
                    </tr>
                    <?php } ?>

                    <?php if($param['client_gst'] != '') {?>
                    <tr>
                        <td height="15" class="GroupDetail_band_portrait">&nbsp;<b><?php echo 'GSTIN : ' .' '.strtoupper(stripslashes($param['client_gst'])) ;?></b></td>
                    </tr>
                    <?php } ?>

            <?php } ?>
      
            <?php if ($bill_row[$key]['attention_code'] != '373') { ?>

                        <tr>
                            <td valign="middle" height="90" class="GroupDetail_band_portrait">&nbsp;
                            <p>&nbsp;<?php if ($bill_row[$key]['attention_code'] != '0') echo 'Attn. : '.$param['attention_name'];?></p>
                            <p>&nbsp;<?php if($param['designation']!='') echo 'Designation : '.$param['designation']; else echo' ';?></p></td>
                        </tr>
            <?php } ?>

                </table>
              </td>
              <!-- end of client detail -->

              <!-- ref and subj -->
              <td width="375">
                <table width="100%" cellpadding="0" cellspacing="0">
          <?php if($bill_row[$key]['fin_year'] == '2015-2016' && $bill_row[$key]['bill_no'] == 'GR71') { ?>
                  <tr>
                    <td height="20" width="06%" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;">&nbsp;</td>
                    <td height="20" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><b><?php echo 'In the High Court at Bombay';?> </b></td>
                  </tr>
          <?php } ?>  
                  <tr>
                    <td height="20" width="06%" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><?php echo 'Re : ';?></td>
                    <td height="20" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><?php echo $param['matter_name'];?></td>
                  </tr>
                  <tr>
                    <td height="70">&nbsp;</td>
                    <td height="70" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><?php echo $param['other_case_desc'];?></td>
                  </tr>
                  <tr>
                    <td height="15" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><?php if($param['source_code']=='C') { echo ' ' ; } else { echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' ; }?></td>
                    <td height="15" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><?php echo $param['reference_desc'];?></td>
                  </tr>
                  <tr>
                    <td height="75" class="GroupDetail_band_portrait w-15" style="vertical-align:text-top; text-align:justify;">Sub : &nbsp;</td>
                    <td height="75" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><?php echo strtoupper($param['subject_desc']);?></td>
                  </tr>
                </table>
              </td>
              <!-- end of ref and subj -->
            </tr>
          </table>
          <table width="750" class="mt-4" cellpadding="0" cellspacing="0" border="1" bordercolor="#666666" bordercolordark="#FFFFFF" bordercolorlight="#FFFFFF">
          <tr>
          <td>
          <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td class="GroupDetail_band_portrait">&nbsp;<b class="ps-2">Date</b></td>
              <td class="GroupDetail_band_portrait">&nbsp;<b>Details</b></td>
                  <td class="GroupDetail_band_portrait pt-3" width="" align="right"><b class="pe-2 tblTplst">Professional Fees<b>(<img src="<?= base_url('public/assets/img/rupee.jpg') ?>" height="8" border="0">)</b><br>
                  </b>&nbsp;</td>
                  <td class="GroupDetail_band_portrait pt-3" width="" align="right"><b class="pe-2 tblTplst">Reimbursement<b><b>(<img src="<?= base_url('public/assets/img/rupee.jpg') ?>" height="8" border="0">)</b></b><br>
                  </b>&nbsp;</td>
                
            </tr>
            <?php
                $l_no = 25;
                $tot_inp_amount  = 0;
                $tot_out_amount  = 0;
                $tot_tax_amount  = 0; 
                $tot_tot_amount  = 0;
                $tot_net_amount  = 0;
                $tot_srv_amount  = 0;
                $rowcnt = 1 ; 
                // echo "<pre>"; print_r($sele_qry); die;
                //$index   = 0;
                $dtl_row = isset($sele_qry[$key][$rowcnt-1]) ? $sele_qry[$key][$rowcnt-1] : '' ;  
                while($rowcnt <= $param['selecnt_nos'])
                {
                    $sub_inp_amount  = 0;
                    $sub_out_amount  = 0;
                    $sub_srv_amount  = 0;
                    $sub_tot_amount  = 0;
                    $sub_net_amount  = 0;
                    $ptaxind = 'Y';
                    //print_r($dtl_row['service_tax_ind']);die;
                    $pserv_tax_ind  = $dtl_row['service_tax_ind'];
                    $pserv_tax_per  = $dtl_row['service_tax_percent'];
                    if ($param['service_tax_amount'] >0)
                    $pserv_tax_desc = $dtl_row['service_tax_desc'];
                    else
                    $pserv_tax_desc = '';

                    while($pserv_tax_ind == $dtl_row['service_tax_ind'] && $rowcnt <= $param['selecnt_nos'])
                    {
                    $activity_date     = $dtl_row['activity_date'];
                    $activity_desc     = $dtl_row['activity_desc'];
                    $io_ind            = $dtl_row['io_ind'];
                    $source_code_dtl2  = $dtl_row['source_code'];
                    $billed_amount     = $dtl_row['billed_amount'];
                    $serv_tax_amount   = $dtl_row['service_tax_amount'];
                    $pserv_bill_date   = $bill_row[$key]['bill_date'];


                    if($l_no>$param['tot_no_of_lines'])
                    {
                    $page_no = $page_no + 1;
                    $l_no = 15;
            ?>
            </table>
            </td>
            </tr>
        </table>
      </div>
      <br class="pageEnd">
      <table width="750" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td class="cellheight_1" width="075">&nbsp;</td>
            <td class="cellheight_1" width="100">&nbsp;</td>
            <td class="cellheight_1" width="400">&nbsp;</td>
            <td class="cellheight_1" width="075">&nbsp;</td>
            <td class="cellheight_1" width="050">&nbsp;</td>
            <td class="cellheight_1" width="050">&nbsp;</td>
          </tr>
          <tr>
            <td class="cellheight_1" width="" colspan="6">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tbody>
                    <tr style="line-height:2px">
                    <td width="120">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="120">&nbsp;</td>
                  </tr>
                  <tr style="line-height:80px">
                    <td valign="top">
                    
                    <td valign="top">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody><tr><td align="center"><img src="<?= base_url('public/assets/img/logo.jpg') ?>" width="155" height="65" border="0"></td></tr>
                    </tbody></table>
                    </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td class="GroupDetail_band_portrait" style="font-size:12px;" align="center"><b>5 KIRAN SHANKAR ROY ROAD, KOLKATA - 700001</b></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td class="GroupDetail_band_portrait" style="font-size:12px;" align="center"><b>TEL : 22438377, 22101617, 22101625     FAX : 22436176</b></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td class="GroupDetail_band_portrait" style="font-size:12px;" align="center"><b>E-Mail : sinhaco@sinhaco.com/sinhacoadvocates@gmail.com</b></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr><td colspan="3"><hr size="3" noshade="noshade"></td></tr>
                </tbody>
              </table>
            </td>
          </tr>  
          <tr>
            <td class="GroupDetail_band_portrait">&nbsp;<?php if($param['prop_ind'] != 'on') { echo 'Bill No' ; } else { echo 'Ref No' ; }?></td>
            <td class="ReportColumn_portrait w-20">&nbsp;<?php echo $bill_row[$key]['fin_year'].'/'.$bill_row[$key]['bill_no'];?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td> 
          </tr>
          <tr>
            <td class="GroupDetail_band_portrait">&nbsp;<?php if($param['prop_ind'] != 'on') { echo 'Bill Date' ; } else { echo 'Ref Date' ; }?></td>
            <td class="ReportColumn_portrait w-20">&nbsp;<?php if(empty($param['final_bill_date'])) echo $bill_row[$key]['bill_date']; else echo $param['final_bill_date'];?></td>
            <td class="ReportTitle_portrait" align="center"><font size="+1"><b><?php if($param['dupl_ind']=='on') {echo 'DUPLICATE ';} if($param['revd_ind']=='on') {echo 'REVISED ';} if($param['prop_ind']=='on') {echo 'PROPOSE / QUOTATION ';} ?>BILL</b></font></td>
            <td>&nbsp;</td>
            <td class="GroupDetail_band_portrait" align="right" colspan="2">Page :&nbsp;<?php echo $page_no;?>&nbsp;</td>
          </tr>
              <tr>
                <td colspan="6"><hr size="1" color="#666666" noshade="noshade"></td>
              </tr>
            </table>
            <table width="750" cellpadding="0" cellspacing="0" border="1" bordercolor="#666666" bordercolordark="#FFFFFF" bordercolorlight="#FFFFFF">
            <tr>
            <td>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr> 
                <td class="GroupDetail_band_portrait">&nbsp;<b class="ps-3">Date</b></td>
                <td class="GroupDetail_band_portrait">&nbsp;<b>Details</b></td>
                <td class="GroupDetail_band_portrait" align="right"><b class="pe-2 tblTplst">Professional Fees <b>(<img src="<?= base_url('public/assets/img/rupee.jpg') ?>" height="8" border="0">)</b><br>
                </b>&nbsp;</td>
                <td class="GroupDetail_band_portrait" align="right"><b class="pe-2 tblTplst">Reimbursement<b>(<img src="<?= base_url('public/assets/img/rupee.jpg') ?>" height="8" border="0">)</b><br>
                </b>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><hr color="#666666" noshade="noshade"></td>
              </tr>
          <?php
                  }
                  if(!empty($activity_date))
                  {
          ?>
            <tr>
              <td class="GroupDetail_band_portrait" colspan="4">
                <hr color="#666666" noshade="noshade" class="m-0"></td>
            </tr>
          <?php
                  $l_no = $l_no + 1 ;
                  }
                  $activity_desc = text_justify(trim(nl2br(stripslashes($activity_desc))),$param['tot_char']);
                  $activity_desc = str_replace("<br />",'',$activity_desc);
          ?>
          <?php if($ptaxind == 'Y') { ?>
          <tr>
            <td class="GroupDetail_band_portrait" colspan="4"><font size="2"><b><u><i><?php echo $pserv_tax_desc ;?></i></u></b></font></td>
          </tr>
          <tr>
            <td class="GroupDetail_band_portrait" colspan="4"><hr color="#666666"  noshade="noshade"></td>
          </tr>
          <?php $l_no += 2; $ptaxind = 'N' ; } ?>
          <tr>
            <td class="GroupDetail_band_portrait w-25" style="vertical-align:middle; font:Courier; font-family:Courier;"><b class="d-inline-block w125 ps-2"><?php echo $activity_date ;?></b></td>
            <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;"><b style="width: auto;padding:0 8px; display: inline-block;"><?php echo $activity_desc;?></b></td>
        <?php if ($billed_amount > 0) { ?>
            <td class="GroupDetail_band_portrait w-20" style="vertical-align:middle; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b style="display: inline-block;width: 110px;"><?php if(($io_ind == 'I' || $io_ind == 'O') && $source_code_dtl2 == 'C' && $billed_amount > 0) echo number_format($billed_amount,2,'.','');?></b></td>
            <?php } ?>
        <?php  if ($billed_amount == '0.00') { ?>
        <td class="ReportColumn_portrait w-20" style="  font:Courier; font-family:Courier; font-weight:bold; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="center"  ><b style="display:inline-block; width:110px;"><?php if(($io_ind == 'I' || $io_ind == 'O') && $param['no_fee_bill_ind'] == 'Y' && $source_code_dtl2 == 'C' && $billed_amount == '0.00') echo '<strong>No Fee Charged</strong>';?></b></td>
        <?php } ?>
            <td class="GroupDetail_band_portrait w-20" style="vertical-align:middle; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php if($io_ind == 'O' && $source_code_dtl2 != 'C' && $billed_amount > 0) echo number_format($billed_amount,2,'.','');?></b></td>
          </tr>       


          <?php
                  $l_no = $l_no + 1 ;
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
          //--------
      
      
      
      
          if ($pserv_tax_per == '10.300')
              { $s_tax = $sub_tot_amount*10/100;
                  $cess_tax = $s_tax*2/100;
                  $hecess_tax = $s_tax*1/100;
                  }
      
          if ($pserv_tax_per == '12.360')
              { $s_tax = $sub_tot_amount*12/100;
                  $cess_tax = $s_tax*2/100;
                  $hecess_tax = $s_tax*1/100;

                  }
          if($pserv_tax_per == '10.300') { $staxper = 'Service Tax 10%';}  if($pserv_tax_per == '12.360') { $staxper = 'Service Tax 12%';}

                  //------   
                  $dtl_row = isset($sele_qry[$key][$rowcnt]) ? $sele_qry[$key][$rowcnt] : $dtl_row; 
                  $rowcnt += 1;   
                  }
                  $sub_net_amount = $sub_tot_amount + $sub_srv_amount ;  



              // $serv_tot_amount = $s_tax + $cess_tax + $hecess_tax ;    
          ?>
          <tr>
            <td class="GroupDetail_band_portrait" colspan="4"><hr color="#666666" noshade="noshade"></td>
          </tr>
          <!--
          <tr>
            <td class="GroupDetail_band_portrait" >&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;">&nbsp;&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php if($sub_inp_amount > 0) echo number_format($sub_inp_amount,2,'.','');?></b>&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php if($sub_out_amount > 0) echo number_format($sub_out_amount,2,'.','');?></b>&nbsp;</td>
          </tr>
          --->


          <tr>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b>Total</b>&nbsp;&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php if($sub_tot_amount > 0) echo number_format($sub_tot_amount,2,'.','');?></b>&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right">&nbsp;</td>

          </tr>

          <?php
                  if($sub_srv_amount > 0)
          {

          ?>
          <tr>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php echo $staxper?></b>&nbsp;&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php  echo number_format($s_tax,2,'.','');?></b>&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right">&nbsp;</td>
          </tr>
          <tr> 
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b>Education Cess 2% on ST</b>&nbsp;&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php  echo number_format($cess_tax,2,'.','');?></b>&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right">&nbsp;</td>
          </tr>
          <tr>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b>Secondary and Higher Secondary Education Cess 1% on ST</b>&nbsp;&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php  echo number_format($hecess_tax,2,'.','');?></b>&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right">&nbsp;</td>
          </tr>
          <tr>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b>Sub Total(Round Off)</b>&nbsp;&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php if($sub_net_amount > 0) echo number_format(round($sub_net_amount,0),2,'.','');?></b>&nbsp;</td>
            <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right">&nbsp;</td>
          </tr>
          <?php
          }
                  $l_no = $l_no + 5 ;
                  $tot_inp_amount  += $sub_inp_amount;
                  $tot_out_amount  += $sub_out_amount;
                  $tot_tot_amount  += $sub_tot_amount;
                  $tot_srv_amount  += $sub_srv_amount;
                  $tot_net_amount  += $sub_net_amount;
                  
              }
          ?>
            <tr>
              <td class="GroupDetail_band_portrait" colspan="4"><hr color="#666666" noshade="noshade"></td>
            </tr>
          </table>
        </td>
        </tr>
      </table>
      <table width="776" cellpadding="0" cellspacing="0" border="1" bordercolor="#666666" bordercolordark="#FFFFFF" bordercolorlight="#FFFFFF">
        <tr>
            <td width="100%">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                <td class="GroupDetail_band_portrait ps-2" width="425" valign="top">
                    <table width="100%" height="49" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="GroupDetail_band_portrait" height="22" style="vertical-align:text-top; font:Courier; font-family:Courier;"><b><?php echo '(Rupees '.int_to_words($tot_net_amount).' only)';?></b></td>
                    </tr>
                    <tr>
                        <td class="GroupDetail_band_portrait" height="22" style="vertical-align:text-top; font:Courier; font-family:Courier; font-size:14px;"><b><?php if($param['direct_counsel_ind'] == 'Y') echo $param['direct_memo'];?></b></td>
                    </tr>
                        <tr>
                        <td class="GroupDetail_band_portrait" height="18" style="vertical-align:text-bottom; font:Arial; font-family:Arial;"><span class="GroupDetail_band_portrait" style="vertical-align:text-bottom; font:Arial; font-family:Arial;"><font size="2"><b><?php echo '';?></b></font></span></td>
                    </tr>
                        <tr>
                        <td class="GroupDetail_band_portrait" height="18" style="vertical-align:text-bottom; font:Arial; font-family:Arial;"><span class="GroupDetail_band_portrait" style="vertical-align:text-bottom; font:Arial; font-family:Arial;"><font size="2"><b><?php echo $param['branch_pan_no'];?></b></font></span></td>
                    </tr>
                    </table>

        <?php
        
        if ($bill_row[$key]['service_tax_amount'] > 0 && $bill_row[$key]['b_d'] >= '2012-07-01') { $service_dec = ''; }
        ?>
              
                  <p><span class="GroupDetail_band_portrait" style="vertical-align:text-bottom; font:Arial; font-family:Arial;"><font size="2"><b><?php echo $param['service_nature'];?></b></font></span></p>
                  <p><span class="GroupDetail_band_portrait" style="vertical-align:text-bottom;  font-size: text-align:justify; font:Arial; font-family:Arial; font-size:15px;"><b><?php echo $param['service_dec'];?></b></font></span></p> </td>
                  <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;">
                  <table width="100%" cellpadding="0" cellspacing="0">

                  
                  
                  <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Total</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right">&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php if($tot_tot_amount>0) echo number_format($tot_tot_amount,2,'.','');?></b>&nbsp;</td>
                  </tr>
                  <?php
                      if($tot_srv_amount>0)
                      {
                  ?>
                  <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Total Service Tax</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right">&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><span class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;"><b><?php  echo number_format(round($tot_srv_amount,0),2,'.','');?></b></span>&nbsp;</td>
                  </tr>
                  <?php
                      }
                  ?>
                  <tr>
                      <td colspan="3"><hr size="1" noshade="noshade"></td>
                  </tr>
                  <tr>
                      <td colspan="3"><hr size="1" noshade="noshade"></td>
                  </tr>
                  <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Grand Total</b>&nbsp;</td>
                      <td width="100" height="15" align="right" class="style1 GroupDetail_band_portrait"><img src="<?= base_url('public/assets/img/rupee.jpg') ?>" height="8" border="0"></td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php if($tot_net_amount>0) echo number_format(round($tot_net_amount,0),2,'.','');?></b>&nbsp;</td>
                  </tr>



                  <tr>
                      <td colspan="3" height="15"><hr size="4" color="#666666" noshade="noshade" style="opacity:unset;"></td>
                  </tr>
        <?php
                $tot_taxable     = $bill_row[$key]['bill_amount_inpocket_stax'] + $bill_row[$key]['bill_amount_outpocket_stax'] + $bill_row[$key]['bill_amount_counsel_stax'] ;
                $tot_nontaxable  = $bill_row[$key]['bill_amount_inpocket_ntax'] + $bill_row[$key]['bill_amount_counsel_ntax'];
                $tot_reim        = $bill_row[$key]['bill_amount_outpocket_ntax'] ;
        ?>
        <?php
                if($tot_taxable>0)
        {
        ?>
                  <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Taxable Service</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php  echo number_format($tot_taxable,2,'.','');?></b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right">&nbsp;</td>
                  </tr>
                  <?php
                  }
                      if($tot_srv_amount>0)
                      {
                  ?>
                  <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Service Tax</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><span class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;"><b><?php  echo number_format(round($tot_srv_amount,0),2,'.','');?></b></span>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right">&nbsp;</td>
                  </tr>
                  <?php
                      }
                  ?>
          <?php
          if($tot_nontaxable>0)
          {
          ?>
                  <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Non Taxable</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php echo number_format(round($tot_nontaxable,0),2,'.','');?></b>&nbsp;</td>
                      <td width="100" height="15" align="right" class="style1 GroupDetail_band_portrait">&nbsp;</td>
                  </tr>
          <?php
          }
          ?>
          <?php
          if($tot_reim>0)
          {
          ?>
                  <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Reimbursement</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php echo number_format(round($tot_reim,0),2,'.','');?></b>&nbsp;</td>
                      <td width="100" height="15" align="right" class="style1 GroupDetail_band_portrait">&nbsp;</td>
                  </tr>
          <?php
          }
          ?>
          <?php
              if($tot_net_amount>0)
          {
          ?>
                  <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Total</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php echo number_format(round($tot_net_amount,0),2,'.','');?></b>&nbsp;</td>
                      <td width="100" height="15" align="right" class="style1 GroupDetail_band_portrait">&nbsp;</td>
                  </tr>
          <?php
              }
          ?>


                  <tr>
                      <td colspan="3" height="15"><hr size="3" color="#666666" noshade="noshade"></td>
                  </tr>
                  <tr>
                      <td colspan="3" class="GroupDetail_band_portrait" height="15" align="center"><b>E.&.O.E.</b></td>
                  </tr>
                  <tr>
                      <td colspan="3" class="GroupDetail_band_portrait" height="15" align="center"><b>for&nbsp; Sinha and Company </b></td>
                  </tr>
                  <tr>
                      <td colspan="3" class="GroupDetail_band_portrait" height="20" align="center">&nbsp;</td>
                  </tr>
                      <tr>
                      <td colspan="3" class="GroupDetail_band_portrait" height="20" align="center"><font size="+1"><b><?php if($param['recd_ind']=='on') {echo 'AMOUNT RECEIVED ';}?></b></font></td>
                  </tr>
                  </table>                </td>
              </tr>
          </table>
          </td>
        </tr>
      </table>
            </div>
    <?php } ?>
  </form>
<?php } ?> 
</main>
</div>
<script>
    
    function selectLookup(select) {

        let selectValue = select.value;
        let lookupDiv = document.getElementById("lookupBtn");
        let rangeFrom = document.getElementById("rangeFrom");
        let rangeTo = document.getElementById("rangeTo");
        let inputCode = document.getElementById("inputCode");
        let inputName = document.getElementById("inputName");

        if(selectValue == 'Range')
        {
            rangeFrom.disabled = false; rangeFrom.required = true; rangeTo.disabled = false; inputCode.readOnly = true;
            rangeFrom.value = ''; rangeTo.value = ''; inputCode.value = ''; inputName.value = '';
            rangeFrom.focus() ; rangeFrom.select() ;

            lookupDiv.innerHTML = `
                <div class="float-start px-2 position-relative mb-3 w-35">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                    <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" readonly/>
                </div>
                <input type="text" class="form-control ms-3 float-start mt-31 w-60" name="input_name" id="inputName" readonly/>
            `;
        }
        else if(selectValue == 'Client') { 

            rangeFrom.disabled = true; rangeTo.disabled = true; inputCode.readonly = false;
            rangeFrom.value = ''; rangeTo.value = ''; inputCode.value = ''; inputName.value = ''; inputCode.focus(); 
             
            lookupDiv.innerHTML = `
                <div class="float-start px-2 position-relative mb-3 w-35">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                    <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'client_code', ['inputName'], ['client_name'], 'client_code')" size="05" maxlength="06" />
                    <i class="fa-solid fa-binoculars icn-vw" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'inputCode', ['inputName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                </div>
                <input type="text" class="form-control ms-3 float-start mt-31 w-60" name="input_name" id="inputName" readonly/>
            `;
        }
        else if (selectValue == 'Matter') {
            
            rangeFrom.disabled = true; rangeTo.disabled = true; inputCode.required = true; inputCode.readonly = false;
            rangeFrom.value = ''; rangeTo.value = ''; inputCode.value = ''; inputName.value = ''; inputCode.focus(); 

            lookupDiv.innerHTML = `
                <div class="float-start px-2 position-relative mb-3 w-35">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                    <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'matter_code', ['inputName'], ['matter_desc'], 'matter_code')" size="05" maxlength="06"/>
                    <i class="fa-solid fa-binoculars icn-vw" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'inputCode', ['inputName'], ['matter_desc'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                </div>
                <input type="text" class="form-control ms-3 float-start mt-31 w-60" name="input_name" id="inputName" readonly/>
            `;
        }
        else { 

            rangeFrom.disabled = true; rangeTo.disabled = true; inputCode.readOnly = true;
            rangeFrom.value = ''; rangeTo.value = ''; inputCode.value = ''; inputName.value = '';

            lookupDiv.innerHTML = `
                <div class="float-start px-2 position-relative mb-3 w-35">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                    <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" readonly/>
                </div>
                <input type="text" class="form-control ms-3 float-start mt-31 w-60" name="input_name" id="inputName" readonly/>
            `;
        }
    }

    function myselect(param) {
        var bill_count = document.getElementById("bill_count").value;
        //alert(bill_count);
            if (param == 'S') { var ind = true ; } else { var ind = false ; }
            //
            for (i=1; i<=bill_count; i++)
            {
            document.getElementById("print_ind"+i).checked = ind;
            // ("#print_ind"+i).prop('checked', true);
            }
            checkBoxValidation();
    }

    function myRangeTo() {
        document.getElementById("rangeTo").value = document.getElementById("rangeFrom").value ; 
    }

    function bill_data_check(ind) {
      var row_count = document.getElementById("rowCount").value;
      ok_ind = 'N';
      for(i=1;i<=row_count;i++)
      {
        if(document.getElementById("print_ind"+i).checked == true)
          ok_ind = 'Y';
      }
      if(ok_ind == 'N')
      {
        //showErrorMessage('',67);
        document.getElementById("print_ind1").checked = true;
        document.getElementById("print_ind1").focus();
        return false;
      }
      else
      {
        if(ind == 'W')
        {
      //      document.f1.action='rep_final_bill.php';
      //      document.f1.submit();
            var r_count     = row_count;
            var bill_str    = '';
            var f_bill_date = document.getElementById("finalBillDate").value;
            var dupl_ind    = '';
            var revd_ind    = '';
          var recd_ind    = '';
          var prop_ind    = '';
          var copy_ind    = '';
            var row_count   = 0 ;
            
            
            if(document.getElementById("duplInd").checked == true)
            {
              //var test = document.getElementById("duplInd").value;
              //alert(test);
              dupl_ind = 'Checked';
            }
            if(document.getElementById("revdInd").checked == true)
            {
              revd_ind = 'Checked';
            }
            if(document.getElementById("recdInd").checked == true)
            {
              recd_ind = 'Checked'; 
            }
            
          if(document.getElementById("propInd").checked == true)
            {
              prop_ind = 'Checked';
            }
            
            
          if(document.getElementById("copyInd").checked == true)
            {
              copy_ind = 'Checked';
            }

            for (i=1;i<=r_count;i++)
            {
              if(document.getElementById("print_ind"+i).checked == true)
              {
                bill_str = bill_str + 'x_x' + document.getElementById("bill_serial_no"+i).value;
                document.getElementById("bill_str").value = bill_str;
                row_count = row_count + 1 ;
              }
            }
      
          // if(document.getElementById("billFormat").value == 'S') 
          // {
          //     window.open('rep_final_bill_tax.php?params['final_bill_date']='+f_bill_date+'&dupl_ind='+dupl_ind+'&revd_ind='+revd_ind+'&recd_ind='+recd_ind+'&prop_ind='+prop_ind+'&copyx_ind='+copy_ind+'&row_count='+row_count+'&bill_str='+bill_str,'Final','top=0,left=0,width=800,height=600,menubar=yes,scrollbars=yes,resizable=yes,statusbar=yes');
          // }
          //   else
          // {
          //     window.open('rep_final_bill.php?params['final_bill_date']='+f_bill_date+'&dupl_ind='+dupl_ind+'&revd_ind='+revd_ind+'&recd_ind='+recd_ind+'&prop_ind='+prop_ind+'&copyx_ind='+copy_ind+'&row_count='+row_count+'&bill_str='+bill_str,'Final','top=0,left=0,width=800,height=600,menubar=yes,scrollbars=yes,resizable=yes,statusbar=yes');
          // }
          }
          // else
          // {
          //   document.f1.action='rep_final_bill_dos.php';
          //   document.f1.submit();
          // }
        }
    }
</script>
<?= $this->endSection() ?>

