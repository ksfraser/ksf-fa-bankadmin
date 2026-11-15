<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\BankAdmin\Service;
use Ksfraser\FrontAccounting\BankAdmin\DTO\BankAccountDTO;
use Ksfraser\FrontAccounting\BankAdmin\Repository\BankAccountRepository;
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
final class BankAccountService {
    private $repo;
    public function __construct(?BankAccountRepository $repo = null) { $this->repo = $repo ?? new BankAccountRepository(); }
    public function resolveById(int $id, ?RelatedRecordDto $dto = null): array {
        $row = $this->repo->findById($id);
        if ($row === null) {
            return array('dto' => new RelatedRecordDto('bank_account', '0', 'bank_account', '0', null), 'fields' => $this->fieldMap(), 'labels' => $this->labelMap(), 'url' => '#', 'label' => _('Bank Account #') . (string) $id);
        }
        $a = BankAccountDTO::fromArray($row);
        $dto = $dto ?? new RelatedRecordDto('bank_account', (string) $a->id, 'bank_account', (string) $a->id, null);
        return array(
            'dto' => $dto,
            'fields' => array(
                'account_number' => $a->account_number,
                'account_name' => $a->account_name,
                'account_type' => $a->account_type,
                'balance' => $a->balance,
                'currency' => $a->currency,
            ),
            'labels' => $this->labelMap(),
            'url' => '/modules/ksf_FA_BankAdmin/pages/bank_account_form.php?action=edit&account_id=' . $a->id,
            'label' => $a->getDisplayLabel(),
        );
    }
    public function listAll(): array {
        $rows = $this->repo->findAll();
        $results = array();
        foreach ($rows as $row) {
            $a = BankAccountDTO::fromArray($row);
            $results[] = array(
                'id' => $a->id,
                'dto' => new RelatedRecordDto('bank_account', (string) $a->id, 'bank_account', (string) $a->id, null),
                'fields' => array(
                    'account_number' => $a->account_number,
                    'account_name' => $a->account_name,
                    'account_type' => $a->account_type,
                    'balance' => $a->balance,
                    'currency' => $a->currency,
                ),
                'labels' => $this->labelMap(),
                'url' => '/modules/ksf_FA_BankAdmin/pages/bank_account_form.php?action=edit&account_id=' . $a->id,
                'label' => $a->getDisplayLabel(),
            );
        }
        return $results;
    }
    public function fieldMap(): array { return $this->labelMap(); }
    public function labelMap(): array {
        return array(
            'account_number' => _('Account Number'),
            'account_name' => _('Account Name'),
            'account_type' => _('Account Type'),
            'balance' => _('Balance'),
            'currency' => _('Currency'),
        );
    }
}
