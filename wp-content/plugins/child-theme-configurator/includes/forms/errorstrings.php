<?php


$errorstrings = array(
    
    '1'   => __( 'Zip file creation failed.', 'child-theme-configurator' ),
    '2'   => __( 'You do not have permission to configure child themes.', 'child-theme-configurator' ),
    '3'   => __( '%s does not exist. Please select a valid Parent Theme.', 'child-theme-configurator' ),
    '4'   => __( 'The Functions file is required and cannot be deleted.', 'child-theme-configurator' ),
    '5'   => __( 'Please select a valid Parent Theme.', 'child-theme-configurator' ),
    '6'   => __( 'Please select a valid Child Theme.', 'child-theme-configurator' ),
    '7'   => __( 'Please enter a valid Child Theme directory name.', 'child-theme-configurator' ),
    '8'   => __( '<strong>%s</strong> exists. Please enter a different Child Theme template name.', 'child-theme-configurator' ),
    '9'   => __( 'Your theme directories are not writable.', 'child-theme-configurator' ), // add_action( 'chld_thm_cfg_admin_notices', array( $this, 'writable_notice' ) );     

    '10'  => __( 'Could not upgrade child theme', 'child-theme-configurator' ),
    '11'  => __( 'Your stylesheet is not writable.', 'child-theme-configurator' ), // add_action( 'chld_thm_cfg_admin_notices', array( $this, 'writable_notice' ) );     

    '12'  => __( 'A closing PHP tag was detected in Child theme functions file so "Parent Stylesheet Handling" option was not configured. Closing PHP at the end of the file is discouraged as it can cause premature HTTP headers. Please edit <code>functions.php</code> to remove the final <code>?&gt;</code> tag and click "Generate/Rebuild Child Theme Files" again.', 'child-theme-configurator' ),
    '13'  => __( 'Could not copy file: %s', 'child-theme-configurator' ),
    '14'  => __( 'Could not delete %s file.', 'child-theme-configurator' ),
    '15'  => __( 'could not copy %s', 'child-theme-configurator' ), //unused
    '16'  => __( 'invalid dir: %s', 'child-theme-configurator' ), //unused
    '17'  => __( 'deleted: %s != %s files', 'child-theme-configurator' ), //unused
    '18'  => __( 'newfiles != files', 'child-theme-configurator' ), //unused
    '19'  => __( 'There were errors while resetting permissions.', 'child-theme-configurator' ), // add_action( 'chld_thm_cfg_admin_notices', array( $this, 'writable_notice' ) );     

    '20'  => __( 'Could not upload file.', 'child-theme-configurator' ),
    '21'  => __( 'Invalid theme root directory.', 'child-theme-configurator' ),
    '22'  => __( 'No writable temp directory.', 'child-theme-configurator' ),
    '23'  => __( 'PclZip returned zero bytes.', 'child-theme-configurator' ),
    '24'  => __( 'Unpack failed -- %s', 'child-theme-configurator' ), // unused
    '25'  => __( 'Pack failed -- %s', 'child-theme-configurator' ), //unused
    '26'  => __( 'Maximum number of styles exceeded.', 'child-theme-configurator' ),
    '27'  => __( 'Error moving file: %s', 'child-theme-configurator' ),
    '28'  => __( 'Could not set write permissions.', 'child-theme-configurator' ), // add_action( 'chld_thm_cfg_admin_notices', array( $this, 'writable_notice' ) );     

);

$writable_errors = array(
    9,
    11,
    19,
    28
);