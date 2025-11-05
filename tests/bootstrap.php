<?php
/**
 * Test bootstrap for WooCommerce Role Order Status plugin.
 */

declare(strict_types=1);

if ( ! class_exists( 'WP_User' ) ) {
        class WP_User {
                /**
                 * @var array<string>
                 */
                public $roles;

                /**
                 * @param array<string> $roles Roles assigned to the user.
                 */
                public function __construct( array $roles = [] ) {
                        $this->roles = $roles;
                }
        }
}

if ( ! class_exists( 'WC_Order' ) ) {
        class WC_Order {
                /**
                 * @var string
                 */
                private $status;

                /**
                 * @var int
                 */
                private $user_id;

                /**
                 * @var string
                 */
                private $gateway;

                /**
                 * @var string|null
                 */
                public $updated_to = null;

                /**
                 * @var array<int, string>
                 */
                public $update_notes = [];

                public function __construct( string $status, int $user_id, string $gateway ) {
                        $this->status  = $status;
                        $this->user_id = $user_id;
                        $this->gateway = $gateway;
                }

                public function get_status() : string {
                        return $this->status;
                }

                public function get_user_id() : int {
                        return $this->user_id;
                }

                public function get_payment_method() : string {
                        return $this->gateway;
                }

                public function update_status( string $new_status, string $note = '' ) : void {
                        $this->status       = $new_status;
                        $this->updated_to   = $new_status;
                        $this->update_notes[] = $note;
                }
        }
}

if ( ! function_exists( 'get_userdata' ) ) {
        /** @return WP_User|false */
        function get_userdata( int $user_id ) {
                global $wros_test_userdata;

                return $wros_test_userdata[ $user_id ] ?? false;
        }
}

if ( ! function_exists( 'sanitize_text_field' ) ) {
        function sanitize_text_field( $value ) {
                return is_string( $value ) ? $value : '';
        }
}

if ( ! function_exists( 'apply_filters' ) ) {
        function apply_filters( $hook_name, $value, ...$args ) {
                return $value;
        }
}

if ( ! function_exists( 'get_option' ) ) {
        function get_option( $option, $default = false ) {
                global $wros_test_settings;

                if ( 'wros_settings' === $option ) {
                        return $wros_test_settings;
                }

                return $default;
        }
}

require_once __DIR__ . '/../woocommerce-role-order-status/includes/class-wros-core.php';
