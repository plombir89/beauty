---
paths:
  - 'composer.*'
---

# General

## Audit Composer lock before install
For PHP dependency installs or updates, first update the lockfile without installing packages (`composer update --no-install` or `composer require vendor/package --no-install`), then run `composer audit --locked`. Only proceed with `composer install` and a final `composer audit` after the locked audit is clean.
