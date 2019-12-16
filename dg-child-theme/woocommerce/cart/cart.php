<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;
wp_enqueue_script('thegem-woocommerce');

wc_print_notices();

$thegem_checkout_type = thegem_get_option('checkout_type', 'multi-step');

?>

<?php if ($thegem_checkout_type == 'multi-step'): ?>
	<div class="checkout-steps <?php if(is_user_logged_in()): ?>user-logged<?php endif; ?> clearfix">
		<?php if(is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' )): ?>
			<div class="checkout-step active" data-tab-id="checkout-billing"><?php esc_html_e('1. Billing','thegem'); ?></div>
			<div class="checkout-step" data-tab-id="checkout-payment"><?php esc_html_e('2. Payment','thegem'); ?></div>
			<div class="checkout-step disabled" data-tab-id="checkout-confirmation"><?php esc_html_e('3. Confirmation','thegem'); ?></div>
		<?php else: ?>
			<div class="checkout-step active" data-tab-id="checkout-signin"><?php esc_html_e('1. Sign in','thegem'); ?></div>
			<div class="checkout-step" data-tab-id="checkout-billing"><?php esc_html_e('2. Billing','thegem'); ?></div>
			<div class="checkout-step" data-tab-id="checkout-payment"><?php esc_html_e('3. Payment','thegem'); ?></div>
			<div class="checkout-step disabled" data-tab-id="checkout-confirmation"><?php esc_html_e('4. Confirmation','thegem'); ?></div>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if ($thegem_checkout_type == 'one-page'): ?>
	<div class="checkout-steps clearfix woocommerce-steps-<?php echo $thegem_checkout_type; ?>">
		<div class="checkout-step disabled active"><?php esc_html_e('Shopping cart','thegem'); ?></div>
		<div class="checkout-step disabled"><?php esc_html_e('Checkout details','thegem'); ?></div>
		<div class="checkout-step disabled"><?php esc_html_e('Order complete','thegem'); ?></div>
	</div>
<?php endif; ?>

<div class="woocommerce-before-cart clearfix"><?php do_action( 'woocommerce_before_cart' ); ?></div>

<div class="row cart-section">
	<div class="col-md-6">
		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post" style="display: block;">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<div class="gem-table"><table class="shop_table cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>
					<th class="product-remove">&nbsp;</th>
					<th class="product-name" colspan="2"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
					<th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
					<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
					<th class="product-subtotal"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
							<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

							<td class="product-remove">
								<?php
										// @codingStandardsIgnoreLine
										echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
											'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&#xe619;</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											__( 'Remove this item', 'woocommerce' ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() )
										), $cart_item_key );
								?>
							</td>

							<td class="product-thumbnail">
								<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

										if ( ! $product_permalink ) {
									echo wp_kses_post( $thumbnail );
									} else {
									printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
									}
								?>
							</td>

							<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
								<div class="product-title"><?php
									if ( ! $product_permalink ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
									} else {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
									}
								?></div>
								<div class="product-meta"><?php

								// Meta data.
								echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

								// Backorder notification.
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>' ) );
									}
								?></div>
							</td>

								<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
							</td>

								<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
								<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input( array(
											'input_name'  => "cart[{$cart_item_key}][qty]",
											'input_value' => $cart_item['quantity'],
											'max_value'   => $_product->get_max_purchase_quantity(),
											'min_value'   => '0',
											'product_name'  => $_product->get_name(),
										), $_product, false );
									}

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
								?>
							</td>

								<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
							</td>
						</tr>
						<?php
					}
				}
				?>

				<?php do_action( 'woocommerce_cart_contents' ); ?>

				<tr>
					<td colspan="6" class="actions">
						<?php if ( wc_coupons_enabled() ) { ?>
							<div class="coupon col-md-6">
								<a id="toggle-coupon-anchor" href="#disabled" onclick="toggleCoupon()">Got A Coupon?</a>
							</div>
						<?php } ?>

						<div class="submit-buttons col-md-6">
							<?php
								thegem_button(array(
									'tag' => 'button',
									'text' => esc_html__( 'Update cart', 'woocommerce' ),
									'size' => 'small',
									'extra_class' => 'update-cart grey',
									'attributes' => array(
										'name' => 'update_cart',
										'value' => esc_attr__( 'Update cart', 'woocommerce' ),
										'type' => 'submit',
										'class' => 'grey',
									)
								), true);
							?>

							<?php do_action( 'woocommerce_cart_actions' ); ?>
						</div>
						<?php if ( wc_coupons_enabled() ) { ?>
						<!-- Coupon Inputs -->
						<div class="coupon-inputs col-md-12" style="display:none;">
							<input type="text" name="coupon_code" class="input-text coupon-code small" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
							<?php
								thegem_button(array(
									'tag' => 'button',
									'text' => esc_html__( 'Apply coupon', 'woocommerce' ),
									'size' => 'small',
									'attributes' => array(
										'name' => 'apply_coupon',
										'value' => esc_attr__( 'Apply coupon', 'woocommerce' ),
										'type' => 'submit',
									)
								), true);
							?>
						</div>
						<?php do_action('woocommerce_cart_coupon'); ?>

						<?php } ?>

						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					</td>
				</tr>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</tbody>
		</table></div>

		<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</form>
	</div>

	<!-- REsponsive cart -->
<!-- 	<form class="woocommerce-cart-form responsive col-md-12" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<?php
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
			?>

			<div class="cart-item cart_item rounded-corners shadow-box">
				<table class="shop_table cart"><tbody><tr>
					<td class="product-thumbnail">
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $product_permalink )
								echo wp_kses_post( $thumbnail );
							else
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
						?>
					</td>

					<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
						<div class="product-title"><?php
							if ( ! $product_permalink ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
								} else {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
							}
							?></div>
						<div class="product-meta"><?php
							// Meta data
							echo wc_get_formatted_cart_item_data( $cart_item );

							// Backorder notification.
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>' ) );
								}
						?></div>
					</td>

					<td class="product-remove">
						<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&#xe619;</a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								__( 'Remove this item', 'woocommerce' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() )
							), $cart_item_key );
						?>
					</td>
				</tr></tbody></table>
				<div class="gem-table"><table class="shop_table cart">
					<thead>
						<tr>
							<th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
							<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
							<th class="product-subtotal"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="product-price">
								<?php
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
								?>
							</td>

							<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
								<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input( array(
											'input_name'  => "cart[{$cart_item_key}][qty]",
											'input_value' => $cart_item['quantity'],
											'max_value'   => $_product->get_max_purchase_quantity(),
											'min_value'   => '0',
											'product_name'  => $_product->get_name(),
										), $_product, false );
									}

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
								?>
							</td>

							<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
								?>
							</td>
						</tr>
					</tbody>
				</table></div>
			</div>
			<?php
		}
	}

	?>
	<div class="actions">
		<?php if ( wc_coupons_enabled() ) { ?>
			<div class="coupon col-md-12">
				<a id="toggle-coupon-anchor" href="#disabled" onclick="toggleCoupon()">Got A Coupon?</a>
				<div class="coupon-inputs" style="display:none;">
					<input type="text" name="coupon_code" class="input-text coupon-code" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
					<?php
						thegem_button(array(
							'tag' => 'button',
							'text' => esc_html__( 'Apply coupon', 'woocommerce' ),
							'style' => 'outline',
							'size' => 'small',
							'attributes' => array(
								'name' => 'apply_coupon',
								'value' => esc_attr__( 'Apply coupon', 'woocommerce' ),
								'type' => 'submit',
							)
						), true);
					?>
				</div>

				<?php do_action('woocommerce_cart_coupon'); ?>
			</div>
		<?php } ?>

		<div class="submit-buttons centered-box">
			<?php
				thegem_button(array(
					'tag' => 'button',
					'text' => esc_html__( 'Update cart', 'woocommerce' ),
					'size' => 'medium',
					'extra_class' => 'update-cart grey',
					'attributes' => array(
						'name' => 'update_cart',
						'value' => esc_attr__( 'Update cart', 'woocommerce' ),
						'type' => 'submit',
					)
				), true);
			?>
			<?php
				thegem_button(array(
					'tag' => 'a',
					'href' => esc_url( wc_get_checkout_url() ),
					'text' => esc_html__( 'Checkout', 'woocommerce' ),
					'size' => 'medium',
					'extra_class' => 'checkout-button-button',
					'attributes' => array(
						'class' => 'checkout-button button alt wc-forward'
					)
				), true);
			?>

			<?php do_action( 'woocommerce_cart_actions' ); ?>
		</div>
		<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

	</div>

	<?php do_action( 'woocommerce_after_cart_table' ); ?>

	</form> -->

	<div class="col-md-6">
		<div class="cart-collaterals">
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<?php woocommerce_shipping_calculator(); ?>
				</div>
				<div class="col-md-12 col-sm-12">
					<?php woocommerce_cart_totals(); ?>
				</div>
				<div class="col-md-12 col-sm-12">
				<?php
					thegem_button(array(
						'tag' => 'a',
						'href' => esc_url( wc_get_checkout_url() ),
						'text' => esc_html__( 'Checkout', 'woocommerce' ),
						'size' => 'medium',
						'extra_class' => 'checkout-button-button',
						'attributes' => array(
							'class' => 'checkout-button button alt wc-forward'
						)
					), true);
				?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php woocommerce_cross_sell_display(6, 6); ?>

<?php do_action( 'woocommerce_after_cart' ); ?>

<script type="text/javascript">
	function toggleCoupon() {
		jQuery('.coupon-inputs').toggle();
	}
</script>