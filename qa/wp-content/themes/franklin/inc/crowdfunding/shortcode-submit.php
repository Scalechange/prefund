<?php 
/**
 * Templating functions to override the campaign submission form.
 * 
 * @package franklin
 */

// Due to the way ATCF changed between version 1.6 and 1.7, we need to provide a fallback for the older version.
$crowdfunding = crowdfunding();

/**
 * This basically has the job of overriding default functions used by ATCF. 
 * 
 * @see atcf_submit_campaign()
 * @return void
 */
function franklin_atcf_submit_campaign() {		
	remove_action( 'atcf_shortcode_submit_field_number', 'atcf_shortcode_submit_field_number', 10, 3 );
	add_action( 'atcf_shortcode_submit_field_number', 'franklin_atcf_shortcode_submit_field_number', 10, 3 );

	remove_action( 'atcf_shortcode_submit_field_term_checklist', 'atcf_shortcode_submit_field_term_checklist', 10, 3 );
	add_action( 'atcf_shortcode_submit_field_term_checklist', 'franklin_atcf_shortcode_submit_field_term_checklist', 10, 3 );

	remove_action( 'atcf_shortcode_submit_field_rewards', 'atcf_shortcode_submit_field_rewards', 10, 3 );
	add_action( 'atcf_shortcode_submit_field_rewards', 'franklin_atcf_shortcode_submit_field_rewards_action', 10, 3 );
}

add_action( 'init', 'franklin_atcf_submit_campaign', 11 );	
add_action( 'atcf_submit_process_after', array( new Sofa_Crowdfunding_Helper(), 'delete_transients' ), 10, 1 );	

/**
 * Number field.
 * 
 * @param $key The key of the current field.
 * @param $field The array of field arguments.
 * @param $args The array of arguments relating to the current state of the campaign
 * @return void
 * @since Franklin 1.4.2
 */
function franklin_atcf_shortcode_submit_field_number( $key, $field, $args ) {
	if ( $key == 'length' ) {
		franklin_atcf_shortcode_submit_field_length( $args, $args['campaign'] );
	} 
	else {
		atcf_shortcode_submit_field_number( $key, $field, $args );
	}	
}

/**
 * Rewards.
 * 
 * @param $key The key of the current field.
 * @param $field The array of field arguments.
 * @param $args The array of arguments relating to the current state of the campaign
 * @return void
 * @since Franklin 1.4.2
 */
function franklin_atcf_shortcode_submit_field_rewards_action( $key, $field, $args ) {
	franklin_atcf_shortcode_submit_field_rewards( $args, $args['campaign'] );
}

/**
 * Term checklist field.
 * 
 * @param $key The key of the current field.
 * @param $field The array of field arguments.
 * @param $args The array of arguments relating to the current state of the campaign
 * @return void
 * @since Franklin 1.4.2
 */
function franklin_atcf_shortcode_submit_field_term_checklist( $key, $field, $args ) {
	if ( ! atcf_theme_supports( 'campaign-' . $key ) ) {
		return;
	}

	if ( ! function_exists( 'wp_terms_checklist' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/admin.php' );
	}
?>
	<div class="atcf-submit-campaign-<?php echo esc_attr( $key ); ?>">
		<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $field[ 'label' ] ); ?></label>

		<ul class="atcf-multi-select cf">			
		<?php 
			wp_terms_checklist( is_null( $args['campaign'] ) ? 0 : $args['campaign']->ID, array( 
				'taxonomy'   => 'download_' . $key,
				'walker'     => new ATCF_Walker_Terms_Checklist
			) );
		?>
		</ul>
	</div>
<?php
}

/**
 * Campaign Length 
 *
 * @param array $args
 * @param null|ATCF_Campaign $campaign
 * @return void
 * @since Franklin 1.1
 */
function franklin_atcf_shortcode_submit_field_length( $args, $campaign ) {
	global $post, $edd_options;

	if ( $args[ 'editing' ] )
		return;

	$min = isset ( $edd_options[ 'atcf_campaign_length_min' ] ) ? $edd_options[ 'atcf_campaign_length_min' ] : 14;
	$max = isset ( $edd_options[ 'atcf_campaign_length_max' ] ) ? $edd_options[ 'atcf_campaign_length_max' ] : 48;	

	$description = sprintf( __( "Your campaign's length can be between %d and %d days", 'franklin' ), $min, $max );

	if ( $args['previewing'] ) {
		$value = $campaign->days_remaining();
		$placeholder = $value;
	}
	else {
		$value = apply_filters( 'atcf_shortcode_submit_field_length_start', round( ( $min + $max ) / 2 ) );
		$placeholder = null;	
	}
?>
	<p class="atcf-submit-campaign-length">
		<label for="length"><?php _e( 'Length (Days)', 'franklin' ); ?></label>
		<input type="number" 
			min="<?php echo esc_attr( $min ); ?>" 
			max="<?php echo esc_attr( $max ); ?>" 
			step="1" 
			name="length" 
			id="length" 
			value="<?php echo esc_attr( $value ) ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>">
		<span class="description"><?php echo $description ?></span>
	</p>
<?php
}


/**
 * Campaign Category
 *
 * @param array $atts
 * @param ATCF_Campaign $campaign
 * @return void
 * @since Franklin 1.4.2
 */
// function franklin_atcf_shortcode_submit_field_category( $atts, $campaign ) {
// 	franklin_atcf_shortcode_submit_field_term_checklist( 'category', array( 'label' => __( 'Category', 'franklin' ) ), $atts, $campaign );	
// }

/**
 * Campaign Tags
 *
 * @param array $atts
 * @param ATCF_Campaign $campaign
 * @return void
 * @since Franklin 1.4.2
 */
// function franklin_atcf_shortcode_submit_field_tags( $atts, $campaign ) {
// 	franklin_atcf_shortcode_submit_field_term_checklist( 'tag', array( 'label' => __( 'Tags', 'franklin' ) ), $atts, $campaign );
// }

/**
 * Campaign Backer Rewards
 *
 * @param array $atts
 * @param ATCF_Campaign $campaign
 * @param array $field
 * @return void
 * @since Franklin 1.1
 */
function franklin_atcf_shortcode_submit_field_rewards( $atts, $campaign ) {
	if ( ! $atts['previewing'] && ! $atts['editing'] ) {
		$rewards = array( 0 => array( 'amount' => null, 'name' => null, 'limit' => null ) );	
	}
	else {
		$rewards = edd_get_variable_prices( $campaign->ID );
		if ( empty( $rewards ) ) {
			$rewards = array( 0 => array( 'amount' => null, 'name' => null, 'limit' => null ) );	
		}		
	}
	
	$crowdfunding = crowdfunding();
	$amount_key = 'amount';
	$name_key = 'name';

	if ( $crowdfunding->version < 1.7 ) :
		$amount_key = 'price';
		$name_key = 'description';
		$shipping = $atts[ 'previewing' ] || $atts[ 'editing' ] ? $campaign->needs_shipping() : 0;
		$norewards = $atts[ 'previewing' ] || $atts[ 'editing' ] ? $campaign->is_donations_only() : 0;
?>
	<h3 class="atcf-submit-section backer-rewards"><?php _e( 'Backer Rewards', 'franklin' ); ?></h3>

	<p class="atcf-submit-campaign-shipping">
		<label for="shipping"><input type="checkbox" id="shipping" name="shipping" value="1" <?php checked(1, $shipping); ?> /> <?php _e( 'Collect shipping information on checkout.', 'franklin' ); ?></label>
	</p>

	<p class="atcf-submit-campaign-norewards">
		<label for="norewards"><input type="checkbox" id="norewards" name="norewards" value="1" <?php checked(1, $norewards); ?> /> <?php _e( 'No rewards, donations only.', 'franklin' ); ?></label>
	</p>

<?php		
	endif;

	do_action( 'atcf_shortcode_submit_field_rewards_list_before' ); ?>

	<div class="atcf-submit-campaign-rewards">
		<?php foreach ( $rewards as $key => $reward ) : 
			$disabled = isset ( $reward[ 'bought' ] ) && $reward[ 'bought' ] > 0 ? true : false; 
		?>
		<div class="atcf-submit-campaign-reward">
			<?php do_action( 'atcf_shortcode_submit_field_rewards_before' ); ?>

			<p class="atcf-submit-campaign-reward-price">
				<label for="rewards[<?php echo esc_attr( $key ); ?>][<?php echo $amount_key ?>]"><?php printf( __( 'Amount (%s)', 'franklin' ), edd_currency_filter( '' ) ); ?></label>
				<input class="name" type="text" name="rewards[<?php echo esc_attr( $key ); ?>][<?php echo $amount_key ?>]" id="rewards[<?php echo esc_attr( $key ); ?>][<?php echo $amount_key ?>]" value="<?php echo esc_attr( $reward[$amount_key] ); ?>" <?php disabled(true, $disabled); ?> />
			</p>

			<p class="atcf-submit-campaign-reward-limit">
				<label for="rewards[<?php echo esc_attr( $key ); ?>][limit]"><?php _e( 'Limit', 'franklin' ); ?></label>
				<input class="description" type="text" name="rewards[<?php echo esc_attr( $key ); ?>][limit]" id="rewards[<?php echo esc_attr( $key ); ?>][limit]" value="<?php echo isset ( $reward[ 'limit' ] ) ? esc_attr( $reward[ 'limit' ] ) : null; ?>" <?php disabled(true, $disabled); ?> />
			</p>

			<p class="atcf-submit-campaign-reward-description">
				<label for="rewards[<?php echo esc_attr( $key ); ?>][<?php echo $name_key ?>]"><?php _e( 'Reward', 'franklin' ); ?></label>
				<textarea class="<?php echo $name_key ?>" name="rewards[<?php echo esc_attr( $key ); ?>][<?php echo $name_key ?>]" id="rewards[<?php echo esc_attr( $key ); ?>][<?php echo $name_key ?>]" rows="3" <?php disabled(true, $disabled); ?>><?php echo esc_attr( $reward[$name_key] ); ?></textarea>
			</p>			

			<?php do_action( 'atcf_shortcode_submit_field_rewards_after' ); ?>

			<?php if ( ! $disabled ) : ?>
			<p class="atcf-submit-campaign-reward-remove">
				<a href="#">&times; <?php _e( 'Remove', 'franklin' ) ?></a>
			</p>
			<?php endif; ?>
		</div>
		<?php endforeach; ?>

		<p class="atcf-submit-campaign-add-reward">
			<a href="#" class="atcf-submit-campaign-add-reward-button"><?php _e( '+ <em>Add Reward</em>', 'franklin' ); ?></a>
		</p>
	</div>
<?php
}

/**
 * Renders the Checkout Agree to Terms, this displays a checkbox for users to
 * agree the T&Cs set in the EDD Settings. This is only displayed if T&Cs are
 * set in the EDD Settigs.
 *
 * @since 1.2.1
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function franklin_edd_terms_agreement() {
	global $edd_options;
	if ( isset( $edd_options['show_agree_to_terms'] ) ) {
?>
		<fieldset id="edd_terms_agreement">
				<div id="edd_terms" style="display:none;">
					<?php
						do_action( 'edd_before_terms' );
						echo wpautop( $edd_options['agree_text'] );
						do_action( 'edd_after_terms' );
					?>
				</div>
				<div id="edd_show_terms">
					<a href="#" class="edd_terms_links"><?php _e( 'Show Terms', 'edd' ); ?></a>
					<a href="#" class="edd_terms_links" style="display:none;"><?php _e( 'Hide Terms', 'edd' ); ?></a>
				</div>
				<label for="tos">
					<input name="tos" class="required" type="checkbox" id="edd_agree_to_terms" value="1"/>
					<?php echo isset( $edd_options['agree_label'] ) ? $edd_options['agree_label'] : __( 'Agree to Terms?', 'edd' ); ?>
				</label>				
		</fieldset>
<?php
	}
}

/**
 * Terms
 *
 * @since 1.2.1
 * @return void
 */
function franklin_atcf_shortcode_submit_field_terms( $atts, $campaign ) {
	edd_agree_to_terms_js();
	franklin_edd_terms_agreement();
}