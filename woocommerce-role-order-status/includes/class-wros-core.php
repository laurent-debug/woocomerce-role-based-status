<?php
class WROS_Core {
	public function init() : void {
		// Apply right after WC creates the order and sets initial status.
		add_action( 'woocommerce_checkout_order_processed', [ $this, 'maybe_set_status' ], 50, 3 );
		// Safety: also handle programmatic order creation.
		add_action( 'woocommerce_new_order', [ $this, 'maybe_set_status_by_id' ], 50, 1 );
	}

	public function maybe_set_status_by_id( $order_id ) : void {
		$order = wc_get_order( $order_id );
		if ( $order ) {
			$this->maybe_apply( $order );
		}
	}

	public function maybe_set_status( $order_id, $posted_data, $order ) : void {
		if ( $order instanceof WC_Order ) {
			$this->maybe_apply( $order );
		}
	}

	private function maybe_apply( WC_Order $order ) : void {
		// Skip if already paid or in final states.
		if ( in_array( $order->get_status(), [ 'processing', 'completed', 'refunded', 'cancelled', 'failed' ], true ) ) {
			return;
		}

                $user_id = (int) $order->get_user_id();
                $roles = [];
                if ( $user_id ) {
                        $user = get_userdata( $user_id );
                        if ( $user instanceof WP_User ) {
                                $roles = (array) $user->roles;
                        }
                }
		$gateway = $order->get_payment_method();

		$settings = $this->get_settings();

		$target = $this->decide_status( $roles, $gateway, $settings );
		$target = apply_filters( 'wros_effective_status', $target, $order, $roles, $gateway, $settings );

		$should = apply_filters( 'wros_should_apply', true, $order, $roles, $gateway, $settings );
		if ( ! $should || ! $target ) {
			return;
		}

		$current = $order->get_status();
		if ( $current === $target ) {
			return;
		}

		// Never downgrade from paid states; we already returned earlier if paid states.
		$order->update_status( $target, sprintf( 'WROS applied status based on role(s): %s and gateway: %s', implode( ',', $roles ), $gateway ) );
	}

	private function decide_status( array $roles, string $gateway, array $settings ) : ?string {
		$role_map = $settings['role_map'] ?? [];
		$overrides = $settings['overrides'] ?? [];
		$default = $settings['default'] ?? '';

		// Check overrides Role × Gateway first.
		foreach ( $roles as $role ) {
			if ( isset( $overrides[ $role ][ $gateway ] ) && $overrides[ $role ][ $gateway ] ) {
				return sanitize_text_field( $overrides[ $role ][ $gateway ] );
			}
		}

		// Then plain Role → Status.
		foreach ( $roles as $role ) {
			if ( isset( $role_map[ $role ] ) && $role_map[ $role ] ) {
				return sanitize_text_field( $role_map[ $role ] );
			}
		}

		return $default ?: null;
	}

	private function get_settings() : array {
		$raw = get_option( 'wros_settings', [] );
		return is_array( $raw ) ? $raw : [];
	}
}
