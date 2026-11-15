<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\BankAdmin\Tests\Unit;
use Ksfraser\FrontAccounting\BankAdmin\DTO\BankAccountDTO;
use PHPUnit\Framework\TestCase;
class BankAccountDtoTest extends TestCase {
    public function testGetDisplayLabel(): void {
        $dto = new BankAccountDTO(7, '1234567890', 'Checking Account', 'checking', '5000.00', 'USD', true, '');
        $this->assertSame('Checking Account — 1234567890', $dto->getDisplayLabel());
    }
    public function testGetDisplayLabelMinimal(): void {
        $dto = new BankAccountDTO(3, '', '', '', '', '', true, '');
        $this->assertSame('Bank Account #3', $dto->getDisplayLabel());
    }
    public function testToArrayShape(): void {
        $dto = new BankAccountDTO(7, '1234567890', 'Checking Account', 'checking', '5000.00', 'USD', true, '');
        $array = $dto->toArray();
        $this->assertSame(7, $array['id']);
        $this->assertSame('1234567890', $array['account_number']);
        $this->assertSame('Checking Account', $array['account_name']);
        $this->assertSame('checking', $array['account_type']);
    }
    public function testFromArrayReconstructs(): void {
        $dto = BankAccountDTO::fromArray(array(
            'id' => 9, 'account_number' => '0987654321', 'account_name' => 'Savings Account',
            'account_type' => 'savings', 'balance' => '10000.00', 'currency' => 'USD',
            'is_active' => true, 'created_at' => ''
        ));
        $this->assertSame(9, $dto->id);
        $this->assertSame('0987654321', $dto->account_number);
        $this->assertSame('Savings Account', $dto->account_name);
        $this->assertSame('savings', $dto->account_type);
        $this->assertSame('10000.00', $dto->balance);
        $this->assertTrue($dto->is_active);
    }
}
