<?php
class WROS_Admin {
	public function init() : void {
		add_filter( 'woocommerce_get_settings_pages', function( $pages ) {
			$pages[] = new WROS_Admin_Page();
			return $pages;
		} );
	}
}

class WROS_Admin_Page extends WC_Settings_Page {
	public function __construct() {
		$this->id = 'wros';
		$this->label = __( 'Role Order Status', 'wros' );
		parent::__construct();
	}

	public function get_settings() {
		$roles = wp_roles()->roles; // All roles.
		$gateways = WC()->payment_gateways() ? WC()->payment_gateways()->payment_gateways() : [];
		$statuses = wc_get_order_statuses(); // [ 'wc-pending' => 'Pending payment', ... ]

		$fields = [];

		$fields[] = [ 'title' => __( 'Role → Status', 'wros' ), 'type' => 'title', 'id' => 'wros_role_map_title' ];
		foreach ( array_keys( $roles ) as $role_key ) {
			$fields[] = [
				'id' => 'wros_role_map_' . $role_key,
				'name' => sprintf( __( 'Role: %s', 'wros' ), $role_key ),
				'type' => 'select',
				'options' => $statuses,
				'css' => 'min-width:250px',
				'desc_tip' => true,
				'desc' => __( 'Default status for this role when order is not paid.', 'wros' ),
			];
		}
		$fields[] = [ 'type' => 'sectionend', 'id' => 'wros_role_map_title' ];

		$fields[] = [ 'title' => __( 'Role × Gateway → Status (overrides)', 'wros' ), 'type' => 'title', 'id' => 'wros_overrides_title' ];
		foreach ( array_keys( $roles ) as $role_key ) {
			foreach ( $gateways as $gw_id => $gw ) {
				$fields[] = [
					'id' => 'wros_override_' . $role_key . '_' . $gw_id,
					'name' => sprintf( '%s × %s', $role_key, $gw->get_title() ),
					'type' => 'select',
					'options' => $statuses,
					'css' => 'min-width:250px',
				];
			}
		}
		$fields[] = [ 'type' => 'sectionend', 'id' => 'wros_overrides_title' ];

		$fields[] = [ 'title' => __( 'Fallback', 'wros' ), 'type' => 'title', 'id' => 'wros_fallback_title' ];
		$fields[] = [
			'id' => 'wros_default',
			'name' => __( 'Default status if no match', 'wros' ),
			'type' => 'select',
			'options' => $statuses,
			'css' => 'min-width:250px',
		];
		$fields[] = [ 'type' => 'sectionend', 'id' => 'wros_fallback_title' ];

		return apply_filters( 'wros_settings_fields', $fields );
	}

	public function output() {
		WC_Admin_Settings::output_fields( $this->get_settings() );
	}

	public function save() {
		$settings = $this->get_settings();
		WC_Admin_Settings::save_fields( $settings );
		// Normalize to single option array for fast runtime access.
		$this->normalize_settings();
	}

	private function normalize_settings() : void {
		$roles = array_keys( wp_roles()->roles );
		$gateways = WC()->payment_gateways() ? array_keys( WC()->payment_gateways()->payment_gateways() ) : [];
		$role_map = [];
		foreach ( $roles as $role ) {
			$val = get_option( 'wros_role_map_' . $role, '' );
			$role_map[ $role ] = $this->strip_prefix( $val );
		}
		$overrides = [];
		foreach ( $roles as $role ) {
			foreach ( $gateways as $gw ) {
				$key = 'wros_override_' . $role . '_' . $gw;
				$val = get_option( $key, '' );
				if ( $val ) {
					$overrides[ $role ][ $gw ] = $this->strip_prefix( $val );
				}
			}
		}
		$default = $this->strip_prefix( get_option( 'wros_default', '' ) );
		update_option(
			'wros_settings',
			[
				'role_map' => $role_map,
				'overrides' => $overrides,
				'default' => $default,
			]
		);
	}

	private function strip_prefix( string $status ) : string {
		return ltrim( $status, 'wc-' );
	}
}
