<?php

if ( ! defined( 'ABSPATH' ) ) { 
	exit;
}

do_action( 'woocommerce_bsc_twitter_my_account_tab' ); ?>

<div class="woocommerce-bsc_twitter-content">

	<form method="post" enctype="multipart/form-data">

		<h3><?php echo apply_filters( 'woocommerce_my_account_bsc_twitter_title', 'Twitter Widget Settings' ); ?></h3>

		<div class="woocommerce-address-fields">
		
			<?php do_action( "woocommerce_before_bsc_twitter_form" ); ?>

			<p class="form-row form-row-wide" id="bsc_twitter" data-priority="30"><label for="bsc_twitter" class="">Twitter Accounts to Pull From&nbsp(Comma Seperated)</label>
				<span class="woocommerce-input-wrapper">
					<input type="text" class="input-text " name="bsc_twitter" id="bsc_twitter" placeholder="@Saucal" value="" autocomplete="">
				</span>
			</p>
			
			<p class="form-row form-row-wide" id="bsc_twitter_count" data-priority="30"><label for="bsc_twitter_count" class="">Total Tweets to Display</label>
				<span class="woocommerce-input-wrapper">
					<input type="number" class="input-number " name="bsc_twitter_count" id="bsc_twitter_count" placeholder="5" value="" autocomplete="">
				</span>
			</p>	

			
			<p class="form-row form-row-wide" id="bsc_twitter_days" data-priority="30"><label for="bsc_twitter_days" class="">Maximum Days Old (Leave 0 for Any)</label>
				<span class="woocommerce-input-wrapper">
					<input type="number" class="input-number " name="bsc_twitter_days" id="bsc_twitter_days" placeholder="7" value="" autocomplete="">
				</span>
			</p>				

			<?php do_action( "woocommerce_after_bsc_twitter_form" ); ?>

			<p>
				<button type="submit" class="button" name="save_bsc_twitter" value="<?php esc_attr_e( 'Save Settings', 'woocommerce' ); ?>"><?php esc_html_e( 'Save Settings', 'woocommerce' ); ?></button>
				<?php wp_nonce_field( 'woocommerce-bsc-twitter', 'woocommerce-bsc-twitter-nonce' ); ?>
				<input type="hidden" name="action" value="save_bsc_twitter" />
			</p>
		</div>

	</form>

</div> 