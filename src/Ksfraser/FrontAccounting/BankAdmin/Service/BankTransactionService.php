<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\BankAdmin\Service;
use Ksfraser\FrontAccounting\BankAdmin\DTO\BankTransactionDTO;
use Ksfraser\FrontAccounting\BankAdmin\Repository\BankTransactionRepository;
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
final class BankTransactionService {
    private $repo;
    public function __construct(?BankTransactionRepository $repo = null) { $this->repo = $repo ?? new BankTransactionRepository(); }
    public function resolveById(int $id, ?RelatedRecordDto $dto = null): array {
        $row = $this->repo->findById($id);
        if ($row === null) {
            return array('dto' => new RelatedRecordDto('bank_transaction', '0', 'bank_transaction', '0', null), 'fields' => $this->fieldMap(), 'labels' => $this->labelMap(), 'url' => '#', 'label' => _('Transaction #') . (string) $id);
        }
        $t = BankTransactionDTO::fromArray($row);
        $dto = $dto ?? new RelatedRecordDto('bank_transaction', (string) $t->id, 'bank_transaction', (string) $t->id, null);
        return array(
            'dto' => $dto,
            'fields' => array(
                'transaction_number' => $t->transaction_number,
                'transaction_type' => $t->transaction_type,
                'amount' => $t->amount,
                'transaction_date' => $t->transaction_date,
                'reference' => $t->reference,
                'status' => $t->status,
            ),
            'labels' => $this->labelMap(),
            'url' => '/modules/ksf_FA_BankAdmin/pages/bank_transaction_form.php?action=edit&transaction_id=' . $t->id,
            'label' => $t->getDisplayLabel(),
        );
    }
    public function listAll(): array {
        $rows = $this->repo->findAll();
        $results = array();
        foreach ($rows as $row) {
            $t = BankTransactionDTO::fromArray($row);
            $results[] = array(
                'id' => $t->id,
                'dto' => new RelatedRecordDto('bank_transaction', (string) $t->id, 'bank_transaction', (string) $t->id, null),
                'fields' => array(
                    'transaction_number' => $t->transaction_number,
                    'transaction_type' => $t->transaction_type,
                    'amount' => $t->amount,
                    'transaction_date' => $t->transaction_date,
                    'reference' => $t->reference,
                    'status' => $t->status,
                ),
                'labels' => $this->labelMap(),
                'url' => '/modules/ksf_FA_BankAdmin/pages/bank_transaction_form.php?action=edit&transaction_id=' . $t->id,
                'label' => $t->getDisplayLabel(),
            );
        }
        return $results;
    }
    public function fieldMap(): array { return $this->labelMap(); }
    public function labelMap(): array {
        return array(
            'transaction_number' => _('Transaction Number'),
            'transaction_type' => _('Transaction Type'),
            'amount' => _('Amount'),
            'transaction_date' => _('Transaction Date'),
            'reference' => _('Reference'),
            'status' => _('Status'),
        );
    }
}
