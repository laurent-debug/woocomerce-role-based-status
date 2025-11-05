<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/bootstrap.php';

final class WROSCoreTest extends TestCase {
        protected function setUp() : void {
                global $wros_test_userdata, $wros_test_settings;

                $wros_test_userdata = [];
                $wros_test_settings = [];
        }

        public function test_it_applies_default_status_when_user_missing() : void {
                global $wros_test_settings;

                $wros_test_settings = [
                        'default' => 'on-hold',
                ];

                $order = new WC_Order( 'pending', 42, 'cod' );

                $this->invoke_maybe_apply( $order );

                $this->assertSame( 'on-hold', $order->get_status() );
                $this->assertSame( 'on-hold', $order->updated_to );
        }

        public function test_it_uses_role_mapping_when_user_exists() : void {
                global $wros_test_userdata, $wros_test_settings;

                $wros_test_userdata[ 7 ] = new WP_User( [ 'wholesale_customer' ] );
                $wros_test_settings     = [
                        'role_map' => [
                                'wholesale_customer' => 'pending',
                        ],
                ];

                $order = new WC_Order( 'on-hold', 7, 'stripe' );

                $this->invoke_maybe_apply( $order );

                $this->assertSame( 'pending', $order->get_status() );
                $this->assertSame( 'pending', $order->updated_to );
        }

        private function invoke_maybe_apply( WC_Order $order ) : void {
                $core   = new WROS_Core();
                $method = new ReflectionMethod( WROS_Core::class, 'maybe_apply' );
                $method->setAccessible( true );
                $method->invoke( $core, $order );
        }
}
