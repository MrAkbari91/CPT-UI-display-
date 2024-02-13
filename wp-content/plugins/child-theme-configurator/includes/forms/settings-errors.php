<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
if ( isset( $_GET[ 'error' ] ) || count( $this->ctc()->errors )  ):
    include_once( CHLD_THM_CFG_DIR . '/includes/forms/errorstrings.php' );
    $errors = $this->ctc()->errors;

    if ( isset( $_GET[ 'error' ] ) )
        $errors = array_merge( $errors,
            explode( ',', sanitize_text_field( $_GET[ 'error' ] ) )
        );
?>

<div class="error notice is-dismissible dashicons-before">
    <h4>
        <?php _e( 'CTC encountered an error:', 'child-theme-configurator' ); ?>
    </h4>
    <ul>
    <?php
    $writable_error = 0;
    foreach ( $errors as $error ):
        $errs = explode( ':', $error );
        $errkey = array_shift( $errs );
        if ( in_array( $errkey, $writable_errors ) )
            $writable_error = 1;
        if ( $errkey && isset( $errorstrings[ $errkey ] ) ):
            $err = $errorstrings[ $errkey ];
        // accommodate zero, one or two arguments
            printf( '<li>' . $err . '</li>' . LF, array_shift( $errs ), array_shift( $errs ) );
        endif;
    endforeach;
    ?>
    </ul>
</div>
<?php
    if ( $writable_error ):
        $this->render_notices( 'writable' );
    endif;
elseif ( $msg = $this->ctc()->msg ):
    $child_theme = wp_get_theme( $this->ctc()->get( 'child' ) );
?>
<div class="updated notice is-dismissible">
    <?php
    switch ( $msg ):
        case '4':
        ?>
    <p>
        <?php printf( __( 'Child Theme <strong>%s</strong> has been reset. Please configure it using the settings below.', 'child-theme-configurator' ), $child_theme->Name ); ?> </p>
    <?php
            break;
        case '7':
        ?>
    <p>
        <?php _e( 'Update Key saved successfully.', 'child-theme-configurator' ); ?>
    </p>
    <?php
            break;
        case '8':
        ?>
    <p>
        <?php _e( 'Child Theme files modified successfully.', 'child-theme-configurator' ); ?>
    </p>
    <?php
            break;
        default: ?>
    <p class="ctc-success-response">
        <?php echo apply_filters( 'chld_thm_cfg_update_msg', sprintf( __( 'Child Theme <strong>%s</strong> has been generated successfully.', 'child-theme-configurator' ), $child_theme->Name ), $this->ctc() ); ?>
        <?php
            if ( $this->ctc()->is_theme() ): ?>
        <strong>
            <?php _e( 'IMPORTANT:', 'child-theme-configurator' ); ?>
            <?php
                if ( is_multisite() && !$child_theme->is_allowed() ):
                    printf( __( 'You must %sNetwork enable%s your child theme.', 'child-theme-configurator' ),
                        sprintf( '<a href="%s" title="%s" class="ctc-live-preview">',
                            network_admin_url( '/themes.php' ),
                            __( 'Go to Themes', 'child-theme-configurator' ) ),
                        '</a>'
                    );
                else :
                    printf( __( '%sPreview your child theme%s before activating.', 'child-theme-configurator' ),
                        sprintf( '<a href="%s" title="%s" class="ctc-live-preview">',
                            admin_url( '/customize.php?theme=' . $this->ctc()->css->get_prop( 'child' ) ),
                            __( 'Live Preview', 'child-theme-configurator' ) ),
                        '</a>'
                    );
                endif;
            ?>
        </strong>
    </p>
<?php
            endif;
    endswitch;
?>
</div>
<?php
endif;