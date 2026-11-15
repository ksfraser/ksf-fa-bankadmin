# Business Requirements — ksf_FA_BankAdmin

**Package:** `ksfraser/ksf-fa-bankadmin`
**Version:** `1.0.0`
**Namespace:** `Ksfraser\FrontAccounting\BankAdmin\*`
**Module sequence:** BA (Bank Admin)

---

## BR-BA-001 — Bank account management

**Status:** IMPLEMENTED

The system SHALL provide `ksf_FA_BankAdmin` as the owning module for bank account management.

1. Bank accounts SHALL have account number, account name, account type, balance, currency, and active status.
2. Account types SHALL reference `0_ksf_code_master` with `code_type = 'bank_account_type'`.
3. Bank accounts SHALL be listable, creatable, editable, and deactivatable.

---

## BR-BA-002 — Bank transaction management

**Status:** IMPLEMENTED

Bank transactions SHALL be associated with bank accounts and have transaction types, amounts, dates, references, and statuses.

1. Transaction types SHALL reference `0_ksf_code_master` with `code_type = 'bank_transaction_type'`.
2. Transaction statuses: pending, cleared, reconciled, voided.
3. Transactions SHALL be listable, creatable, and editable.

---

## BR-BA-003 — Resolved DTOs for RelatedTo

**Status:** IMPLEMENTED

Bank Admin SHALL build resolved display data for RelatedTo display for both `bank_account` and `bank_transaction` types.

1. Resolved data SHALL wrap a `RelatedRecordDto` with resolved fields, overridable labels, and a URL.
2. `fieldMap()` SHALL return the field name => display label mapping.
3. `listAll()` SHALL return resolved data for summary table display.

