# WooCommerce Role-based Order Status (WROS)

Set WooCommerce order status based on the customer role, with optional overrides by payment method. Useful to send B2B invoice orders to `pending` and retail invoice orders to `on-hold`, while keeping card/PayPal flows unchanged.

## Features
- Role → Status mapping.
- Optional Role × Payment Method → Status overrides.
- Safe guardrails: never downgrade from `processing/completed`.
- Admin UI with WordPress Settings API.
- Logs (WP debug log) and filters to extend.

## Requirements
- PHP >= 7.4
- WordPress >= 6.0
- WooCommerce >= 7.0

## Install
1. Copy the plugin folder to `wp-content/plugins/woocommerce-role-order-status`.
2. Activate in WP Admin.
3. Go to **WooCommerce → Settings → Role Order Status** and configure mappings.

## Notes
- Only applies when the order does not already have a paid status.
- You can target specific gateways (e.g., invoice) with overrides.

## Filters
- `wros_effective_status` — filter the decided status before applying.
- `wros_should_apply` — return false to skip for a given order.

## Dev
- `composer install`
- Coding standards via `phpcs` and `phpstan`.
