<?php
/**
 * Compact header template.
 *
 * This template can be overridden by copying it to yourtheme/wpforms/emails/compact-header.php.
 *
 * @since 1.8.5
 *
 * @var string $title        Email title.
 * @var array  $header_image Header image arguments.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="color-scheme" content="light dark">
	<title><?php echo esc_html( $title ); ?></title>
</head>
<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="#e9eaec">
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" class="body" role="presentation">
	<tr>
		<td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td>
		<td align="center" valign="top" class="body-inner" width="700">
			<div class="wrapper" width="100%" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
				<table border="0" cellpadding="0" cellspacing="0" width="100%" class="container" role="presentation">
					<tr>
						<?php if ( ! empty( $header_image['url'] ) ) : ?>
						<td align="center" valign="middle" class="header">
							<div class="header-image has-image-size-<?php echo ! empty( $header_image['size'] ) ? esc_attr( $header_image['size'] ) : 'medium'; ?>">
								<img src="<?php echo esc_url( $header_image['url'] ); ?>" <?php echo isset( $header_image['width'] ) ? 'width="' . absint( $header_image['width'] ) . '"' : ''; ?> alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
							</div>
						</td>
						<?php endif; ?>
					</tr>
					<tr>
						<td class="wrapper-inner" bgcolor="#ffffff">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
								<tr>
									<td valign="top" class="content <?php echo is_rtl() ? 'is-rtl' : 'is-ltr'; ?>">
