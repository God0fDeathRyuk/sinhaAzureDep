<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?= view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<main id="main" class="main">
    <div class="pagetitle d-inline-block w-100">
        <h1 class="col-md-11 float-start">Adjustment JV [<?= ucfirst($user_option) ?>]</h1>
        <a href="<?= base_url(session()->last_selected_end_menu) ?>" class="btn btn-primary cstmBtn btn-cncl mt-2">Back</a>
    </div><!-- End Page Title -->

    <?php if(!isset($print)) { ?> 
        <section class="section dashboard">
            <form action="" name="f1" method="post">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                            <?php if($user_option != 'Add') { ?>	
                            <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Serial#</label>
                                <input type="text" class="form-control" name="voucher_serial_no" value="<?= $params['voucher_serial_no'] ?>" readonly>
                            </div>
                            <?php } ?>
                            <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Branch  <strong class="text-danger">*</strong></label>
                                <select class="form-select cstm-inpt" name="branch_code" onClick="pass_close()" onBlur="pass_close()">
                                    <?php foreach($data['branches'] as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : $disk ?>><?= $branch['branch_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Year <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start me-1" name="fin_year" value="<?= $params['fin_year'] ?>" readonly />
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Entry Date <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start me-1 datepicker" name="voucher_serial_date" value="<?= $params['voucher_serial_date'] ?>" onBlur="make_date(this)" <?php echo $redv?> />
                                <input type="hidden" name="current_date" value="<?= $params['global_dmydate'] ?>">
                                <input type="hidden" name="finyr_start_date" value="<?= $params['finyr_start_date'] ?>">
                                <input type="hidden" name="finyr_end_date" value="<?= $params['finyr_end_date'] ?>">
                            </div>
                            <div class="d-inline-block w-100">
                                <span class="bdge d-block w-100 mb-2">From</span>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Adv# <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control" name="advance_serial_no" id="serialNo" value="<?= $params['advance_serial_no'] ?>" tabindex="2" onBlur="getAdvanceSerial()" <?php echo $redv?>/>
                                    <input type="hidden" class="form-control" name="old_advance_serial_no" value="<?= $params['old_advance_serial_no'] ?>" tabindex="2" onBlur="getAdvanceSerial()" <?php echo $redv?>/>
                                    <?php if($user_option=='Add' || $user_option=='Edit') {?>
                                        <i class="fa fa-binoculars icn-vw icn-vw2 lkupIcn" onclick="showData('serial_no', '<?= $displayId['advance_help_id']?>', 'serialNo', [], [], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                    <?php } ?>
                                </div>
                                <div class="col-md-9 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Client</label>					
                                    <input type="text" class="form-control w-33 float-start me-1" name="client_code_from" value="<?= $params['client_code_from'] ?>" readonly/>
                                    <input type="text" class="form-control w-65 float-start me-1" name="client_name_from" value="<?= $params['client_name_from'] ?>" readonly/>
                                </div>
                                <div class="col-md-9 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Matter</label>					
                                    <input type="text" class="form-control w-33 float-start me-1" name="matter_code_from"  value="<?= $params['matter_code_from'] ?>" readonly/>
                                    <input type="text" class="form-control w-65 float-start me-1" name="matter_desc_from" value="<?= $params['matter_desc_from'] ?>" readonly/>
                                    <input type="hidden" class="form-control w-65 float-start me-1" name="initial_code_from" value="<?= $params['initial_code_from'] ?>"/>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Adv Amt</label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="advance_amount" value="<?= $params['advance_amount'] ?>" readonly/>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Adv Adj</label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="adjusted_amount" value="<?= $params['adjusted_amount'] ?>" readonly/>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Adv Bal</label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="balance_amount" value="<?= $params['balance_amount'] ?>" readonly/>
                                </div>
                            </div>
                            
                            <div class="d-inline-block w-100">
                                <span class="bdge d-block w-100 mb-2">To</span>
                                <div class="col-md-12 float-start px-2 mb-1 position-relative">
                                    <div class="position-relative w-33">
                                        <label class="d-inline-block w-100 mb-1 lbl-mn">Client <strong class="text-danger">*</strong></label>					
                                        <input type="text" class="form-control w-100 float-start me-1" name="client_code_to" id="clientCode" value="<?= $params['client_code_to'] ?>" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" tabindex="11" <?= $redv ?>/>
                                        <?php if($user_option=='Add' || $user_option=='Edit') {?>
                                            <i class="fa fa-binoculars icn-vw icn-vw2 lkupIcn" onclick="showData('client_code', '<?= $displayId['client_help_id']?>', 'clientCode', ['clientName'], ['client_name'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                            <?php } ?>
                                        </div>
                                    <input type="text" class="form-control w-65 float-start me-1" id="clientName" name="client_name_to" value="<?= $params['client_name_to'] ?>" readonly/>
                                </div>
                                <div class="col-md-12 float-start px-2 mb-1 position-relative">
                                    <div class="position-relative w-33">
                                        <label class="d-inline-block w-100 mb-1 lbl-mn">Matter <strong class="text-danger">*</strong></label>					
                                        <input type="text" class="form-control w-100 float-start me-1" name="matter_code_to" id="matterCode" value="<?= $params['matter_code_to'] ?>" tabindex="12" <?= $redv ?> readonly/>
                                        <input type="hidden" class="form-control w-100 float-start me-1" name="initial_code_to" value="<?= $params['initial_code_to'] ?>"/>
                                        <?php if($user_option=='Add' || $user_option=='Edit') {?>
                                            <i class="fa fa-binoculars icn-vw icn-vw2 lkupIcn" onclick="showData('matter_code', 'display_id=<?= $displayId['matter_help_id']?>&myclient_code_to=@clientCode', 'matterCode', ['matterDesc'], ['matter_desc'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                        <?php } ?>
                                    </div>
                                    <input type="text" class="form-control w-65 float-start me-1" name="matter_desc_to" id="matterDesc" value="<?= $params['matter_desc_to'] ?>" readonly/>
                                </div>
                                <div class="col-md-4 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Amount <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="transfer_amount" value="<?= $params['transfer_amount'] ?>" tabindex="13" onBlur="format_number(this,2)" <?= $redv ?>/>						
                                    <input type="hidden" class="form-control w-100 float-start me-1" name="old_transfer_amount" value="<?= $params['old_transfer_amount'] ?>"/>						
                                </div>
                                <?php if($user_option == 'Approve') { ?>
                                <div class="col-md-4 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Date <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="voucher_date" value="<?= $params['voucher_date'] ?>" onBlur="make_date(this)"/>						
                                </div>
                                <?php } ?>
                                <div class="d-inline-block col-md-6 mt-28">	
                                    <input type="hidden" name="selemode" value="Y">
                                    <input type="hidden" name="user_option" value="<?= $user_option ?>">
                                    
                                    <?php if($user_option == 'Add' || $user_option == 'Edit' || $user_option == 'Delete' || $user_option == 'Print' || $user_option == 'Approve') { ?>
                                    <?php if($user_option != 'View') { ?> <button type="submit" class="btn btn-primary cstmBtn ms-2">Confirm</button> <?php } ?>				
                                    <?php } ?>
                                </div>
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
    function getAdvanceSerial() { 
        if (document.f1.advance_serial_no.value != '' && document.f1.advance_serial_no.value != document.f1.old_advance_serial_no.value) {
            fetch(`/sinhaco/api/get_finance_details/${document.f1.advance_serial_no.value}/AdvanceSerial`)
            .then((response) => response.json())
            .then((data) => {
                if(data.status) {
                    document.f1.client_code_from.value = data.client_code; 
                    document.f1.client_name_from.value = data.client_name; 
                    document.f1.matter_code_from.value = data.matter_code; 
                    document.f1.matter_desc_from.value = data.matter_desc; 
                    document.f1.initial_code_from.value = data.initial_code; 
                    document.f1.advance_amount.value = data.advance_amt; format_number(document.f1.advance_amount, 2);
                    document.f1.adjusted_amount.value = data.adjusted_amt; format_number(document.f1.adjusted_amount, 2);
                    document.f1.balance_amount.value = data.balance_amt; format_number(document.f1.balance_amount, 2);
                    document.f1.client_code_to.focus();
                } else {
                    console.log(data);
                    Swal.fire({ text: `${data.message}` }).then((result) => { setTimeout(() => {document.f1.advance_serial_no.value = ''; document.f1.advance_serial_no.focus()}, 500) });
                }
            });
		}
    }
</script>
<?php } ?>

<?= $this->endSection() ?>