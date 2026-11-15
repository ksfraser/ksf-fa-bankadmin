# RTM — ksf_FA_BankAdmin

| BR ID | Requirement | Implementing class/method | Test file | Status |
|-------|-------------|---------------------------|-----------|--------|
| BR-BA-001 | Bank account management | BankAccountService::resolveById, listAll; BankAccountRepository::findAll, findById, save, update, delete | BankAccountDtoTest | Implemented |
| BR-BA-002 | Bank transaction management | BankTransactionService::resolveById, listAll; BankTransactionRepository::findAll, findById, save, update | BankTransactionDtoTest | Implemented |
| BR-BA-003 | Resolved DTOs for RelatedTo | BankAccountService, BankTransactionService (toArray, fieldMap, labelMap) | BankAccountDtoTest, BankTransactionDtoTest | Implemented |

