<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?= view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<main id="main" class="main">
    <div class="pagetitle d-inline-block w-100">
        <h1 class="col-md-11 float-start">Courier Expenses [Edit]</h1>
        <button type="button" class="btn btn-primary cstmBtn btn-cncl col-md-1 float-end">Exit</button>
    </div><!-- End Page Title -->

    <?php if(!isset($print)) { ?> 
    <section class="section dashboard">
        <form action="" method="post" name="f1">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                    <?php if($user_option != 'Add') { ?>	
                        <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Serial#</label>
                            <input type="text" class="form-control" name="serial_no" value="<?= $params['serial_no'] ?>" readonly>
                            <input type="hidden" class="form-control" name="link_jv_serial_no" value="<?= $params['link_jv_serial_no'] ?>" readonly>
                        </div>
                        <?php } ?>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Year</label>					
                            <input type="text" class="form-control w-100 float-start me-1 datepicker" name="fin_year" value="<?= $params['fin_year'] ?>" readonly />
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Branch  <strong class="text-danger">*</strong></label>
                            <select class="form-select cstm-inpt" name="branch_code" onClick="pass_close()" onBlur="pass_close()" <?= $tag_permissions ?>>
                                <?php foreach($data['branches'] as $branch) { ?>
                                <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Date <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-100 float-start me-1 datepicker" name="entry_date" value="<?= $params['entry_date'] ?>" required />
                        </div>
                        
                        <div class="d-inline-block w-100">
                            <span class="bdge d-block w-100 mb-2">From</span>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">DB Code <strong class="text-danger">*</strong></label>					
                                <select class="form-select" name="daybook_code_from" onChange="myDayBookFrom()" <?= $tag_permissions ?>>
                                    <option value="">--Select--</option>
                                    <?php foreach($dbfrom_qry as $dbfrom_row) { ?>
                                    <option value="<?php echo $dbfrom_row['daybook_code']?>" <?php if ($params['daybook_code_from'] == $dbfrom_row['daybook_code']) { echo 'selected' ; }?>><?php echo $dbfrom_row['daybook_desc'] . ' [DB '.$dbfrom_row['daybook_code'].']';?></option>
                                    <?php } ?>
                                </select>
		                        <input type="hidden" name="daybook_type_from" value="<?php echo $params['daybook_type_from']?>">
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Cheq No <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start me-1" name="instrument_no" value="<?= $params['instrument_no'] ?>" <?= $tag_permissions ?> tabindex="4"/>
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Cheq Dt <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start me-1 datepicker" name="instrument_dt"  value="<?php echo $params['instrument_dt']?>" onBlur="make_date(this)" <?php echo $tag_permissions?> tabindex="5" />
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Amount <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start me-1" name="gross_amount"   value="<?php echo $params['gross_amount']?>" onBlur="format_number(this,2)" <?php echo $tag_permissions?>  tabindex="6" />
                            </div>
                        </div>
                        
                        <div class="d-inline-block w-100">
                            <span class="bdge d-block w-100 mb-2">To</span>
                            <div class="col-md-6 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">DB Code <strong class="text-danger">*</strong></label>					
                                <select class="form-select" name="daybook_code_to" onChange="myDayBookTo()" <?php echo $tag_permissions?> tabindex="7">
                                    <option value="">--Select--</option>
                                    <?php foreach($dbto_qry as $dbto_row) { ?>
                                    <option value="<?php echo $dbto_row['daybook_code']?>" <?php if ($params['daybook_code_to'] == $dbto_row['daybook_code']) { echo 'selected' ; }?>><?php echo $dbto_row['daybook_desc'] . ' [DB '.$dbto_row['daybook_code'].']';?></option>
                                    <?php } ?>
                                </select>  
                                <input type="hidden" name="daybook_type_to" value="<?php echo $params['daybook_type_to']?>">
                            </div>
                        </div>
                        <hr>
                        <?php if($user_option == 'Approve') { ?>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Voucher Date <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-100 float-start me-1 datepicker" onKeyPress="return validnumbercheck(event)" name="voucher_date" value="<?php echo date('d-m-Y') ?>"  tabindex="2001" onBlur="make_date(this)" <?php if($user_option=='View') { echo 'readonly' ; } ?>>
                            <input type="hidden" name="current_date" value="<?php echo $params['global_sysdate']?>">
                            <input type="hidden" name="finyr_start_date" value="<?php echo $params['global_curr_finyr_fymddate']?>">
                            <input type="hidden" name="finyr_end_date" value="<?php echo $params['global_curr_finyr_lymddate']?>">
                        </div>
                        <?php } ?>
                        <div class="d-inline-block w-100 mt-2">		
                            <input type="hidden" name="user_option" value="<?= $user_option ?>">
                            <input type="hidden" name="selemode" value="<?= $selemode ?>">	
                            <?php if($user_option == 'Add' || $user_option == 'Edit' || $user_option == 'Delete' || $user_option == 'Print' || $user_option == 'Approve') { ?>
                            <button type="submit" class="btn btn-primary cstmBtn ms-2">Confirm</button>	
                            <?php } ?>
                            <a href="<?= base_url(session()->last_selected_end_menu) ?>" class="btn btn-primary cstmBtn btn-cncl">Back</a>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </form>
    </section>
    <?php } else { echo view('pages/OtherExpenses/common_print_expenses'); } ?>
</main><!-- End #main -->

<?php if(!isset($print)) { ?> 
<script>

</script>
<?php } ?>

<?= $this->endSection() ?>