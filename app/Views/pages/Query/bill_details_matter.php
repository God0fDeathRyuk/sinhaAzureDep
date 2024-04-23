<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<main id="main" class="main">
<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php endif;?>
<div class="pagetitle">
      <h1>Bill Details (By Matter)</h1>
    </div><!-- End Page Title -->
    <section class="section dashboard">
    <div class="frms-sec d-inline-block w-100 bg-white p-3">	
	<div class="float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
					<select class="form-select" name="branch_code" id="branchCode"  disabled >
						<?php foreach ($branch as $key => $value) {?>
						<option value="<?= $value['branch_code'] ?>" <?php if($branch_code==$value['branch_code']){ echo 'selected';} ?>><?= $value['branch_name'] ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-5 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
					<input type="text" class="form-control w-45 float-start datepicker" name="start_date"  id="start_date" value="<?= $start_date?>"  onblur="make_date(this)" readonly>
					<span class="w-2 float-start mx-1">--</span>
					<input type="text" class="form-control w-45 float-start datepicker" name="end_date" id="end_date" required disabled value="<?= $end_date?>">
				</div>				
				<div class="float-start col-md-3 px-2 mb-1 position-relative">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Matter Code <strong class="text-danger">*</strong></label>
					<input type="text" name="matter_code" id="matterCode" class="form-control" value="<?= $matter_code ?>" required readonly/>
					<i class="fa fa-binoculars icn-vw" id="matterBinocular" onclick="showData('matter_code', '<?= '4219' ?>', 'matterCode', [ 'matterCode','matterDesc','clientName','clientCode'], ['matter_code','matter_desc','client_name','client_code'],'matter_code')"  data-toggle="modal" data-target="#lookup" ></i>
				</div>
				<div class="float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Matter Description</label>
					<input type="text" class="form-control" name="matter_desc" id="matterDesc" value="<?= $matter_descst ?>" readonly/>
				</div>
				<div class="float-start col-md-6 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Client Name</label>
					<input type="text" class="form-control" name="client_name" id="clientName" value="<?= $client_namest ?>" readonly/>
				</div>	
                <div class="float-start col-md-2 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">&nbsp;</label>
                    <input type="text" class="form-control" name="client_code" id="clientCode" value="<?= $client_code ?>" readonly/>
				</div>	
				<div class="float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Bill Status <strong class="text-danger">*</strong</label>
					<select tabindex="6" class="form-select" name="bill_status" id="bill_status" disabled>
                        <option value='%' <?= ($bill_status=='%')?'selected':'' ?>>All</option>
                        <option value='B' <?= ($bill_status=='B')?'selected':'' ?>>Approved</option>
                        <option value='A' <?= ($bill_status=='A')?'selected':'' ?>>Un-Approved</option>
                        <option value='X' <?= ($bill_status=='X')?'selected':'' ?>>Cancelled</option>
				    </select> 
				</div>				
			</div>
			<div class="d-block w-100 px-2 mt-2 tblscrlvtrcllrg ScrltblMn">
			<table class="table border-0">
						<tr class="fs-14">						
						 <th>&nbsp;</td>
                         <th>&nbsp;Serial</td>
                         <th>&nbsp;Bill No</td>
                         <th>&nbsp;Final Bill Dt</td>
                         <th>&nbsp;Bill Period</td>
                         <th>Inpocket&nbsp;</td>
                         <th>Outpocket&nbsp;</td>
                         <th>Counsel&nbsp;</td>
                         <th>Service Tax&nbsp;</td>
                         <th>Total&nbsp;</td>
                         <th>&nbsp;Status</td>
                         <th>Realised&nbsp;</td>
                         <th>Deficit&nbsp;</td>
                         <th>Balance&nbsp;</td>
                   		 <th>Prepared By&nbsp;</td>
						 <th>Updated By&nbsp;</td>
						 <th>Approved By&nbsp;</td>
						</tr>
						<?php $i=0; 
						$total_amount_inpocket    = 0.00 ; 					 
						$total_amount_outpocket   = 0.00 ; 							 
						$total_amount_counsel     = 0.00 ; 				
						 $total_amount_total       = 0.00 ; 		
						$total_amount_realise     = 0.00 ; 							 
						$total_amount_deficit     = 0.00 ;
						$total_amount_service_tax = 0.00 ;		 					 
						$total_amount_balance     = 0.00 ; 	
						foreach ($data as $i => $value) {
							$i++ ; 
                        $serial_no              = $value['serial_no'] ; 					 
                        $ref_bill_serial_no     = $value['ref_bill_serial_no'] ; 					 
                        $bill_number            = $value['bill_number'] ; 					 
                        $bill_date              = date_conv($value['bill_date']) ; 
                        $draft_bill_date        = date_conv($value['draft_bill_date']) ; 
                        $period_start_date      = date_conv($value['start_date']) ; 					 
                        $period_end_date        = date_conv($value['end_date']) ; 					 
                        $amount_inpocket        = $value['bill_amount_inpocket'] ; 					 
                        $amount_outpocket       = $value['bill_amount_outpocket'] ; 					 
                        $amount_counsel         = $value['bill_amount_counsel'] ;
                        $amount_service_tax     = $value['service_tax_amount'] ; 		
						$status_code            = $value['status_code'] ;
                        $prepared_by            = $value['prepared_by'] ;
                        $updated_by             = $value['updated_by'] ;
                        $approved_by            = $value['approved_by'] ;
 					    $amount_total           = ($amount_inpocket + $amount_outpocket + $amount_counsel + $amount_service_tax) ;
                        $amount_realise         = $value['realise_amount'] ; 					 
                        $amount_deficit         = $value['deficit_amount'] ; 					 
                        $amount_balance         = $value['balance_amount'] ; 		
					    //
                        $total_amount_inpocket       += $value['bill_amount_inpocket'] ; 					 
                        $total_amount_outpocket      += $value['bill_amount_outpocket'] ; 					 
                        $total_amount_counsel        += $value['bill_amount_counsel'] ;
                        $total_amount_service_tax    += $value['service_tax_amount'] ; 		
 					    $total_amount_total          += ($value['bill_amount_inpocket'] + $value['bill_amount_outpocket'] + $value['bill_amount_counsel'] + $value['service_tax_amount']) ;
                        $total_amount_realise        += $value['realise_amount'] ; 					 
                        $total_amount_deficit        += $value['deficit_amount'] ; 					 
                        $total_amount_balance        += $value['balance_amount'] ; 		
                        //
                        if($period_start_date != '' && $period_start_date != '0000-00-00') { $bill_period = $period_start_date.' - '.$period_end_date ; } else { $bill_period = ' UPTO '.$period_end_date ; }

                        if($status_code == 'A') { $status_desc = "Un-App" ; } else if($status_code == 'B') { $status_desc = "Aprvd" ; } else if($status_code == 'X') { $status_desc = "Cancel" ; }  else { $status_desc = " " ; } 
						?>
						<tr class="fs-14">
						   <td><input class="form-control" type="radio" name="seleind" id="seleind" value="Y" onClick="myseleind('<?php echo $serial_no?>','<?php echo $ref_bill_serial_no?>','<?php echo $i; ?>')"></td> 
                           <td><input class="form-control" type="text"  size="04" maxlength="08"  name="serial_no<?php echo $i?>"          value="<?php echo $serial_no ?>"        title="<?php echo 'Draft Bill Date : ' . $draft_bill_date;?>" readonly></td> 
                           <td><input class="form-control" type="text"  size="12" maxlength="14"  name="bill_number<?php echo $i?>"        value="<?php echo $bill_number ?>"      readonly></td> 
                           <td><input class="form-control" type="text"  size="08" maxlength="10"  name="bill_date<?php echo $i?>"          value="<?php echo $bill_date ?>"        readonly></td> 
                           <td><input class="form-control" type="text"  size="20" maxlength="16"  name="bill_period<?php echo $i?>"        value="<?php echo $bill_period ?>"      readonly></td> 
                           <td><input class="form-control" type="text"  size="08" maxlength="11"  name="amount_inpocket<?php echo $i?>"    value="<?php echo $amount_inpocket ?>"  readonly></td> 
                           <td><input class="form-control" type="text"  size="08" maxlength="11"  name="amount_outpocket<?php echo $i?>"   value="<?php echo $amount_outpocket ?>" readonly></td> 
                           <td><input class="form-control" type="text"  size="08" maxlength="11"  name="amount_counsel<?php echo $i?>"     value="<?php echo $amount_counsel ?>"   readonly></td> 
                           <td><input class="form-control" type="text"  size="08" maxlength="11"  name="amount_service_tax<?php echo $i?>" value="<?php echo $amount_service_tax ?>"   readonly></td> 
                           <td><input class="form-control" type="text"  size="08" maxlength="11"  name="amount_total<?php echo $i?>"       value="<?php echo number_format($amount_total,2,'.','') ?>" readonly></td> 
                           <td><input class="form-control" type="text"  size="04" maxlength="06"  name="status_desc<?php echo $i?>"        value="<?php echo $status_desc ?>"      readonly></td> 
                           <td><input class="form-control" type="text"  size="08" maxlength="11"  name="amount_realise<?php echo $i?>"     value="<?php echo $amount_realise ?>"   readonly></td> 
                           <td><input class="form-control" type="text"  size="08" maxlength="11"  name="amount_deficit<?php echo $i?>"     value="<?php echo $amount_deficit ?>"   readonly></td> 
                           <td><input class="form-control" type="text"  size="08" maxlength="11"  name="amount_balance<?php echo $i?>"     value="<?php if($status_code != 'X') {echo $amount_balance ; }?>"   readonly></td> 

                           <td><input class="form-control" type="text"  size="08" maxlength="11"  name="prepared_by<?php echo $i?>"     value="<?php echo $prepared_by ?>"   readonly></td> 

                           <td><input class="form-control" type="text"  size="08" maxlength="11"  name="updated_by<?php echo $i?>"     value="<?php echo $updated_by ?>"   readonly></td> 

                           <td><input class="form-control" type="text"  size="08" maxlength="11"  name="approved_by<?php echo $i?>"     value="<?php echo $approved_by ?>"   readonly></td> 

						</tr>
						
						<?php } ?>
					</table>
					<?php $i=0; 
						$total_amount_inpocket    = 0.00 ; 					 
						$total_amount_outpocket   = 0.00 ; 							 
						$total_amount_counsel     = 0.00 ; 				
						 $total_amount_total       = 0.00 ; 		
						$total_amount_realise     = 0.00 ; 							 
						$total_amount_deficit     = 0.00 ;
						$total_amount_service_tax = 0.00 ;		 					 
						$total_amount_balance     = 0.00 ; 
						foreach ($data as $i => $value) { $i++;
							$serial_no              = $value['serial_no'] ; 					 
							$ref_bill_serial_no     = $value['ref_bill_serial_no'] ; 					 
							$bill_number            = $value['bill_number'] ; 					 
							$bill_date              = date_conv($value['bill_date']) ; 
							$draft_bill_date        = date_conv($value['draft_bill_date']) ; 
							$period_start_date      = date_conv($value['start_date']) ; 					 
							$period_end_date        = date_conv($value['end_date']) ; 					 
							$amount_inpocket        = $value['bill_amount_inpocket'] ; 					 
							$amount_outpocket       = $value['bill_amount_outpocket'] ; 					 
							$amount_counsel         = $value['bill_amount_counsel'] ;
							$amount_service_tax     = $value['service_tax_amount'] ; 		
							$status_code            = $value['status_code'] ;
							$prepared_by            = $value['prepared_by'] ;
							$updated_by             = $value['updated_by'] ;
							$approved_by            = $value['approved_by'] ;
							 $amount_total           = ($amount_inpocket + $amount_outpocket + $amount_counsel + $amount_service_tax) ;
							$amount_realise         = $value['realise_amount'] ; 					 
							$amount_deficit         = $value['deficit_amount'] ; 					 
							$amount_balance         = $value['balance_amount'] ; 		
							//
							$total_amount_inpocket       += $value['bill_amount_inpocket'] ; 					 
							$total_amount_outpocket      += $value['bill_amount_outpocket'] ; 					 
							$total_amount_counsel        += $value['bill_amount_counsel'] ;
							$total_amount_service_tax    += $value['service_tax_amount'] ; 		
							 $total_amount_total          += ($value['bill_amount_inpocket'] + $value['bill_amount_outpocket'] + $value['bill_amount_counsel'] + $value['service_tax_amount']) ;
							$total_amount_realise        += $value['realise_amount'] ; 					 
							$total_amount_deficit        += $value['deficit_amount'] ; 					 
							$total_amount_balance        += $value['balance_amount'] ; 	?>
					<form method="post" action="/sinhaco/query/rep-final-bill-tax" id="finalbilltax<?php echo $i; ?>" target="">

						   <input class="form-control" type="hidden" name="serial_no" id="serial_no" value="<?php echo $serial_no; ?>">
						   <input class="form-control" type="hidden" name="ref_bill_serial_no" id="ref_bill_serial_no" value="<?php echo $ref_bill_serial_no; ?>">
                           <input class="form-control" type="hidden"  size="04" maxlength="08"  name="serial_no<?php echo $i?>"          value="<?php echo $serial_no ?>"        title="<?php echo 'Draft Bill Date : ' . $draft_bill_date;?>" readonly/>
                           <input class="form-control" type="hidden"  size="12" maxlength="14"  name="bill_number<?php echo $i?>"        value="<?php echo $bill_number ?>"      readonly/>
                           <input class="form-control" type="hidden"  size="08" maxlength="10"  name="bill_date<?php echo $i?>"          value="<?php echo $bill_date ?>"        readonly/>
                           <input class="form-control" type="hidden"  size="20" maxlength="16"  name="bill_period<?php echo $i?>"        value="<?php echo $bill_period ?>"      readonly/>
                           <input class="form-control" type="hidden"  size="08" maxlength="11"  name="amount_inpocket<?php echo $i?>"    value="<?php echo $amount_inpocket ?>"  readonly/>
                           <input class="form-control" type="hidden"  size="08" maxlength="11"  name="amount_outpocket<?php echo $i?>"   value="<?php echo $amount_outpocket ?>" readonly/>
                           <input class="form-control" type="hidden"  size="08" maxlength="11"  name="amount_counsel<?php echo $i?>"     value="<?php echo $amount_counsel ?>"   readonly/>
                           <input class="form-control" type="hidden"  size="08" maxlength="11"  name="amount_service_tax<?php echo $i?>" value="<?php echo $amount_service_tax ?>"   readonly/>
                           <input class="form-control" type="hidden"  size="08" maxlength="11"  name="amount_total<?php echo $i?>"       value="<?php echo number_format($amount_total,2,'.','') ?>" readonly/>
                           <input class="form-control" type="hidden"  size="04" maxlength="06"  name="status_desc<?php echo $i?>"        value="<?php echo $status_desc ?>"      readonly/>
                           <input class="form-control" type="hidden"  size="08" maxlength="11"  name="amount_realise<?php echo $i?>"     value="<?php echo $amount_realise ?>"   readonly/>
                           <input class="form-control" type="hidden"  size="08" maxlength="11"  name="amount_deficit<?php echo $i?>"     value="<?php echo $amount_deficit ?>"   readonly/>
                           <input class="form-control" type="hidden"  size="08" maxlength="11"  name="amount_balance<?php echo $i?>"     value="<?php if($status_code != 'X') {echo $amount_balance ; }?>"   readonly/>

                           <input class="form-control" type="hidden"  size="08" maxlength="11"  name="prepared_by<?php echo $i?>"     value="<?php echo $prepared_by ?>"   readonly/>

                           <input class="form-control" type="hidden"  size="08" maxlength="11"  name="updated_by<?php echo $i?>"     value="<?php echo $updated_by ?>"   readonly/>

                           <input class="form-control" type="hidden"  size="08" maxlength="11"  name="approved_by<?php echo $i?>"     value="<?php echo $approved_by ?>"   readonly/>
						</form>
						<?php } ?>
			</div>
    </section>

  </main><!-- End #main -->
<?= $this->endSection() ?>