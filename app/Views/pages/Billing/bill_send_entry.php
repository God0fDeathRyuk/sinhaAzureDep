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
<?php endif; ?>
<?php if (session()->getFlashdata('success_message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('success_message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php endif; ?>


    <div class="pagetitle col-md-12 float-start border-bottom pb-1">
		<h1>Final Bill Send [Entry]</h1> 
		</div>
<form action="" method="post" id="billSendEntry" name="billSendEntry" onsubmit="setValue(event)">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="col-md-3 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
					<select tabindex="1" class="form-select" name="db_code" id="dbCode" onBlur="mydbfunc()" <?= $permission ?>>
                        <?php foreach($daybook_qry as $daybook_row) { ?>
                            <option value="<?php echo $daybook_row['branch_code']?>" ><?php echo $daybook_row['branch_name'].' [DB '.$daybook_row['branch_code'].']';?></option>
                        <?php } ?>		
                    </select>
	            <input type="hidden" name="branch_code" id="branchCode" value="<?= ($option == 'search') ? $params['branch_code'] : ''  ?>">
				</div>
				<div class="col-md-5 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Year/Month</label>
					<select tabindex="3" class="form-select w-50 float-start" name="fin_year" id="finYear" value="<?= ($option == 'search') ? $params['fin_year'] : ''  ?>" <?= $permission ?>>
                        <option value="">--Select--</option>
                        <?php foreach($finyr_qry as $finyr_row) { ?>
                        <option value="<?php echo $finyr_row['fin_year']?>" <?php if($fin_year == $finyr_row['fin_year']) { echo 'selected' ; }?>><?php echo $finyr_row['fin_year']?></option>
                        <?php } ?>		
                    </select>
                    <select tabindex="4" class="form-select w-48 float-start ms-3" name="month_no" id="monthNo" value="<?= ($option == 'search') ? $params['month_no'] : ''  ?>" <?= $permission ?>>
                        <option value="">--Select--</option>
                        <?php foreach($month_qry as $month_row) { ?>
                        <option value="<?php echo $month_row['month_no']?>" <?php if($month_no == $month_row['month_no']) { echo 'selected' ; }?>><?php echo $month_row['month_descl']?></option>
                        <?php } ?>		
                    </select>
                    <input type="hidden" name="reco_yymm"   value="">
        	        <input type="hidden" name="current_date" value="<?php echo date('Y-m-d');?>">
				</div>
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Type</label>
					<select class="form-select" name="rec_type" id="recType" onBlur="myrectypefunc()" <?= $permission ?>>
                        <option value="U" <?php if($rec_type == 'U') { echo 'selected' ; }?>>Unsend</option>
                        <option value="R" <?php if($rec_type == 'R') { echo 'selected' ; }?>>Send</option>
                        <option value="A" <?php if($rec_type == 'A') { echo 'selected' ; }?>>All</option>
                    </select>
        	    <input type="hidden" name="trans_type" id="transType" value="<?= ($option == 'search') ? $params['trans_type'] : ''  ?>">
				</div>
				<div class="col-md-4 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Date</label>
					<input class="form-control w-100 float-start" type="text" name="last_recon_date" id="last_recon_date" value="<?= ($option == 'search') ? date_conv($params['last_recon_date']) : ''  ?>" readonly>
				</div>
				<?php if($option != 'search') { ?>
				<div class="d-inline-block w-100 mb-3">
					<button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2" onclick="formOption('/billing/send-entry/', 'search', 'billSendEntry')">Search</button>
					<button type="Reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
				</div>
				<?php } ?>
</form>
<?php if (isset($recon_qry)){ ?>
<form action="" method="post" id="" name="">
    <div class="d-inline-block w-100 mb-4 bnd">
					<span>Transaction Details </span>
				</div>
				<div class="d-inline-block w-100 mt-2 ScrltblMn tblscrlvtrcllrg">
					<table class="table table-bordered tblhdClr">
						<tr>
							<th class="w-10">
								<span>Bill No</span>
							</th>
							<th class="w-10">
								<span>Bill Date</span>
							</th>
							<th class="w-25">
								<span>Client</span>
							</th>
							<th class="w-5">
								<span>Matter</span>
							</th>
							<th class="w-25">
								<span>Matter Desc.</span>
							</th>
							<th class="w-10">
								<span>Amount</span>
							</th>
							<th class="w-10">
								<span>Send Date</span>
							</th>
						</tr>
                        <?php foreach($recon_qry as $key => $recon_row) { 
                            if ($recon_row['billsend_on'] == '0000-00-00' || $recon_row['billsend_on'] == '') 
                            {
                               $billsend_on = '' ; $billsend_ind = 'N' ; $cleared_desc = '' ; 
                            } 
                            else
                            {
                               $billsend_on = date_conv($recon_row['billsend_on'],'/') ; $billsend_ind = 'Y' ; $cleared_desc = 'checked' ; 
                            } ?>
						<tr>
							<td>
								<input type="text" class="form-control" name="bill_no<?php echo $key+1?>"    value="<?php echo $recon_row['bill_no'] ;?>"  readonly/>
							</td>
							<td>
								<input type="text" class="form-control" name="bill_date<?php echo $key+1?>"  value="<?php echo date_conv($recon_row['bill_date'],'/') ;?>" readonly/>
							</td>
							<td>
								<input type="text" class="form-control" name="client_name<?php echo $key+1?>" value="<?php echo $recon_row['client_name'] ;?>" readonly/>
							</td>
							<td class="">
								<input type="text" class="form-control" name="matter_code<?php echo $key+1?>" value="<?php echo $recon_row['matter_code'] ;?>"  readonly/>
							</td>
							<td>
								<input type="text" class="form-control" name="matter_desc<?php echo $key+1?>" value="<?php echo $recon_row['matter_desc'] ;?>" readonly/>
							</td>
							<td>
								<input type="text" class="form-control" class="form-control w-75 float-start" name="totamt<?php echo $key+1?>" value="<?php echo $recon_row['totamt'] ;?>" readonly/>
							</td>
							<td>
                                <input type="checkbox" class="tblcbx mt-2 me-2" name="billsend_ind<?php echo $key+1?>" id="billsend_ind<?php echo $key+1?>" value='Y' onClick="myclearindfunc(<?php echo $key+1?>)" <?php echo $cleared_desc ;?>/>
								<input type="text" class="form-control d-block w-75" name="billsend_on<?php echo $key+1?>" id="billsend_on<?php echo $key+1?>" value="<?php echo $billsend_on ;?>" onBlur="mycleardatefunc(<?php echo $key+1?>)"/>
                                <input type="hidden" name="serial_no<?php echo $key+1?>" value="<?php echo $recon_row['serial_no'];?>"/>
							</td>
						</tr>
                        <?php } ?>
					</table>					
				</div>
				<input type="hidden" name="finsub" id="finsub" value="fsub">
				<div class="d-inline-block w-100 mb-3">
					<button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2">Confirm</button>
					<a href="<?= base_url($params['requested_url']) ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
				</div>
				<input type="hidden" name="recon_cnt" value="<?php echo $params['recon_cnt']?>">
				<input type="hidden" name="db_code" value="<?php echo $params['db_code']?>">
</form>
<?php } ?>
</main>
<script>
    // function mydbfunc() {
    //      document.getElementById('branchCode').value = document.getElementById('dbCode').value ;
	// } 

    // function myrectypefunc() {
    //      document.getElementById('transType').value = document.getElementById('recType').value ;
	// } 

	function setValue(e){
		e.preventDefault();
		console.log(document.billSendEntry);
		var currdt   = document.billSendEntry.current_date.value ;
		var currym   = currdt.substr(0,4) + currdt.substr(5,2) ;

		var recomm   = document.billSendEntry.month_no.value ;
        var recoyr   = document.billSendEntry.fin_year.value ;
        if (recomm < '04') { var recoyy = recoyr.substr(5,4) ; } else { var recoyy = recoyr.substr(0,4) ; }
        var recoym   = recoyy + recomm  ;
		document.billSendEntry.reco_yymm.value = recoym ;
		document.billSendEntry.branch_code.value = document.billSendEntry.db_code.value ;
		document.billSendEntry.trans_type.value = document.billSendEntry.rec_type.value ;

		if (recoym > currym){
			Swal.fire({ text: 'Year/Month must be <= Current Year/Month ...' }).then((result) => { setTimeout(() => {document.billSendEntry.fin_year.focus()}, 500) });
			//alert('Year/Month must be <= Current Year/Month .......');
			//document.billSendEntry.fin_year.focus() ;
            return false;
		}
		document.billSendEntry.submit();
	}

    function myclearindfunc(posindex) {
        if (document.getElementById("billsend_ind"+posindex).checked == true)
		{
           document.getElementById("billsend_on"+posindex).value = document.getElementById("last_recon_date").value ;
        }
        else
		{
           document.getElementById("billsend_on"+posindex).value = ''  ;
        }
	} 

	function mycleardatefunc(posindex)
	{
		if (document.getElementById("billsend_on"+posindex).value != '')
		{
			make_date(document.getElementById("billsend_on"+posindex)); 
			//
			var clrdt = document.getElementById("billsend_on"+posindex).value ;  
			var clrdd  = clrdt.substr(0,2) ;
			var clrmm  = clrdt.substr(3,2) ;
			var clryy  = clrdt.substr(6,4) ;
			var clrymd = clryy + '-' + clrmm + '-' + clrdd ;   
			//
			var chqdt  = document.getElementById("instrument_dt"+posindex).value ;
			var chqdd  = chqdt.substr(0,2) ;
			var chqmm  = chqdt.substr(3,2) ;
			var chqyy  = chqdt.substr(6,4) ;
			var chqymd = chqyy + '-' + chqmm + '-' + chqdd ;  
			//
			var lrcdt  = document.getElementById("last_recon_date").value ;
			var lrcdd  = lrcdt.substr(0,2) ;
			var lrcmm  = lrcdt.substr(3,2) ;
			var lrcyy  = lrcdt.substr(6,4) ;
			var lrcymd = lrcyy + '-' + lrcmm + '-' + lrcdd ;  
			//
			if (clrymd > document.getElementById("current_date").value) 
			{
				Swal.fire({ text: 'Clear Date must be <= Current Date ...' }).then((result) => { setTimeout(() => {document.getElementById("billsend_ind"+posindex).focus()}, 500) });
				//alert('Clear Date must be <= Current Date ......');
				document.getElementById("billsend_on"+posindex).value='' ;
				document.getElementById("billsend_ind"+posindex).checked=false ;
				//document.getElementById("billsend_ind"+posindex).focus();
				return false;
			}	  
			else if (clrymd < chqymd) 
			{
				Swal.fire({ text: 'Clear Date must be >= Cheque Date ...' }).then((result) => { setTimeout(() => {document.getElementById("billsend_ind"+posindex).focus()}, 500) });
				//alert('Clear Date must be >= Cheque Date ......');
				document.getElementById("billsend_on"+posindex).value='' ;
				document.getElementById("billsend_ind"+posindex).checked=false ;
				//document.getElementById("billsend_ind"+posindex).focus();
				return false;
			}	  
			else 
			{
				if (clrymd > lrcymd)
				{ //alert(clrymd);
				//alert(lrcymd);
				document.getElementById("last_recon_date").value = document.getElementById("billsend_on"+posindex).value ;
				}
			}	  
		}
	} 
</script>
<?= $this->endSection() ?>