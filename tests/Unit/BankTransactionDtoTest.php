<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\BankAdmin\Tests\Unit;
use Ksfraser\FrontAccounting\BankAdmin\DTO\BankTransactionDTO;
use PHPUnit\Framework\TestCase;
class BankTransactionDtoTest extends TestCase {
    public function testGetDisplayLabel(): void {
        $dto = new BankTransactionDTO(7, 'A1', '1234567890', 'TXN-001', 'deposit', '1000.00', '2026-10-01', 'INV-001', 'cleared', '');
        $this->assertSame('TXN-001 — deposit — 1000.00', $dto->getDisplayLabel());
    }
    public function testGetDisplayLabelMinimal(): void {
        $dto = new BankTransactionDTO(3, '', '', '', '', '', '', '', '', '');
        $this->assertSame('Transaction #3', $dto->getDisplayLabel());
    }
    public function testToArrayShape(): void {
        $dto = new BankTransactionDTO(7, 'A1', '1234567890', 'TXN-001', 'deposit', '1000.00', '2026-10-01', 'INV-001', 'cleared', '');
        $array = $dto->toArray();
        $this->assertSame(7, $array['id']);
        $this->assertSame('A1', $array['account_id']);
        $this->assertSame('TXN-001', $array['transaction_number']);
        $this->assertSame('deposit', $array['transaction_type']);
    }
    public function testFromArrayReconstructs(): void {
        $dto = BankTransactionDTO::fromArray(array(
            'id' => 9, 'account_id' => 'A2', 'account_number' => '0987654321',
            'transaction_number' => 'TXN-002', 'transaction_type' => 'withdrawal',
            'amount' => '500.00', 'transaction_date' => '2026-10-02', 'reference' => 'INV-002',
            'status' => 'pending', 'created_at' => ''
        ));
        $this->assertSame(9, $dto->id);
        $this->assertSame('A2', $dto->account_id);
        $this->assertSame('TXN-002', $dto->transaction_number);
        $this->assertSame('withdrawal', $dto->transaction_type);
        $this->assertSame('500.00', $dto->amount);
        $this->assertSame('pending', $dto->status);
    }
}
