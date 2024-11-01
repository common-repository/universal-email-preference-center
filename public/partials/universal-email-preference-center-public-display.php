<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://draglabs.com
 * @since      1.0.0
 *
 * @package    Universal_Email_Preference_Center
 * @subpackage Universal_Email_Preference_Center/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="universal-email-preference-center">
	<?php if (isset($error) && ! empty($error)): ?>
        <div class="uepc-alert error uepc-status-text">
			<?php esc_html_e($error); ?>
        </div>
	<?php endif; ?>

	<?php if (!$valid): ?>
        <div class="uepc-alert error uepc-status-text" >
			<?php esc_html_e($validation_error); ?>
        </div>
	<?php else: ?>
		
		<?php if (is_null($email) || (isset($tamper_protection) && $tamper_protection)): ?>

			<div class="uepc-email-container">
				<?php if (!empty($this->premium) && function_exists("uepcp_fs") && uepcp_fs()->is__premium_only() && uepcp_fs()->can_use_premium_code()): ?>
					<?php if ($heading_retrieve != ''): ?>
						<h3 class="uepc-retrieve-heading"><?php esc_html_e($heading_retrieve); ?></h3>
					<?php endif; ?>
				<?php else: ?>
					<h3 class="uepc-retrieve-heading"><?php esc_html_e('Retrieve Email Preferences', 'universal-email-preference-center'); ?></h3>
				<?php endif; ?>

				<?php if (!empty($this->premium) && uepcp_fs()->is__premium_only()): ?>
					<div id="tamper-protection-alert"></div>
				<?php endif; ?>

				<form method="get">
					<?php if (!empty($this->premium) && function_exists("uepcp_fs") && uepcp_fs()->is__premium_only() && uepcp_fs()->can_use_premium_code()): ?>
						<input type="email" name="email" value="<?php esc_attr_e($email); ?>" placeholder="<?php esc_attr_e($placeholder_email); ?>" class="uepc-email" required="required">
						<?php if ($button_retrieve != ''): ?>
							<input type="submit" class="uepc-button" value="<?php esc_attr_e($button_retrieve); ?>">
						<?php endif; ?>

						<?php if ($email && $current_user->user_email !== $email) : ?>
							<input type="button" id="upec-get-token-btn" class="uepc-button" value="Get Token">
						<?php endif; ?>
					<?php else: ?>
						<input type="email" name="email" class="uepc-email" placeholder="<?php esc_attr_e('Enter your email...', 'universal-email-preference-center'); ?>" value="<?php esc_attr_e($email); ?>" required="required">
						<input type="submit" class="uepc-button" value="<?php esc_attr_e('Retrieve Email Preferences', 'universal-email-preference-center'); ?>">
					<?php endif; ?>
					<br>
				</form>
			</div>

		<?php endif; ?>

		<?php if ($has_credentials): ?>
			<?php if (is_null($email)): ?>
				<h2 class="uepc-available-lists-heading"><?php esc_html_e('Not a subscriber yet?', 'universal-email-preference-center'); ?></h2>
				<h3 class="uepc-available-lists-heading"><?php esc_html_e('Select your lists and enter your email below.', 'universal-email-preference-center'); ?></h3>
			<?php endif; ?>
			<?php if (!empty($this->premium) && function_exists("uepcp_fs") && uepcp_fs()->is__premium_only() && uepcp_fs()->can_use_premium_code()): ?>
				<?php if ($heading_available_lists != ''): ?>
					<h3 class="uepc-available-lists-heading"><?php esc_attr_e($heading_available_lists); ?></h3>
				<?php endif; ?>
			<?php else: ?>
				<h3 class="uepc-available-lists-heading"><?php esc_html_e('Available Lists', 'universal-email-preference-center'); ?></h3>
			<?php endif; ?>

			<form method="post" class="universal-email-preference-center-form">
				<?php $nonce = isset($_GET['nonce']) ? sanitize_text_field($_GET['nonce']) : ''; ?>
			<input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>">
			<?php $template_id = (isset($_GET['templateId']) && !empty($_GET['templateId']) ? sanitize_text_field($_GET['templateId']) : ""); ?>
			<input type="hidden" name="templateId" value="<?php echo esc_attr($template_id); ?>">
			
			<?php $campaignId = (isset($_GET['campaignId']) && !empty($_GET['campaignId']) ? sanitize_text_field($_GET['campaignId']) : ""); ?>
			<input type="hidden" name="campaignId" value="<?php echo esc_attr($campaignId); ?>">

			<?php $prefCtrId = (isset($_GET['prefCtrId']) && !empty($_GET['prefCtrId']) ? sanitize_text_field($_GET['prefCtrId']) : ""); ?>
			<input type="hidden" name="prefCtrId" value="<?php echo esc_attr($prefCtrId); ?>">

				<?php
				require_once apply_filters('uepc_frontend_layout', []);
				?>
				<?php $input_type = is_null($email) ? 'email' : 'hidden'; ?>

				<?php if (!empty($this->premium) && function_exists("uepcp_fs") && uepcp_fs()->is__premium_only() && uepcp_fs()->can_use_premium_code()): ?>
					<input type="<?php esc_attr_e($input_type); ?>" name="email" class="uepc-email" value="<?php esc_attr_e($email); ?>" placeholder="<?php esc_attr_e($placeholder_email); ?>" required="required">
				<?php else: ?>
					<input type="<?php esc_attr_e($input_type); ?>" name="email" class="uepc-email" placeholder="<?php esc_attr_e('Enter your email...', 'universal-email-preference-center'); ?>" value="<?php esc_attr_e($email); ?>" required="required">
				<?php endif; ?>

				<input type="hidden" id="current_subscribed_list_ids" name="current_subscribed_list_ids" value="<?php echo urlencode(serialize($subscribed_lists)); ?>">
				<input type="hidden" id="all_channel_ids" name="all_channel_ids" value="<?php echo urlencode(serialize($channel_ids)); ?>">
				<div class="universal-email-preference-status-text"></div>
				<?php

					if (!empty($this->premium) && uepcp_fs()->is__premium_only()) {
						if (! is_null($email)) {
							$btnLabel = ($button_save_preferences);
						} else {
							$btnLabel = ($button_subscribe);
						};
					} else {
						if (! is_null($email)) {
							$btnLabel = esc_attr_x('Save Preferences', 'Button text if email has NOT been retrieved', 'universal-email-preference-center');
						} else {
							$btnLabel = esc_attr_x('Subscribe', 'Button text if email HAS been retrieved.', 'universal-email-preference-center');
						};
					}
					?>
				<input type="submit" value="<?php echo esc_attr($btnLabel) ?>" class="uepc-button">
			</form>
		<?php endif; ?>
	<?php endif; ?>
</div>
