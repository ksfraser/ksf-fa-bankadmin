<?php
declare(strict_types=1);
use Ksfraser\FrontAccounting\BankAdmin\Service\BankAccountService;
use ksfraser\RelatedTo\DTO\RelatedQueryDto;
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
use ksfraser\RelatedTo\UI\DefaultRelatedRecordsRenderer;
$path_to_root = '../../..';
include_once($path_to_root . '/includes/session.inc');
add_access_extensions();
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) { display_error(_('Bank Admin module dependencies are not installed')); exit; }
require_once $autoload;
$service = new BankAccountService();
$action = isset($_GET['action']) ? (string) $_GET['action'] : 'list';
if ($action === 'list') {
    page(_('Bank Accounts'));
    $accounts = $service->listAll();
    echo '<p>' . _('Bank Accounts') . '</p>';
    echo '<table class="selection">';
    echo '<thead><tr>'
        . '<th>' . _('Account Number') . '</th>'
        . '<th>' . _('Account Name') . '</th>'
        . '<th>' . _('Account Type') . '</th>'
        . '<th>' . _('Balance') . '</th>'
        . '<th>' . _('Currency') . '</th>'
        . '<th>' . _('Actions') . '</th>'
        . '</tr></thead><tbody>';
    foreach ($accounts as $a) {
        echo '<tr>'
            . '<td>' . htmlspecialchars($a['fields']['account_number'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($a['fields']['account_name'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($a['fields']['account_type'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($a['fields']['balance'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($a['fields']['currency'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td><a href="' . htmlspecialchars($a['url'], ENT_QUOTES, 'UTF-8') . '" target="_blank">' . _('View') . '</a></td>'
            . '</tr>';
    }
    echo '</tbody></table>';
    $lhsId = isset($_GET['id']) ? (string) $_GET['id'] : '';
    if (function_exists('hook_invoke') && class_exists(DefaultRelatedRecordsRenderer::class) && $lhsId !== '') {
        $queryDto = new RelatedQueryDto('bank_account', $lhsId);
        $queryResult = $queryDto;
        hook_invoke('ksf_FA_RelatedTo', 'RELATED_TO', $queryResult, null);
        if (is_array($queryResult) && isset($queryResult['related']) && is_array($queryResult['related'])) {
            $renderer = new DefaultRelatedRecordsRenderer(array(
                'label_resolver' => function ($type, $id) { return (string) $id; },
                'url_resolver' => function ($type, $id) { return '#'; },
                'type_labels' => array('bank_account' => _('Bank Accounts')),
            ));
            echo $renderer->renderRelatedRecords('bank_account', $lhsId, $queryResult['related'], array(
                'css_class' => 'related-records',
                'header_class' => 'related-header',
                'table_class' => 'related-table',
                'table_border' => true,
            ));
        }
    }
    end_page();
    exit;
}
display_error(_('Unknown action'));
exit;
