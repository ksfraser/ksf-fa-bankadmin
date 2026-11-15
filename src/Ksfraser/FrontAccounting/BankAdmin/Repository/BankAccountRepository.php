<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\BankAdmin\Repository;
final class BankAccountRepository {
    private string $table = 'ksf_bank_accounts';
    public function findAll(): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " ORDER BY account_number ASC";
        $result = db_query($sql, 'Could not load bank accounts');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function findById(int $id): ?array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return null; }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " WHERE id = " . (int) $id;
        $result = db_query($sql, 'Could not load bank account');
        if ($result === false) { return null; }
        $row = db_fetch_assoc($result);
        return $row === false ? null : $row;
    }
    public function findByAccountNumber(string $accountNumber): ?array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return null; }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " WHERE account_number = " . $this->escape($accountNumber) . " LIMIT 1";
        $result = db_query($sql, 'Could not load bank account by number');
        if ($result === false) { return null; }
        $row = db_fetch_assoc($result);
        return $row === false ? null : $row;
    }
    public function save(array $data): int {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return 0; }
        $sql = "INSERT INTO " . TB_PREF . $this->table . " (
                account_number, account_name, account_type, balance, currency, is_active)
            VALUES ("
            . $this->escape($data['account_number'] ?? '') . ", "
            . $this->escape($data['account_name'] ?? '') . ", "
            . $this->escape($data['account_type'] ?? '') . ", "
            . $this->escape($data['balance'] ?? '0.00') . ", "
            . $this->escape($data['currency'] ?? 'USD') . ", "
            . (isset($data['is_active']) ? (int)$data['is_active'] : 1) . ")";
        db_query($sql, 'Could not save bank account');
        return db_insert_id();
    }
    public function update(int $id, array $data): void {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return; }
        $sets = [];
        foreach (['account_number', 'account_name', 'account_type', 'balance', 'currency', 'is_active'] as $col) {
            if (array_key_exists($col, $data)) {
                $val = ($col === 'is_active') ? (int)$data[$col] : $this->escape($data[$col]);
                $sets[] = "$col = $val";
            }
        }
        if (empty($sets)) { return; }
        $sql = "UPDATE " . TB_PREF . $this->table . " SET " . implode(', ', $sets) . " WHERE id = " . (int) $id;
        db_query($sql, 'Could not update bank account');
    }
    public function delete(int $id): void {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return; }
        $sql = "DELETE FROM " . TB_PREF . $this->table . " WHERE id = " . (int) $id;
        db_query($sql, 'Could not delete bank account');
    }
    private function escape($value): string {
        return function_exists('db_escape') ? db_escape($value) : addslashes((string) $value);
    }
    private function intVal($value): int {
        return (int) $value;
    }
}
