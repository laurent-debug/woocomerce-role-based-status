# SPEC — Role-based Order Status

## User story
As a store owner I want orders from professional roles to land in `pending` when paying by invoice, while consumer roles create `on-hold` when paying by invoice. Card/PayPal should continue to auto-complete or go to processing as usual.

## Roles examples
- `wholesaler`, `shop_manager`, `customer` (core roles vary per site; UI must list all roles dynamically).

## Status universe
- Supported: `pending`, `processing`, `on-hold`, `completed`, `cancelled`, `refunded`, `failed`, plus any custom like `planzer-transmit`, `checkout-draft`.

## Rules
1. When an order is created and is not in a paid terminal state, compute target status from:
   - Overrides: Role × Gateway → Status.
   - Else Role → Status.
   - Else Default.
2. Never downgrade a paid status.
3. Allow filters to bypass or modify the target.

## Acceptance criteria
- Given user role `wholesaler` and gateway `invoice`, then order becomes `pending`.
- Given role `customer` and gateway `invoice`, then order becomes `on-hold`.
- Given role `wholesaler` and gateway `stripe`, no change from Stripe flow.
- Admin screen saves mappings and normalization strips `wc-` prefix.
