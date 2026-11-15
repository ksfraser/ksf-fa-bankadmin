<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\BankAdmin\Repository;
final class BankTransactionRepository {
    private string $table = 'ksf_bank_transactions';
    public function findAll(): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " ORDER BY transaction_date DESC, id DESC";
        $result = db_query($sql, 'Could not load bank transactions');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function findById(int $id): ?array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return null; }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " WHERE id = " . (int) $id;
        $result = db_query($sql, 'Could not load bank transaction');
        if ($result === false) { return null; }
        $row = db_fetch_assoc($result);
        return $row === false ? null : $row;
    }
    public function save(array $data): int {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return 0; }
        $sql = "INSERT INTO " . TB_PREF . $this->table . " (
                account_id, transaction_number, transaction_type, amount, transaction_date, reference, status)
            VALUES ("
            . $this->escape($data['account_id'] ?? '') . ", "
            . $this->escape($data['transaction_number'] ?? '') . ", "
            . $this->escape($data['transaction_type'] ?? '') . ", "
            . $this->escape($data['amount'] ?? '0.00') . ", "
            . $this->escape($data['transaction_date'] ?? '') . ", "
            . $this->escape($data['reference'] ?? '') . ", "
            . $this->escape($data['status'] ?? 'pending') . ")";
        db_query($sql, 'Could not save bank transaction');
        return db_insert_id();
    }
    public function update(int $id, array $data): void {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return; }
        $sets = [];
        foreach (['account_id', 'transaction_number', 'transaction_type', 'amount', 'transaction_date', 'reference', 'status'] as $col) {
            if (array_key_exists($col, $data)) { $sets[] = "$col = " . $this->escape($data[$col]); }
        }
        if (empty($sets)) { return; }
        $sql = "UPDATE " . TB_PREF . $this->table . " SET " . implode(', ', $sets) . " WHERE id = " . (int) $id;
        db_query($sql, 'Could not update bank transaction');
    }
    private function escape($value): string {
        return function_exists('db_escape') ? db_escape($value) : addslashes((string) $value);
    }
    private function intVal($value): int {
        return (int) $value;
    }
}
