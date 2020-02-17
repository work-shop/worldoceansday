<!DOCTYPE html>
<html lang="en">
<meta content="IE=edge" http-equiv="X-UA-Compatible" />
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<meta content="width=device-width, initial-scale=1" name="viewport" />
<head>
	<title><?php bloginfo('name'); ?></title>
	<style type="text/css">
		a, a:link, a:visited {
			color:#FF8C00;
			text-decoration: none !important;
		}
		a {text-decoration: none !important;}
		a:hover, a:active {
			color:#FF8C00!important;
			text-decoration:none;
		}
	</style>
</head>
<body>
	<div style="margin-bottom:40px;">
		<h3>Welcome to World Oceans Day!</h3>
		<p>With your new account, you can log into your dashboard and manage your event listings.</p>
	</div>
	<div style="margin-bottom: 30px">
		<p><strong>Your Login Details:</strong></p>
		<p>Email: <?php echo esc_html($user_email); ?>
		<?php if($plaintext_pass): ?>
			<br>Password: <?php echo $plaintext_pass; ?>
			<?php else: ?>
				<br>To set your password, visit <a href="https://worldoceansday.org/my-account/lost-password/" target="_blank">https://worldoceansday.org/my-account/lost-password/</a></p>
			<?php endif; ?>
			<p>Login at <a href="https://worldoceansday.org/my-account" target="_blank">https://worldoceansday.org/my-account</a></p>
		</div>
		<div style="margin-bottom: 30px">
			<p><strong>Ready to submit an event? </strong></p>
			<p>Share your event on our global database by <a href="https://worldoceansday.org/submit" target="_blank">filling out the event submission form</a></p>
		</div>
		<div style="margin-bottom: 30px">
			<p>If you have any questions, don't hesitate to <a href="https://worldoceansday.org/contact" target="_blank">contact us.</a>
			</p>
		</div>
	</body>
	</html>