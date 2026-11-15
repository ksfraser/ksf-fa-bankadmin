<?php
declare(strict_types=1);
use Ksfraser\FrontAccounting\BankAdmin\Service\BankTransactionService;
$path_to_root = '../../..';
include_once($path_to_root . '/includes/session.inc');
add_access_extensions();
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) { display_error(_('Bank Admin module dependencies are not installed')); exit; }
require_once $autoload;
$service = new BankTransactionService();
$action = isset($_GET['action']) ? (string) $_GET['action'] : 'list';
if ($action === 'list') {
    page(_('Bank Transactions'));
    $transactions = $service->listAll();
    echo '<p>' . _('Bank Transactions') . '</p>';
    echo '<table class="selection">';
    echo '<thead><tr>'
        . '<th>' . _('Transaction Number') . '</th>'
        . '<th>' . _('Transaction Type') . '</th>'
        . '<th>' . _('Amount') . '</th>'
        . '<th>' . _('Transaction Date') . '</th>'
        . '<th>' . _('Reference') . '</th>'
        . '<th>' . _('Status') . '</th>'
        . '<th>' . _('Actions') . '</th>'
        . '</tr></thead><tbody>';
    foreach ($transactions as $t) {
        echo '<tr>'
            . '<td>' . htmlspecialchars($t['fields']['transaction_number'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($t['fields']['transaction_type'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($t['fields']['amount'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($t['fields']['transaction_date'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($t['fields']['reference'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($t['fields']['status'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td><a href="' . htmlspecialchars($t['url'], ENT_QUOTES, 'UTF-8') . '" target="_blank">' . _('View') . '</a></td>'
            . '</tr>';
    }
    echo '</tbody></table>';
    end_page();
    exit;
}
display_error(_('Unknown action'));
exit;
