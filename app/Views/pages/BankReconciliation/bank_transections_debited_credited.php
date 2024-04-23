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
    <div class="pagetitle">
      <h1>Bank Transaction - Others [Entry]</h1>
    </div><!-- End Page Title -->

    <form action="" method="post" id="transectionsDebitCredit" name="transectionsDebitCredit" onsubmit="setValue(event)">
        <div class="frms-sec d-inline-block w-100 bg-white p-3">
            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                <label class="d-inline-block w-100 mb-1 lbl-mn"> Bank <strong class="text-danger">*</strong></label>
                <select class="form-select" name="db_code" onChange="mydbfunc();"  required>
                    <option value="">--Select--</option>
                    <?php foreach($daybook_qry as $daybook_row) { ?>
                        <option value="<?php echo $daybook_row['daybook_code']?>" ><?php echo $daybook_row['daybook_desc']?></option>
                    <?php } ?>	
                </select>
                <input type="hidden" size="03" maxlength="02" name="daybook_code" value="">
            </div>
            <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                <label class="d-inline-block w-100 mb-1 lbl-mn">Financial Year <strong class="text-danger">*</strong></label>
                <select class="form-select" name="fin_year" required>
                    <?php foreach($finyr_qry as $finyr_row) { ?>
                        <option value="<?php echo $finyr_row['fin_year']?>" <?php if(session()->financialYear == $finyr_row['fin_year']) { echo 'selected' ; }?>><?php echo $finyr_row['fin_year']?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1">
                <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control" placeholder="Branch" name="branch_name" value="" readonly required />
                <input type="hidden" size="03" maxlength="04" name="branch_code" value="" readonly>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                <label class="d-inline-block w-100 mb-1 lbl-mn">Month <strong class="text-danger">*</strong></label>
                <select class="form-select" name="fin_month" required>
                    <option value="">--Select--</option>
                    <?php foreach($month_qry as $month_row) { ?>
                        <option value="<?php echo $month_row['month_no']?>"><?php echo $month_row['month_descl']?></option>
                    <?php } ?>
                </select>
                <input type="hidden" size="08" maxlength="12" name="curr_yyyymm" value="<?php echo $curr_yyyymm?>">
			    <input type="hidden" size="08" maxlength="12" name="curr_date"   value="<?php echo date('Y-m-d')?>">
                <input type="hidden" size="03" maxlength="01" name="selemode" value="Y"> 
            </div>
            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mt-10">
                <button type="submit" class="btn btn-primary cstmBtn mt-3">Search</button>
                <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
            </div>
        </div>
    </form>	
    <?php if($selemode == 'Y') { ?>
        <form action="" method="post" id="transectionsDebitCreditTable" name="transectionsDebitCreditTable"> 
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <input type="hidden" size="03" maxlength="01" name="selemode" value="Y">              
            <p class="d-inline-block w-100 my-2 bnd">Transiction Details</p>
                <div class="d-inline-block w-100">
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr class="fs-14">
                                <th>&nbsp;</th>
                                <th>Date</th>
                                <th>Narration</th>
                                <th>Debit</th>
                                <th>Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 0; $t = 11;
                                while($i < $params['trans_rowno'])
                                {
                                $i++;
                                $trans_row = $trans_qry; 
                                if ($trans_row['trans_date'] == '0000-00-00') { $trans_date = '' ; } else { $trans_date = date_conv($trans_row['trans_date'],'-') ; } 
                                ?>
                                <tr>
                                    
                                    <td style="width:20px;" id="Ctd<?= $i ?>" onClick="mydelrow(this, <?= $i ?>)">
                                        <input type="hidden" class="cstm-rdo" value="Y" name="del_inpoc<?= $i ?>" id="del_inpoc<?= $i ?>" checked onClick="mydelrow(this, <?= $i ?>)">
                                        <img src="<?= base_url('public/assets/img/SelectRow.png') ?>" class="slctRow" alt="Select"/>
                                    </td>
                                    <td class="w-150"><input type="text" name="trans_date<?php echo $i?>" value="<?php echo $trans_date?>" onBlur="make_date(this)"/></td>
                                    <td class="w-350"><input type="text" name="narration<?php echo $i?>"  value="<?php echo $trans_row['narration']?>"/></td>
                                    <td class="w-150"><input type="text" name="debit_amt<?php echo $i?>"  value="<?php echo $trans_row['debit_amt']?>"  onBlur="mydebitcreditfunc(this,'D',<?php echo $i?>)"/></td>
                                    <td class="w-150"><input type="text" name="credit_amt<?php echo $i?>" value="<?php echo $trans_row['credit_amt']?>" onBlur="mydebitcreditfunc(this,'C',<?php echo $i?>)"/></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="frms-sec-insde d-block float-start col-md-4">
                    <button type="button" class="btn btn-primary cstmBtn">Confirm</button>
                    <button type="button" class="btn btn-primary cstmBtn btn-cncl ms-2">Cancel</button>
                </div>
        </form>
    <?php } ?>
</main>
<script>
    function mydbfunc() {   
        document.transectionsDebitCredit.daybook_code.value = document.transectionsDebitCredit.db_code.value ;
        fetch(`${baseURL}/api/getBranchInfo/${document.transectionsDebitCredit.db_code.value}`)
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            document.transectionsDebitCredit.branch_code.value = data.branch_code;
            document.transectionsDebitCredit.branch_name.value = data.branch_name;
            
        });
    } 
    function setValue(e) {
        e.preventDefault();
        console.log(document.transectionsDebitCredit);
        var cmm = document.transectionsDebitCredit.fin_month.value ;
        var fyy = document.transectionsDebitCredit.fin_year.value ;
        if (cmm <= '03') { var cyy = fyy.substr(5,4) ; } else { var cyy = fyy.substr(0,4) ; }
        var cym = cyy + cmm ;  
        
        if (cym > document.transectionsDebitCredit.curr_yyyymm.value){
            Swal.fire({ text: 'Selected Year/Month must be less than or equal to Current Year/Month ...' }).then((result) => { setTimeout(() => {document.transectionsDebitCredit.fin_month.focus()}, 500) });
            return false;
        }
        document.transectionsDebitCredit.submit();
    }
    function mydebitcreditfunc(param1,param2,param3) {
	    if (param2 == 'D') 
		{
           if (param1.value != '') 
           {
              validateNumber(param1,"Debit Amount : ",2); 
 	   	      eval("document.transectionsDebitCreditTable.credit_amt"+param3+".value = ''");
           }
		}  
	    else if (param2 == 'C') 
		{
           if (param1.value != '') 
           {
              validateNumber(param1,"Credit Amount : ",2); 
 	   	      eval("document.transectionsDebitCreditTable.debit_amt"+param3+".value = ''");
           }
        }
    }
    // function mydelrow(param) {
        // var itemnos  =  echo $params['trans_rowno']?> ;
    //     var mycurrow = param*1 ; 
    //     for (i=mycurrow; i<=itemnos-1; i++)
    //     {
    //     var j = i+1 ;
    //     eval("document.transectionsDebitCreditTable.trans_date"+i+".value = document.transectionsDebitCreditTable.trans_date"+j+".value");
    //     eval("document.transectionsDebitCreditTable.narration"+i+".value  = document.transectionsDebitCreditTable.narration"+j+".value");
    //     eval("document.transectionsDebitCreditTable.debit_amt"+i+".value  = document.transectionsDebitCreditTable.debit_amt"+j+".value");
    //     eval("document.transectionsDebitCreditTable.credit_amt"+i+".value = document.transectionsDebitCreditTable.credit_amt"+j+".value");
    //     }
    //     eval("document.transectionsDebitCreditTable.trans_date"+i+".value=''");
    //     eval("document.transectionsDebitCreditTable.narration"+i+".value=''");
    //     eval("document.transectionsDebitCreditTable.debit_amt"+i+".value=''");
    //     eval("document.transectionsDebitCreditTable.credit_amt"+i+".value=''");
    // }
</script>
<?= $this->endSection() ?>