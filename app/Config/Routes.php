<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('MasterController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->group('sinhaco', static function ($routes) {

$routes->match(['get','post'], '/login', 'UserLoginController::login');
$routes->get('/api/username/(:any)', 'ApiController::username/$1');
$routes->get('/api/checkusername/(:any)', 'ApiController::checkusername/$1');
$routes->get('/api/duplicate_code_check/(:any)', 'ApiController::duplicate_code_check/$1');
$routes->get('/api/duplicate_codename_check/(:any)', 'ApiController::duplicate_codename_check/$1');
$routes->get('/api/disp_code_mas_tab/(:any)', 'ApiController::disp_code_mas_tab/$1');
$routes->get('/api/check_case_number/(:any)', 'ApiController::check_case_number/$1');
$routes->get('/api/check_password/(:any)', 'ApiController::check_password/$1');
$routes->get('/api/mymattercheck/(:any)', 'ApiController::mymattercheck/$1');
$routes->get('/api/getAddress/(:any)', 'ApiController::getAddress/$1');
$routes->get('/api/getAttention/(:any)', 'ApiController::getAttention/$1');
$routes->get('/api/getBillDetails/(:any)', 'ApiController::getBillDetails/$1');
$routes->get('/api/myRecSelectall/(:any)', 'ApiController::myRecSelectall/$1');
$routes->get('/api/chkCaseDet/(:any)', 'ApiController::chkCaseDet/$1');
$routes->get('/api/checkCaseNo/(:any)', 'ApiController::checkCaseNo/$1');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    
    $routes->get('/', 'HomeController::dashboard');
    $routes->get('/home', 'HomeController::home');
    $routes->get('/dashboard', 'HomeController::dashboard');
    // $routes->get('/(:any)', 'Home::index/$1');
    $routes->get('/disp', 'MasterController::index');
    
    // $routes->get('/view', 'Master::view');
    $routes->get('/logout', 'UserLoginController::logout');
    
    
    /*=================================== Master Routing ===================================*/
     /*--Done By Sylvester--*/
    $routes->group('master', static function ($routes) {
        $routes->match(['get','post'], 'master-list/', 'MasterController::master_list');
        $routes->match(['get','post'], 'account-head-master/(:any)', 'MasterController::account_head_master/$1');
  		$routes->match(['get','post'], 'mas-account-master-pl/(:any)', 'MasterController::mas_account_master_pl/$1');
        $routes->match(['get','post'], 'associate-list/', 'MasterController::associate_list');
        $routes->match(['get','post'], 'associate-list-add/(:any)', 'MasterController::associate_list_add/$1');
        $routes->match(['get','post'], 'client-master-list/', 'MasterController::client_master_list');
        $routes->match(['get','post'], 'client-master/(:any)', 'MasterController::client_master/$1');
        $routes->match(['get','post'], 'client-address-list/', 'MasterController::client_address_list');
        $routes->match(['get','post'], 'client-address-addedit/(:any)', 'MasterController::client_address_addedit/$1');
        $routes->match(['get','post'], 'client-attention-list/', 'MasterController::client_attention_list');
        $routes->match(['get','post'], 'client-attention-addedit/', 'MasterController::client_attention_addedit/');
        //$routes->match(['get','post'], 'client-attention-addedit/(:any)', 'MasterController::client_attention_addedit/$1');
        $routes->match(['get','post'], 'matter-master/', 'MasterController::matter_master');
        $routes->match(['get','post'], 'matter-masteraddedit/(:any)', 'MasterController::matter_masteraddedit/$1'); 
        $routes->match(['get','post'], 'mas-matter-type/', 'MasterController::mas_matter_type');
        $routes->match(['get','post'], 'mas-matter-type/(:any)', 'MasterController::mas_matter_type/$1');
        $routes->match(['get','post'], 'mas-matter-sub-type/', 'MasterController::mas_matter_sub_type');
        $routes->match(['get','post'], 'mas-matter-sub-type/(:any)', 'MasterController::mas_matter_sub_type/$1');
        $routes->match(['get','post'], 'client-details/', 'MasterController::client_details');
        $routes->match(['get','post'], 'client-details-combine/', 'MasterController::client_details_combine');
        $routes->match(['get','post'], 'mas-matter-sub-sub-type/', 'MasterController::mas_matter_sub_sub_type');
        $routes->match(['get','post'], 'mas-bank-master/(:any)', 'MasterController::mas_bank_master/$1');
        $routes->match(['get','post'], 'mas-company-master/', 'MasterController::mas_company_master');
        $routes->match(['get','post'], 'mas-barnch-master/', 'MasterController::mas_branch_master');
        $routes->match(['get','post'], 'mas-building-master/', 'MasterController::mas_building_master');
        $routes->match(['get','post'], 'mas-daybook-master/', 'MasterController::mas_daybook_master');
        $routes->match(['get','post'], 'mas-department-master/', 'MasterController::mas_department_master');
        $routes->match(['get','post'], 'mas-designation-master/', 'MasterController::mas_designation_master');
        $routes->match(['get','post'], 'mas-employee-master/', 'MasterController::mas_employee_master');
        $routes->match(['get','post'], 'mas-initial-master/', 'MasterController::mas_initial_master');
        $routes->match(['get','post'], 'mas-code-master/', 'MasterController::mas_code_master');
        $routes->match(['get','post'], 'mas-code-master-list/', 'MasterController::mas_code_master_list');
        $routes->match(['get','post'], 'mas-code-master-insert/', 'MasterController::mas_code_master_insert');
        $routes->match(['get','post'], 'mas-courier/', 'MasterController::mas_courier');
        $routes->match(['get','post'], 'mas-photocopy/', 'MasterController::mas_photocopy');
        $routes->match(['get','post'], 'mas-billing-rate/', 'MasterController::mas_billing_rate');
        $routes->match(['get','post'], 'mas-sub-account-master/', 'MasterController::mas_sub_account_master');
        $routes->match(['get','post'], 'mas-supplier-master/', 'MasterController::mas_supplier_master');
        $routes->match(['get','post'], 'mas-st-narration/', 'MasterController::mas_st_narration');
        $routes->match(['get','post'], 'mas-state-master/', 'MasterController::mas_state_master');
        $routes->match(['get','post'], 'mas-tax-master/', 'MasterController::mas_tax_master');
        $routes->match(['get','post'], 'mas-activity-master/', 'MasterController::mas_activity_master');
        $routes->match(['get','post'], 'mas-expense-master/', 'MasterController::mas_expense_master');
        $routes->match(['get','post'], 'mas-other-payee/', 'MasterController::mas_other_payee');
        $routes->match(['get','post'], 'mas-mis-name-master/', 'MasterController::mas_mis_name_master');
        $routes->match(['get','post'], 'mas-mis-exps-master/', 'MasterController::mas_mis_exps_master');
        $routes->match(['get','post'], 'mas-consultant-master/', 'MasterController::mas_consultant_master');
        $routes->match(['get','post'], 'file-uploads/(:any)', 'MasterController::file_uploads/$1');
        $routes->match(['get','post'], '(:any)', 'MasterController::master_list/$1'); 
    });
    $routes->group('master-list', static function ($routes) {
        $routes->match(['get','post'], '/', 'MasterController::master_list');
        $routes->match(['get','post'], '(:any)', 'MasterController::master_list/$1');
    });
     $routes->group('disp-master-report', static function ($routes) {
        $routes->match(['get','post'], '/', 'SystemController::disp_master_report');
        $routes->match(['get','post'], '(:any)', 'SystemController::disp_master_report/$1');
    });
    /*-
    /*--End By Sylvester--*/
    
    /*=================================== Finance Routing ===================================*/
    $routes->group('finance', static function ($routes) {
        
        /*-------------------------- Transaction Routing (Vouchers) --------------------------*/ 
        $routes->match(['get','post'], 'payment', 'FinanceController::payment');
        $routes->match(['get','post'], 'receipt-others', 'FinanceController::receipt_others');
        $routes->match(['get','post'], 'receipt-client', 'FinanceController::receipt_client');
        $routes->match(['get','post'], 'cash-bank-cash', 'FinanceController::cash_bank_cash');
        $routes->match(['get','post'], 'receipt-bill-adjustment-client', 'FinanceController::receipt_bill_adjustment_client');
        $routes->match(['get','post'], 'receipt-voucher-bulk', 'FinanceController::receipt_voucher_bulk');
       /*-------------------------- Transaction Routing (Journels) --------------------------*/
        $routes->match(['get','post'], 'adjustment', 'FinanceController::adjustment');
        $routes->match(['get','post'], 'advance-adjustment', 'FinanceController::advance_adjustment');
        $routes->match(['get','post'], 'advance-transfer-jv', 'FinanceController::advance_transfer_jv');
        $routes->match(['get','post'], 'advance-adjustment-jv', 'FinanceController::advance_adjustment_jv');
        $routes->match(['get','post'], 'counsel-memo-jv', 'FinanceController::counsel_memo_jv');
        
        /*---------------------------- Reports Routing ----------------------------*/
        $routes->match(['get','post'], 'daybook', 'FinanceController::daybook');
        $routes->match(['get','post'], 'payment-register', 'FinanceController::payment_register');
        $routes->match(['get','post'], 'receipt-register', 'FinanceController::receipt_register');
        $routes->match(['get','post'], 'journal-register', 'FinanceController::journal_register');
        $routes->match(['get','post'], 'acknowledgement-slip', 'FinanceController::acknowledgement_slip');
        $routes->match(['get','post'], 'money-receipt', 'FinanceController::money_receipt');
        $routes->match(['get','post'], 'courtwise-expenses', 'FinanceController::list_of_courtwise_expenses');
        $routes->match(['get','post'], 'balance-sub-ac-wise', 'FinanceController::list_of_balance_sub_ac_wise');
        $routes->match(['get','post'], 'balance-client-wise', 'FinanceController::list_of_balance_client_wise');
        $routes->match(['get','post'], 'advances-received', 'FinanceController::list_of_advances_received');
        $routes->match(['get','post'], 'advances-paid', 'FinanceController::list_of_advances_paid');
        $routes->match(['get','post'], 'courtwise-unbilled-expenses', 'FinanceController::courtwise_unbilled_expenses');
        $routes->match(['get','post'], 'advance-adjusted', 'FinanceController::list_of_advance_adjusted');
        $routes->match(['get','post'], 'advance-unadjusted', 'FinanceController::list_of_advance_unadjusted');
        $routes->match(['get','post'], 'ac-report-7053-7048-7068', 'FinanceController::selected_ac_report_7053_7048_7068');
        $routes->match(['get','post'], 'ac-report-5110', 'FinanceController::selected_ac_report_5110');
        $routes->match(['get','post'], 'ac-report-7021-receiver', 'FinanceController::selected_ac_report_7021_receiver');
        $routes->match(['get','post'], 'general-ledger', 'FinanceController::general_ledger');
        $routes->match(['get','post'], 'sub-ledger', 'FinanceController::sub_ledger');
        $routes->match(['get','post'], 'client-ledger', 'FinanceController::client_ledger');
        $routes->match(['get','post'], 'matter-ledger', 'FinanceController::matter_ledger');
        $routes->match(['get','post'], 'trial-balance', 'FinanceController::trial_balance');
        $routes->match(['get','post'], 'ceo-expenses', 'FinanceController::ceo_expenses');
        $routes->match(['get','post'], 'receiver-remuneration', 'FinanceController::receiver_remuneration');
        $routes->match(['get','post'], 'photocopy-expenses-report', 'FinanceController::photocopy_expenses_report');
        $routes->match(['get','post'], 'loan-confirmation-ac', 'FinanceController::loan_confirmation_ac');
        $routes->match(['get','post'], 'payment-summary', 'FinanceController::payment_summary');
    });
    
    /*=================================== Billing Routing ===================================*/
    $routes->group('billing', static function ($routes) {
        /*-------------------------- Transaction Routing --------------------------*/
        $routes->match(['get','post'], 'history/(:any)', 'BillingController::bill_history/$1');
        $routes->match(['get','post'], 'generation-matter', 'BillingController::bill_generation');
        $routes->match(['get','post'], 'generation-matter/(:any)', 'BillingController::bill_generation/$1');
        $routes->match(['get','post'], 'editing', 'BillingController::bill_editing');
        $routes->match(['get','post'], 'editing/(:any)', 'BillingController::bill_editing/$1');  
        $routes->match(['get','post'], 'copying', 'BillingController::bill_copying');  
        $routes->match(['get','post'], 'copying/(:any)', 'BillingController::bill_copying/$1');  
        $routes->match(['get','post'], 'approval', 'BillingController::bill_approval'); 
        $routes->match(['get','post'], 'approval/(:any)', 'BillingController::bill_approval/$1'); 
        $routes->match(['get','post'], 'cancellation-draft', 'BillingController::bill_cancellation_draft'); 
        $routes->match(['get','post'], 'cancellation-draft/(:any)', 'BillingController::bill_cancellation_draft/$1'); 
        $routes->match(['get','post'], 'cancellation-final', 'BillingController::bill_cancellation_final'); 
        $routes->match(['get','post'], 'cancellation-final/(:any)', 'BillingController::bill_cancellation_final/$1'); 
        $routes->match(['get','post'], 'collection-status', 'BillingController::bill_collection_status'); 
        $routes->match(['get','post'], 'collection-status/(:any)', 'BillingController::bill_collection_status/$1'); 
        $routes->match(['get','post'], 'settlement', 'BillingController::bill_settlement'); 
        $routes->match(['get','post'], 'settlement/(:any)', 'BillingController::bill_settlement/$1'); 
        $routes->match(['get','post'], 'final-bill-open', 'BillingController::final_bill_open'); 
        $routes->match(['get','post'], 'final-bill-open/(:any)', 'BillingController::final_bill_open/$1'); 
        $routes->match(['get','post'], 'final-bill-editing', 'BillingController::final_bill_editing'); 
        $routes->match(['get','post'], 'final-bill-editing/(:any)', 'BillingController::final_bill_editing/$1'); 
        $routes->match(['get','post'], 'send-entry', 'BillingController::bill_send_entry'); 
        $routes->match(['get','post'], 'send-entry/(:any)', 'BillingController::bill_send_entry/$1'); 
        $routes->match(['get','post'], 'final-bill-updation', 'BillingController::final_bill_updation'); 
        $routes->match(['get','post'], 'final-bill-updation/(:any)', 'BillingController::final_bill_updation/$1'); 
        $routes->match(['get','post'], 'summary-correction', 'BillingController::bill_summary_correction'); 
        $routes->match(['get','post'], 'summary-correction/(:any)', 'BillingController::bill_summary_correction/$1'); 
    
    
        /*---------------------------- Reports Routing ----------------------------*/
        $routes->match(['get','post'], 'printing-draft', 'BillingController::bill_printing_draft');
        $routes->match(['get','post'], 'printing-draft/(:any)', 'BillingController::bill_printing_draft/$1');  
        $routes->match(['get','post'], 'printing-final', 'BillingController::bill_printing_final');
        $routes->match(['get','post'], 'printing-final/(:any)', 'BillingController::bill_printing_final/$1');  
        $routes->match(['get','post'], 'register-bill-client-matter-initial', 'BillingController::bill_register_bill_client_matter_initial');
        $routes->match(['get','post'], 'register-court-client-matter-initial', 'BillingController::bill_register_court_client_matter_initial');
        $routes->match(['get','post'], 'realisation', 'BillingController::bill_realisation');
        $routes->match(['get','post'], 'os-details', 'BillingController::bill_os_details');
        $routes->match(['get','post'], 'os-summary', 'BillingController::bill_os_summary');
        $routes->match(['get','post'], 'followup-letter-billing-address', 'BillingController::bill_followup_letter_billing_addr');
        $routes->match(['get','post'], 'followup-letter-specific-address', 'BillingController::bill_followup_letter_specific_addr');
        $routes->match(['get','post'], 'activity-cost-statement', 'BillingController::activity_cost_statement');
        $routes->match(['get','post'], 'activity-cost-statement/(:any)', 'BillingController::activity_cost_statement/$1');
        $routes->match(['get','post'], 'ledger', 'BillingController::bill_ledger');
        $routes->match(['get','post'], 'print', 'BillingController::bill_print');
        $routes->match(['get','post'], 'excel-bill-list', 'BillingController::excel_bill_list');
        $routes->match(['get','post'], 'register-court-initial', 'BillingController::bill_register_court_initial');
        $routes->match(['get','post'], 'send', 'BillingController::bill_send');
    });
    
    /*=================================== Counsel Routing ===================================*/
    $routes->group('counsel', static function ($routes) {
        /*-------------------------- Transaction Routing --------------------------*/
        $routes->match(['get','post'], 'memo-maint-direct', 'CounselController::memo_maint_direct');
        $routes->match(['get','post'], 'memo-entry', 'CounselController::memo_entry');  
        /*---------------------------- Reports Routing ----------------------------*/
        $routes->match(['get','post'], 'memo-credited', 'CounselController::counsel_memo_credited');
        $routes->match(['get','post'], 'memo-os', 'CounselController::counsel_memo_os');
        $routes->match(['get','post'], 'memo-direct-payment-os', 'CounselController::counsel_memo_direct_payment_os');
        $routes->match(['get','post'], 'direct-memo-followup', 'CounselController::direct_memo_followup');
    });
    
    /*=================================== Case-Details Routing ===================================*/
    $routes->group('case', static function ($routes) {
        /*-------------------------- Transaction Routing --------------------------*/
        $routes->match(['get','post'], 'history/(:any)', 'CaseDetailsController::case_history/$1');
        $routes->get('status', 'CaseDetailsController::case_status');
        $routes->match(['get','post'], 'status/(:any)', 'CaseDetailsController::case_status/$1');
        $routes->get('status-open', 'CaseDetailsController::case_status_open'); 
        $routes->match(['get','post'], 'alert-close', 'CaseDetailsController::case_alert_close');
        $routes->match(['get','post'], 'change-billing-option', 'CaseDetailsController::change_billing_option');
        $routes->match(['get','post'], 'status-spell-check', 'CaseDetailsController::case_status_spell_check');
        $routes->match(['get','post'], 'billed-case-status-edit', 'CaseDetailsController::billed_case_status_edit');
        $routes->match(['get','post'], 'billed-case-status-edit/(:any)', 'CaseDetailsController::billed_case_status_edit/$1');
        /*---------------------------- Reports Routing ----------------------------*/
        $routes->match(['get','post'], 'cases-appeared', 'CaseDetailsController::cases_appeared');
        $routes->match(['get','post'], 'cases-tobe-appeared', 'CaseDetailsController::cases_tobe_appeared');
        $routes->match(['get','post'], 'list-of-cases', 'CaseDetailsController::list_of_cases');
        $routes->match(['get','post'], 'list-of-unbilled-case-status', 'CaseDetailsController::list_of_unbilled_case_status');
        $routes->match(['get','post'], 'list-of-case-status', 'CaseDetailsController::list_of_case_status');
        $routes->match(['get','post'], 'cases-appeared-prepare-date', 'CaseDetailsController::cases_appeared_prepare_date');
        $routes->match(['get','post'], 'status-of-matters', 'CaseDetailsController::status_of_matters');
    });
    
    /*=================================== Miscellaneous-Letters Routing ===================================*/
    $routes->group('miscellaneous-letters', static function ($routes) {
        /*-------------------------- Transaction Routing --------------------------*/
        $routes->match(['get','post'], 'action', 'MiscellaneousLettersController::actions');
        $routes->match(['get','post'], 'letter', 'MiscellaneousLettersController::letter');
        /*---------------------------- Reports Routing ----------------------------*/
        $routes->match(['get','post'], 'list-of-unbilled-notice', 'MiscellaneousLettersController::list_of_unbilled_notice');
    });  
        
    /*=================================== Other-Expenses Routing ===================================*/
    $routes->group('other-expenses', static function ($routes) {
        $routes->match(['get','post'], 'court-expenses', 'OtherExpensesController::court_expenses');
        $routes->match(['get','post'], 'photocopy-expenses', 'OtherExpensesController::photocopy_expenses');
        $routes->match(['get','post'], 'courier-expenses', 'OtherExpensesController::courier_expenses');
        $routes->match(['get','post'], 'stenographer-expenses', 'OtherExpensesController::stenographer_expenses');
        /*---------------------------- Reports Routing ----------------------------*/
        $routes->match(['get','post'], 'list-of-other-expenses', 'OtherExpensesController::list_of_other_expenses');
    });  
    /*=================================== TDS Routing ===================================*/
    $routes->group('tds', static function ($routes) {
        /*-------------------------- Transaction Routing --------------------------*/
        $routes->match(['get','post'], 'deposited-by-company', 'TdsController::deposited_by_company');
        $routes->match(['get','post'], 'received-from-client', 'TdsController::received_from_client');
        $routes->match(['get','post'], 'acknowledgement-no', 'TdsController::acknowledgement_no');
        /*---------------------------- Reports Routing ----------------------------*/
        $routes->match(['get','post'], 'not-deposited', 'TdsController::not_deposited');
        $routes->match(['get','post'], 'payable-certificate-status', 'TdsController::payable_certificate_status');
        $routes->match(['get','post'], 'certificate-fresh', 'TdsController::certificate_fresh');
        $routes->match(['get','post'], 'receivable-certificate-status', 'TdsController::receivable_certificate_status');
        $routes->match(['get','post'], 'receivable-followup-letter', 'TdsController::receivable_followup_letter');
        $routes->match(['get','post'], 'certificate-duplicate', 'TdsController::certificate_duplicate');
    });  
    
    /*=================================== Bank-Reconciliation Routing ===================================*/
    $routes->group('bank-reconciliation', static function ($routes) {
        /*-------------------------- Transaction Routing --------------------------*/
        $routes->match(['get','post'], 'entry', 'BankReconciliationController::bank_reconciliation_entry');
        $routes->match(['get','post'], 'entry/(:any)', 'BankReconciliationController::bank_reconciliation_entry/$1');
        $routes->match(['get','post'], 'transections-debited-credited', 'BankReconciliationController::bank_transections_debited_credited');
        $routes->match(['get','post'], 'transections-debited-credited/(:any)', 'BankReconciliationController::bank_transections_debited_credited/$1');
        /*-------------------------- Report Routing --------------------------*/
        $routes->match(['get','post'], 'statement', 'BankReconciliationController::bank_reconciliation_statement');
    }); 
    
    /*=================================== MIS Routing ===================================*/
    $routes->group('mis', static function ($routes) {
        $routes->match(['get','post'], 'os-bill-summary', 'MISController::os_bill_summary');
        $routes->match(['get','post'], 'os-bill-details', 'MISController::os_bill_details');
        $routes->match(['get','post'], 'os-bill-age-analysis', 'MISController::os_bill_age_analysis');
        $routes->match(['get','post'], 'billing-count-summary', 'MISController::billing_count_summary');
        $routes->match(['get','post'], 'bill-realisation-count-summary', 'MISController::bill_realisation_count_summary');
        $routes->match(['get','post'], 'realisation-detail-for-client', 'MISController::realisation_detail_for_client');
        $routes->match(['get','post'], 'os-bill-printing-period', 'MISController::os_bill_printing_period');
        $routes->match(['get','post'], 'os-bill-summary-old', 'MISController::os_bill_summary_old');
        $routes->match(['get','post'], 'counsel-memo-credited', 'MISController::counsel_memo_credited');
        $routes->match(['get','post'], 'counsel-memo-os', 'MISController::counsel_memo_os');
        $routes->match(['get','post'], 'matter-status-latest', 'MISController::matter_status_latest');
        $routes->match(['get','post'], 'matter-history', 'MISController::matter_history');
        $routes->match(['get','post'], 'matters-opened-during-a-period', 'MISController::matters_opened_during_a_period');
        $routes->match(['get','post'], 'matter-history-2', 'MISController::matter_history_2');
        $routes->match(['get','post'], 'matter-status', 'MISController::matter_status');
        $routes->match(['get','post'], 'matter-information', 'MISController::matter_information');
        $routes->match(['get','post'], 'case-detail-quiry', 'MISController::case_detail_quiry');
        $routes->match(['get','post'], 'client-matter-change-history', 'MISController::client_matter_change_history');
        $routes->match(['get','post'], 'download_matter_files', 'MISController::download_matter_files');
        $routes->match(['get','post'], 'matter-ps-and-other', 'MISController::matter_ps_and_other');
        $routes->match(['get','post'], 'excel-cause-list', 'MISController::excel_cause_list');
        $routes->match(['get','post'], 'instrument-receive', 'MISController::instrument_receive');
        $routes->match(['get','post'], 'client-list-new', 'MISController::client_list_new');
        $routes->match(['get','post'], 'bill-register-service-tax', 'MISController::bill_register_service_tax');
        $routes->match(['get','post'], 'bill-realisation-service-tax', 'MISController::bill_realisation_service_tax');
        $routes->match(['get','post'], 'payments-made-to-party-service-tax', 'MISController::payments_made_to_party_service_tax');

    });
    
	/*=================================== Admin Routing ===================================*/
	$routes->group('admin', static function ($routes) {
    	$routes->match(['get','post'], 'user-details', 'AdminController::user_details');
    	$routes->match(['get','post'], 'user-details/(:any)', 'AdminController::user_details/$1');
        $routes->match(['get','post'], 'system-menu', 'AdminController::system_menu');
        $routes->match(['get','post'], 'system-menu/(:any)', 'AdminController::system_menu/$1');
        $routes->match(['get','post'], 'query-details', 'AdminController::query_details');
        $routes->match(['get','post'], 'query-details-add/(:any)', 'AdminController::query_details_add/$1');
        $routes->match(['get','post'], 'system-menu-activity', 'AdminController::system_menu_activity');
        $routes->match(['get','post'], 'system-menu-activity/(:any)', 'AdminController::system_menu_activity/$1');
        $routes->match(['get','post'], 'system-menu-perm', 'AdminController::system_menu_perm');
        $routes->match(['get','post'], 'system-menu-perm/(:any)', 'AdminController::system_menu_perm/$1');
        $routes->match(['get','post'], 'sys-user-initial-perm', 'AdminController::sys_user_initial_perm');
        $routes->match(['get','post'], 'sys-user-initial-perm/(:any)', 'AdminController::sys_user_initial_perm/$1');
        $routes->match(['get','post'], 'user-role/', 'AdminController::user_role');
        $routes->match(['get','post'], 'user-role/(:any)', 'AdminController::user_role/$1');
        $routes->match(['get','post'], 'permission-add/', 'AdminController::permission_add');
        $routes->match(['get','post'], 'permission-add/(:any)', 'AdminController::permission_add/$1');
        $routes->match(['get','post'], 'role-permission-add/', 'AdminController::role_permission_add');
        $routes->match(['get','post'], 'role-permission-add/(:any)', 'AdminController::role_permission_add/$1');
        $routes->match(['get','post'], 'excel-files-upload/', 'AdminController::excel_files_upload');
        $routes->match(['get','post'], 'client-report/', 'AdminController::client_report');
        
	});
	 /*=================================== System Routing ===================================*/
	$routes->group('system', static function ($routes) {
        $routes->match(['get','post'], 'change-password/', 'SystemController::change_password');
        $routes->match(['get','post'], 'matter-merge/', 'SystemController::matter_merge');
        $routes->match(['get','post'], 'client-merge/', 'SystemController::client_merge');
        $routes->match(['get','post'], 'matter-client-updation/', 'SystemController::matter_client_updation');
        $routes->match(['get','post'], 'matter-status-change/', 'SystemController::matter_status_change');
        $routes->match(['get','post'], 'matter-copy/', 'SystemController::matter_copy');
        $routes->match(['get','post'], 'holiday-master/', 'SystemController::holiday_master');
        $routes->match(['get','post'], 'schedule-task/', 'SystemController::schedule_task');
        $routes->match(['get','post'], 'matter-data-transfer/', 'SystemController::matter_data_transfer');
        $routes->match(['get','post'], 'excel-files/', 'SystemController::excel_files');
        $routes->match(['get','post'], 'notice-download/', 'SystemController::notice_download');
        
        
	});  
    $routes->get('download_excel', 'SystemController::download_excel');
    $routes->get('download_notice', 'SystemController::download_notice');
     /*=================================== Query Routing ===================================*/
	$routes->group('query', static function ($routes) {
        $routes->match(['get','post'], 'query-details/', 'QueryController::query_details');
        $routes->match(['get','post'], 'matter-information/', 'QueryController::matter_information');   
        $routes->match(['get','post'], 'payment-made-to-emp/', 'QueryController::payment_made_to_emp');   
        $routes->match(['get','post'], 'voucher-view/', 'QueryController::voucher_view');
        $routes->match(['get','post'], 'payment-made-to-consltn/', 'QueryController::payment_made_to_consltn');   
        $routes->match(['get','post'], 'consltn-voucher-view/', 'QueryController::consltn_voucher_view');   
        $routes->match(['get','post'], 'finance-query-details/', 'QueryController::finance_query_details');
        $routes->match(['get','post'], 'payment-made/', 'QueryController::voucher_view');
        $routes->match(['get','post'], 'payment-to-party-voucher/', 'QueryController::payment_to_party_voucher');
        $routes->match(['get','post'], 'councel-memo-query-details/', 'QueryController::councel_memo_query_details');
        $routes->match(['get','post'], 'councel-memo-view/', 'QueryController::councel_memo_view');
        $routes->match(['get','post'], 'councel-memo-credited-view/', 'QueryController::councel_memo_credited_view');
        $routes->match(['get','post'], 'billing-query-details/', 'QueryController::billing_query_details');
        $routes->match(['get','post'], 'qry-bill-details-billno/', 'QueryController::qry_bill_details_billno');
        $routes->match(['get','post'], 'qry-bill-details-bill-no-realisation/', 'QueryController::qry_bill_details_bill_no_realisation');
        $routes->match(['get','post'], 'qry-bill-details-matter/', 'QueryController::qry_bill_details_matter');
        $routes->match(['get','post'], 'bill-details-matter/', 'QueryController::bill_details_matter');
        $routes->match(['get','post'], 'rep-final-bill-tax/', 'QueryController::rep_final_bill_tax');
        $routes->match(['get','post'], 'qry-bill-not-approved/', 'QueryController::qry_bill_not_approved');
        $routes->match(['get','post'], 'bill-not-approved-rp/', 'QueryController::bill_not_approved_rp');
        $routes->match(['get','post'], 'case-status-query-details/', 'QueryController::case_status_query_details');
        $routes->match(['get','post'], 'case-details-client-matter-wise/', 'QueryController::case_details_client_matter_wise');
        $routes->match(['get','post'], 'case-details-client-matter-view/', 'QueryController::case_details_client_matter_view');
        $routes->match(['get','post'], 'case-details-matter-view/', 'QueryController::case_details_matter_view');
        $routes->match(['get','post'], 'case-status-client-date-wise/', 'QueryController::case_status_client_date_wise');
        $routes->match(['get','post'], 'print/', 'QueryController::print');
	});  
     /*=================================== HR Routing ===================================*/
     $routes->group('hr', static function ($routes) {
        $routes->match(['get','post'], 'employe-details-general/', 'HrController::employe_details_general');
        $routes->match(['get','post'], 'notice-upload/', 'HrController::notice_upload');
        $routes->match(['get','post'], 'upload-file/', 'HrController::upload_file');
        $routes->match(['get','post'], 'notice-download/', 'HrController::notice_download');
        $routes->match(['get','post'], 'time-sheet-upload/', 'HrController::time_sheet_upload');
        $routes->match(['get','post'], 'time-sheet-download/', 'HrController::time_sheet_download');

        
    });
    $routes->get('download_timesheet', 'HrController::download_timesheet');
    /*=================================== API Routing ===================================*/
    $routes->group('api', static function ($routes) {
        $routes->get('lookup', 'ApiController::lookup');
        $routes->get('lookup/(:any)', 'ApiController::lookup_byID/$1');
        $routes->get('matterDetails/(:any)', 'ApiController::getMatterValue/$1');
        $routes->get('billDetails/(:any)', 'ApiController::getBillInfo/$1');
        $routes->get('matterInfo/(:any)', 'ApiController::getMatterInfo/$1');
        $routes->get('FinalBillSerial/(:any)', 'ApiController::myFinalBillSerial/$1');
        $routes->get('getCourierExpensesById/(:any)', 'ApiController::getCourierExpensesById/$1');
        $routes->get('get_finance_details/(:any)', 'ApiController::get_finance_details/$1/$1');
        $routes->get('VoucherDetails/(:any)', 'ApiController::myVoucherDetails/$1');
    });   
    
    $routes->get('/excel', 'ExcelTest::index');
});

// });
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
