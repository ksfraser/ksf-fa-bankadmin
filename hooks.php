<?php
declare(strict_types=1);
require_once __DIR__ . '/ComposerDependencies.php';
\Ksfraser\FrontAccounting\BankAdmin\Utils\ComposerDependencies::ensure(__DIR__);
$autoload_path = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($autoload_path)) { require_once $autoload_path; }
require_once __DIR__ . '/includes/common.inc';
require_once __DIR__ . '/includes/events.inc';
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
define('SS_ksf_FA_BankAdmin', 176 << 8);
if (!defined('SA_BANKADMIN_VIEW')) {
    define('SA_BANKADMIN_VIEW', SS_ksf_FA_BankAdmin | 1);
    define('SA_BANKADMIN_MANAGE', SS_ksf_FA_BankAdmin | 2);
    define('SA_BANKADMIN_RECONCILE', SS_ksf_FA_BankAdmin | 3);
}
class hooks_ksf_FA_BankAdmin extends hooks {
    const RESPONDERS = array('RELATED_TO_FIELDS', 'BANKACCOUNT_RESOLVE', 'BANKACCOUNT_FIELDS', 'BANKTRANSACTION_RESOLVE', 'BANKTRANSACTION_FIELDS');
    var $module_name = 'ksf_FA_BankAdmin';
    var $version = '1.0.0';
    function install_tabs($app) { }
    function install_options($app) { }
    function install_access() {
        $security_sections[SS_ksf_FA_BankAdmin] = _("Bank Administration");
        $security_areas['SA_BANKADMIN_VIEW'] = array(SA_BANKADMIN_VIEW, _("View bank accounts"));
        $security_areas['SA_BANKADMIN_MANAGE'] = array(SA_BANKADMIN_MANAGE, _("Manage bank accounts"));
        $security_areas['SA_BANKADMIN_RECONCILE'] = array(SA_BANKADMIN_RECONCILE, _("Reconcile bank accounts"));
        return array($security_areas, $security_sections);
    }
    function activate_extension($company, $check_only = true) {
        if ($check_only) { return true; }
        \Ksfraser\FrontAccounting\BankAdmin\Utils\ComposerDependencies::ensure(__DIR__);
        $this->update_databases($company, array(
            '0_ksf_bank_accounts.sql' => array('ksf_bank_accounts'),
            '0_ksf_bank_transactions.sql' => array('ksf_bank_transactions'),
        ), false);
        return true;
    }
    private function ensure_composer_dependencies() {
        \Ksfraser\FrontAccounting\BankAdmin\Utils\ComposerDependencies::ensure(__DIR__);
    }
    public function getModuleConstants(&$data, $opts = null) {
        $data['constants'] = array(
            'SS_ksf_FA_BankAdmin' => SS_ksf_FA_BankAdmin,
            'SA_BANKADMIN_VIEW' => SA_BANKADMIN_VIEW,
            'SA_BANKADMIN_MANAGE' => SA_BANKADMIN_MANAGE,
            'SA_BANKADMIN_RECONCILE' => SA_BANKADMIN_RECONCILE,
        );
        return true;
    }
    public function getModuleCapabilities(&$data, $opts = null) {
        $data['capabilities'] = self::RESPONDERS;
        return true;
    }
    public function hasCapability(&$data, $opts) {
        $capability = is_array($opts) && isset($opts['capability']) ? $opts['capability'] : null;
        if ($capability === null && isset($data['capability'])) { $capability = $data['capability']; }
        $data['has_capability'] = in_array($capability, self::RESPONDERS, true);
        return $data['has_capability'];
    }
    public function respondToCapabilityRequest(&$data, $opts = null) {
        $request = is_array($opts) && isset($opts['request']) ? $opts['request'] : (isset($data['request']) ? $data['request'] : 'capabilities');
        $data['request'] = $request;
        $data['module'] = $this->module_name;
        if (strpos($request, 'has:') === 0) { return $this->hasCapability($data, array('capability' => substr($request, 4))); }
        switch ($request) {
            case 'capabilities': return $this->getModuleCapabilities($data, $opts);
            case 'constants': return $this->getModuleConstants($data, $opts);
            default: $data['error'] = 'Unknown request type: ' . $request; return null;
        }
    }
    public function RELATED_TO_FIELDS(&$data, $opts = null) {
        if (!isset($data['module']) || !is_string($data['module'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[module]'; return null; }
        if (!isset($data['type']) || !is_string($data['type'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[type]'; return null; }
        if ($data['type'] === 'bank_account') {
            $data['fields'] = array_merge($data['fields'] ?? [], array(
                'account_number' => _('Account Number'),
                'account_name' => _('Account Name'),
                'account_type' => _('Account Type'),
                'balance' => _('Balance'),
                'currency' => _('Currency'),
            ));
        } elseif ($data['type'] === 'bank_transaction') {
            $data['fields'] = array_merge($data['fields'] ?? [], array(
                'transaction_number' => _('Transaction Number'),
                'transaction_type' => _('Transaction Type'),
                'amount' => _('Amount'),
                'transaction_date' => _('Transaction Date'),
                'reference' => _('Reference'),
                'status' => _('Status'),
            ));
        }
        return null;
    }
    public function BANKACCOUNT_RESOLVE(&$data, $opts = null) {
        if (!isset($data['account_id']) || !is_numeric($data['account_id'])) { $data['error'] = 'BANKACCOUNT_RESOLVE requires data[account_id]'; return null; }
        if (!isset($data['dto']) || !$data['dto'] instanceof RelatedRecordDto) { $data['error'] = 'BANKACCOUNT_RESOLVE requires data[dto] as RelatedRecordDto'; return null; }
        $service = new \Ksfraser\FrontAccounting\BankAdmin\Service\BankAccountService();
        $data['result'] = $service->resolveById((int) $data['account_id'], $data['dto']);
        return null;
    }
    public function BANKACCOUNT_FIELDS(&$data, $opts = null) {
        $data['fields'] = array(
            'account_number' => _('Account Number'),
            'account_name' => _('Account Name'),
            'account_type' => _('Account Type'),
            'balance' => _('Balance'),
            'currency' => _('Currency'),
        );
        return null;
    }
    public function BANKTRANSACTION_RESOLVE(&$data, $opts = null) {
        if (!isset($data['transaction_id']) || !is_numeric($data['transaction_id'])) { $data['error'] = 'BANKTRANSACTION_RESOLVE requires data[transaction_id]'; return null; }
        if (!isset($data['dto']) || !$data['dto'] instanceof RelatedRecordDto) { $data['error'] = 'BANKTRANSACTION_RESOLVE requires data[dto] as RelatedRecordDto'; return null; }
        $service = new \Ksfraser\FrontAccounting\BankAdmin\Service\BankTransactionService();
        $data['result'] = $service->resolveById((int) $data['transaction_id'], $data['dto']);
        return null;
    }
    public function BANKTRANSACTION_FIELDS(&$data, $opts = null) {
        $data['fields'] = array(
            'transaction_number' => _('Transaction Number'),
            'transaction_type' => _('Transaction Type'),
            'amount' => _('Amount'),
            'transaction_date' => _('Transaction Date'),
            'reference' => _('Reference'),
            'status' => _('Status'),
        );
        return null;
    }
}
