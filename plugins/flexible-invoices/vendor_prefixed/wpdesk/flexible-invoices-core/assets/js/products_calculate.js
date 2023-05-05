jQuery.noConflict();
( function ( $ ) {

	function parseFloatLocal( num ) {
		return parseFloat( num.replace( ",", "." ) );
	}

	function moneyMultiply( a, b ) {
		if ( a === 0 || b === 0 ) {
			return 0;
		}
		var log_10 = function ( c ) {
				return Math.log( c ) / Math.log( 10 );
			},
			ten_e = function ( d ) {
				return Math.pow( 10, d );
			},
			pow_10 = -Math.floor( Math.min( log_10( a ), log_10( b ) ) ) + 1;
		var mul = ( ( a * ten_e( pow_10 ) ) * ( b * ten_e( pow_10 ) ) ) / ten_e( pow_10 * 2 );

		if ( isNaN( mul ) || ! isFinite( mul ) ) {
			return 0;
		} else {
			return mul;
		}
	}

	function getVatRateFromField( field ) {
		return parseInt( field.val().split( '|' )[ 1 ], 10 );
	}

	function invoiceRefreshProductNetPriceSum( $productHandle ) {
		$( '[name=product\\[net_price_sum\\]\\[\\]]', $productHandle ).val(
			moneyMultiply(
				parseFloatLocal( $( '[name=product\\[net_price\\]\\[\\]]', $productHandle ).val() ),
				parseFloatLocal( $( '[name=product\\[quantity\\]\\[\\]]', $productHandle ).val() )
			).toFixed( 2 )
		);
		invoiceRefreshProductVatRate( $productHandle );
	}

	function invoiceRefreshProductVatRate( $productHandle ) {
		var vatType = getVatRateFromField( $( '[name=product\\[vat_type\\]\\[\\]]', $productHandle ) );

		$( '[name=product\\[vat_sum\\]\\[\\]]', $productHandle ).val(
			moneyMultiply(
				parseFloatLocal( $( '[name=product\\[net_price_sum\\]\\[\\]]', $productHandle ).val() ),
				( isNaN( vatType ) ? 0 : vatType ) / 100
			).toFixed( 2 )
		);
		invoiceRefreshProductTotal( $productHandle );
	}

	function invoiceRefreshProductTotal( $productHandle ) {
		var total = parseFloatLocal( $( '[name=product\\[vat_sum\\]\\[\\]]', $productHandle ).val() ) +
			parseFloatLocal( $( '[name=product\\[net_price_sum\\]\\[\\]]', $productHandle ).val() );
		$( '[name=product\\[total_price\\]\\[\\]]', $productHandle ).val(
			(
				( isNaN( total ) ? 0 : total ).toFixed( 2 )
			)
		);
		invoiceRefreshTotal();
	}

	function invoiceRefreshTotal() {
		var price = 0.0;
		$( '.product_row [name=product\\[total_price\\]\\[\\]]' ).each( function ( index, item ) {
			var val = parseFloatLocal( $( item ).val() );
			price += isNaN( val ) ? 0 : val;
		} );

		$( '[name=total_price]' ).val( price.toFixed( 2 ) );
	}

	$( 'body.post-type-inspire_invoice .products_metabox' )
		.on( 'click', '.remove_product', function ( e ) {
			e.preventDefault();

			$( this ).parents( '.product_row' ).remove();
			invoiceRefreshTotal();
		} )
		.on( 'click', '.add_product', function ( e ) {
			e.preventDefault();

			var $container = $( '.products_container' );
			let item_html = $( '#product_prototype' ).html();
			$container.append( item_html );
		} )
		.on( 'change', '.refresh_net_price_sum', function ( e ) {
			var productHandle = $( this ).parents( '.product_row' );
			invoiceRefreshProductNetPriceSum( productHandle );
		} )
		.on( 'change', '.refresh_product', function ( e ) {
			var productHandle = $( this ).parents( '.product_row' );
			var price = this.options[ this.selectedIndex ].dataset.price;

			productHandle[ 0 ].querySelector( "input[name='product[net_price][]" ).value = price;

			invoiceRefreshProductNetPriceSum( productHandle );
		} )
		.on( 'change', '.refresh_vat_sum', function ( e ) {
			var productHandle = $( this ).parents( '.product_row' );
			invoiceRefreshProductVatRate( productHandle );
		} )
		.on( 'change', '.refresh_total_price', function ( e ) {
			var productHandle = $( this ).parents( '.product_row' );
			invoiceRefreshProductTotal( productHandle );
		} )
		.on( 'change', '.refresh_total', function ( e ) {
			invoiceRefreshTotal();
		} );

} )( jQuery );
