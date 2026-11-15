<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\BankAdmin\DTO;
final class BankTransactionDTO {
    public $id;
    public $account_id;
    public $account_number;
    public $transaction_number;
    public $transaction_type;
    public $amount;
    public $transaction_date;
    public $reference;
    public $status;
    public $created_at;
    public function __construct(
        int $id,
        string $account_id = '',
        string $account_number = '',
        string $transaction_number = '',
        string $transaction_type = '',
        string $amount = '0.00',
        string $transaction_date = '',
        string $reference = '',
        string $status = 'pending',
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->account_id = $account_id;
        $this->account_number = $account_number;
        $this->transaction_number = $transaction_number;
        $this->transaction_type = $transaction_type;
        $this->amount = $amount;
        $this->transaction_date = $transaction_date;
        $this->reference = $reference;
        $this->status = $status;
        $this->created_at = $created_at;
    }
    public function getDisplayLabel(): string {
        $parts = array();
        if ($this->transaction_number !== '') { $parts[] = $this->transaction_number; }
        if ($this->transaction_type !== '') { $parts[] = $this->transaction_type; }
        if ($this->amount !== '' && $this->amount !== '0.00') { $parts[] = $this->amount; }
        return $parts === [] ? _('Transaction #') . (string) $this->id : implode(' — ', $parts);
    }
    public function toArray(): array {
        return array(
            'id' => $this->id,
            'account_id' => $this->account_id,
            'account_number' => $this->account_number,
            'transaction_number' => $this->transaction_number,
            'transaction_type' => $this->transaction_type,
            'amount' => $this->amount,
            'transaction_date' => $this->transaction_date,
            'reference' => $this->reference,
            'status' => $this->status,
            'created_at' => $this->created_at,
        );
    }
    public static function fromArray(array $data): self {
        return new self(
            (int) ($data['id'] ?? 0),
            (string) ($data['account_id'] ?? ''),
            (string) ($data['account_number'] ?? ''),
            (string) ($data['transaction_number'] ?? ''),
            (string) ($data['transaction_type'] ?? ''),
            (string) ($data['amount'] ?? '0.00'),
            (string) ($data['transaction_date'] ?? ''),
            (string) ($data['reference'] ?? ''),
            (string) ($data['status'] ?? 'pending'),
            (string) ($data['created_at'] ?? '')
        );
    }
}
