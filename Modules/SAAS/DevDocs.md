# Developer Documentation

## Tenant Level Commands

- `php artisan tenants:list`
- `php artisan tenants:migrate`
- `php artisan tenants:run {command}`

## Landlord Level Commands

- `php artisan saas:rp-sync`
- `php artisan saas:backup`


## Important code snippet

- Put a tenant into maintenance mode: `$tenant->putDownForMaintenance();`
- Active from maimntenance mode: `$tenant->update(['maintenance_mode' => null]);`
