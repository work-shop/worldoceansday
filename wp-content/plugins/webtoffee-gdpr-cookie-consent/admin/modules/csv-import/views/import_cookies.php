<div class="wrap">
	<h2><?php _e( 'Import from a CSV file' , 'cookie-law-info'); ?></h2>
	<?php
	$error_log_file = self::$log_dir_path . 'cookielawinfo_errors.log';
	$error_log_url  = self::$log_dir_url . 'cookielawinfo_errors.log';

	if(is_dir(self::$log_dir_path))
	{
		if (!file_exists( $error_log_file ) ) 
		{
			if ( ! @fopen( $error_log_file, 'x' ) )
				echo '<div class="updated"><p><strong>' . sprintf( __( 'Notice: please make the directory %s writable so that you can see the error log.' , 'cookie-law-info'), self::$log_dir_path ) . '</strong></p></div>';
		}
	}else
	{
		if(!@mkdir(self::$log_dir_path,0755))
		{
			//echo '<div class="updated"><p><strong>' . __( 'Notice: `wp-content/uploads` directory not found.' , 'cookie-law-info') . '</strong></p></div>';
		}
	}

	if ( isset( $_GET['import'] ) ) {
		$error_log_msg = '';
		if ( file_exists( $error_log_file ) )
			$error_log_msg = sprintf( __( ', please <a href="%s">check the error log</a>' , 'cookie-law-info'), $error_log_url );

		switch ( $_GET['import'] ) {
			case 'file':
				echo '<div class="error"><p><strong>' . __( 'Error during file upload.' , 'cookie-law-info') . '</strong></p></div>';
				break;
			case 'data':
				echo '<div class="error"><p><strong>' . __( 'Cannot extract data from uploaded file or no file was uploaded.' , 'cookie-law-info') . '</strong></p></div>';
				break;
			case 'fail':
				echo '<div class="error"><p><strong>' . sprintf( __( 'No posts was successfully imported%s.' , 'cookie-law-info'), $error_log_msg ) . '</strong></p></div>';
				break;
			case 'errors':
				echo '<div class="error"><p><strong>' . sprintf( __( 'Some posts were successfully imported but some were not%s.' , 'cookie-law-info'), $error_log_msg ) . '</strong></p></div>';
				break;
			case 'success':
				echo '<div class="updated"><p><strong>' . __( 'Post import was successful.' , 'cookie-law-info') . '</strong></p></div>';
				break;
			default:
				break;
		}
	}
	?>
	<form method="post" action="" enctype="multipart/form-data">
		<?php wp_nonce_field( 'cookie-page_import', '_wpnonce-icookie-page_import' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="cookie_csv"><?php _e( 'CSV file' , 'cookie-law-info'); ?></label></th>
				<td>
					<input type="file" id="cookie_csv" name="cookie_csv" value="" class="all-options" /><br />
					<span class="description"><?php echo sprintf( __( 'You may want to see <a href="%s">the example of the CSV file</a>.' , 'cookie-law-info'), $example_file); ?></span>
				</td>
			</tr>
		</table>
		<p class="submit">
		 	<input type="submit" class="button-primary" value="<?php _e( 'Import' , 'cookie-law-info'); ?>" />
		</p>
	</form>