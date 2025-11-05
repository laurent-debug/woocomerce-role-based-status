<?php
class WROS_Helpers {
	public static function get_manual_gateways() : array {
		$manual = [];
		$gws = WC()->payment_gateways() ? WC()->payment_gateways()->payment_gateways() : [];
		foreach ( $gws as $id => $gw ) {
			if ( method_exists( $gw, 'is_available' ) && $gw->is_available() ) {
				// Heuristic: gateways that do not auto-capture payment often are these.
				if ( in_array( $id, [ 'bacs', 'cheque', 'cod', 'invoice', 'bexio_invoice', 'jimsoft_invoice' ], true ) ) {
					$manual[] = $id;
				}
			}
		}
		return $manual;
	}
}
