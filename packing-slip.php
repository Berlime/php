<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'wpo_wcpdf_before_document', $this->get_type(), $this->order ); ?>

<table class="head container">
	<tr>
		<td class="header">
			<?php
				if ( $this->has_header_logo() ) {
					do_action( 'wpo_wcpdf_before_shop_logo', $this->get_type(), $this->order );
					$this->header_logo();
					do_action( 'wpo_wcpdf_after_shop_logo', $this->get_type(), $this->order );
				} else {
					$this->title();
				}
			?>
		</td>
		<td class="shop-info">
			<?php do_action( 'wpo_wcpdf_before_shop_name', $this->get_type(), $this->order ); ?>
			<div class="shop-name"><h3><?php $this->shop_name(); ?></h3></div>
			<?php do_action( 'wpo_wcpdf_after_shop_name', $this->get_type(), $this->order ); ?>
			<?php do_action( 'wpo_wcpdf_before_shop_address', $this->get_type(), $this->order ); ?>
			<div class="shop-address"><?php $this->shop_address(); ?></div>
			<?php do_action( 'wpo_wcpdf_after_shop_address', $this->get_type(), $this->order ); ?>
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_document_label', $this->get_type(), $this->order ); ?>

<!-- hook available: wpo_wcpdf_after_footer -->
<?php if ( $this->has_header_logo() ) : // Edit order number size below ?>
	<h5 class="document-type-label"><?php $this->title(); ?> #<?php $this->order_number(); ?></h5>
<?php endif; ?>

<?php do_action( 'wpo_wcpdf_after_document_label', $this->get_type(), $this->order ); ?>

<table class="order-data-addresses">
	<tr>
		<td class="address shipping-address">
			<?php do_action( 'wpo_wcpdf_before_shipping_address', $this->get_type(), $this->order ); ?>
			<p><?php $this->shipping_address(); ?></p>
			<?php do_action( 'wpo_wcpdf_after_shipping_address', $this->get_type(), $this->order ); ?>
			<?php if ( isset( $this->settings['display_email'] ) ) : ?>
				<div class="billing-email"><?php $this->billing_email(); ?></div>
			<?php endif; ?>
			<?php if ( isset( $this->settings['display_phone'] ) ) : ?>
				<div class="shipping-phone"><?php $this->shipping_phone( ! $this->show_billing_address() ); ?></div>
			<?php endif; ?>
		</td>
		<td class="address billing-address">
			<?php if ( $this->show_billing_address() ) : ?>
				<h3><?php $this->billing_address_title(); ?></h3>
				<?php do_action( 'wpo_wcpdf_before_billing_address', $this->get_type(), $this->order ); ?>
				<p><?php $this->billing_address(); ?></p>
				<?php do_action( 'wpo_wcpdf_after_billing_address', $this->get_type(), $this->order ); ?>
				<?php if ( isset( $this->settings['display_phone'] ) && ! empty( $this->get_billing_phone() ) ) : ?>
					<div class="billing-phone"><?php $this->billing_phone(); ?></div>
				<?php endif; ?>
			<?php endif; ?>
		</td>
		<td class="order-data">
			<table>
				<?php do_action( 'wpo_wcpdf_before_order_data', $this->get_type(), $this->order ); ?>
				<tr class="order-number">
					<th><?php $this->order_number_title(); ?></th>
					<td><?php $this->order_number(); ?></td>
				</tr>
				<tr class="order-date">
					<th><?php $this->order_date_title(); ?></th>
					<td><?php $this->order_date(); ?></td>
				</tr>
				<?php if ( ! empty( $this->get_shipping_method() ) ) : ?>
					<tr class="shipping-method">
						<th><?php $this->shipping_method_title(); ?></th>
						<td><?php $this->shipping_method(); ?></td>
					</tr>
				<?php endif; ?>
				<?php do_action( 'wpo_wcpdf_after_order_data', $this->get_type(), $this->order ); ?>
			</table>			
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_order_details', $this->get_type(), $this->order ); ?>

<table class="order-details">
	<?php $headers = wpo_wcpdf_templates_get_table_headers( $this ); ?>
	<thead>
		<tr>
			<?php 
				foreach ( (array) $headers as $column_key => $header_data ) {
					printf( '<th class="%s"><span>%s</span></th>', $header_data['class'], $header_data['title'] );
				}
			?>
		</tr>
	</thead>
	<?php $body = wpo_wcpdf_templates_get_table_body( $this ); ?>
	<tbody>
		<?php
			foreach ( (array) $body as $item_id => $item_columns ) {
				do_action( 'wpo_wcpdf_templates_before_order_details_row', $this, $item_id, $item_columns );
				$row_class = apply_filters( 'wpo_wcpdf_item_row_class', 'item-'.$item_id, $this->get_type(), $this->order, $item_id );
				printf( '<tr class="%s">', $row_class );
				foreach ( $item_columns as $column_key => $column_data ) {
					printf( '<td class="%s"><span>%s</span></td>', $column_data['class'], $column_data['data'] );
				}
				echo '</tr>';
				do_action( 'wpo_wcpdf_templates_after_order_details_row', $this, $item_id, $item_columns );
			}
		?>
	</tbody>
</table>

<div class="bottom-spacer"></div>

<div class="notes-totals-container">
	<div class="totals">
		<?php $totals = wpo_wcpdf_templates_get_totals( $this ); ?>
		<table>
			<tbody>
				<?php foreach ( (array) $totals as $total_key => $total_data ) : ?>
					<tr class="<?php echo $total_data['class']; ?>">
						<th class="description"><span><?php echo $total_data['label']; ?></span></th>
						<td class="price"><span class="totals-price"><?php echo $total_data['value']; ?></span></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="notes">
		<div class="no-borders wrapper">
			<?php do_action( 'wpo_wcpdf_before_customer_notes', $this->get_type(), $this->order ); ?>
			<div class="customer-notes">
				<?php if ( $this->get_shipping_notes() ) : ?>
					<h3><?php $this->customer_notes_title(); ?></h3>
					<?php $this->shipping_notes(); ?>
				<?php endif; ?>
			</div>				
			<?php do_action( 'wpo_wcpdf_after_customer_notes', $this->get_type(), $this->order ); ?>
		</div>
	</div>
</div>

<div class="bottom-spacer"></div>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->get_type(), $this->order ); ?>

<?php if ( $this->get_footer() ) : ?>
	<htmlpagefooter name="docFooter"><!-- required for mPDF engine -->
		<div id="footer">
			<!-- hook available: wpo_wcpdf_before_footer -->
			<?php $this->footer(); ?>
			<!-- hook available: wpo_wcpdf_after_footer -->
		</div>
	</htmlpagefooter><!-- required for mPDF engine -->
<?php endif; ?>

<?php do_action( 'wpo_wcpdf_after_document', $this->get_type(), $this->order ); ?>