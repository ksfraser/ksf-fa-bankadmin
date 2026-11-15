<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\BankAdmin\DTO;
final class BankAccountDTO {
    public $id;
    public $account_number;
    public $account_name;
    public $account_type;
    public $balance;
    public $currency;
    public $is_active;
    public $created_at;
    public function __construct(
        int $id,
        string $account_number = '',
        string $account_name = '',
        string $account_type = '',
        string $balance = '0.00',
        string $currency = 'USD',
        bool $is_active = true,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->account_number = $account_number;
        $this->account_name = $account_name;
        $this->account_type = $account_type;
        $this->balance = $balance;
        $this->currency = $currency;
        $this->is_active = $is_active;
        $this->created_at = $created_at;
    }
    public function getDisplayLabel(): string {
        $parts = array();
        if ($this->account_name !== '') { $parts[] = $this->account_name; }
        if ($this->account_number !== '') { $parts[] = $this->account_number; }
        return $parts === [] ? _('Bank Account #') . (string) $this->id : implode(' — ', $parts);
    }
    public function toArray(): array {
        return array(
            'id' => $this->id,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'account_type' => $this->account_type,
            'balance' => $this->balance,
            'currency' => $this->currency,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
        );
    }
    public static function fromArray(array $data): self {
        return new self(
            (int) ($data['id'] ?? 0),
            (string) ($data['account_number'] ?? ''),
            (string) ($data['account_name'] ?? ''),
            (string) ($data['account_type'] ?? ''),
            (string) ($data['balance'] ?? '0.00'),
            (string) ($data['currency'] ?? 'USD'),
            !empty($data['is_active']),
            (string) ($data['created_at'] ?? '')
        );
    }
}
