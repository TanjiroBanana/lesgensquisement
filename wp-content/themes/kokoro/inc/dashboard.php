<?php
/**
 * Add theme dashboard page
 */

add_action('admin_menu', 'kokoro_theme_info');
if ( ! function_exists( 'kokoro_theme_info' ) ) {
    function kokoro_theme_info()
    {
        $theme_data = wp_get_theme();
        add_theme_page(sprintf(esc_html__('%s Theme Dashboard', 'kokoro'), $theme_data->Name), sprintf(esc_html__('%s theme', 'kokoro'), $theme_data->Name), 'edit_theme_options', 'kokoro', 'kokoro_theme_info_page');
    }
}

if ( ! function_exists( 'kokoro_admin_scripts' ) ) {
    /**
     * Enqueue scripts for admin page only: Theme info page
     */
    function kokoro_admin_scripts( $hook )
    {
            wp_enqueue_style('kokoro-admin-css', get_template_directory_uri() . '/css/dashboard.css');
    }
}
add_action('admin_enqueue_scripts', 'kokoro_admin_scripts');

if ( ! function_exists( 'kokoro_theme_info_page' ) ) {
    function kokoro_theme_info_page()
    {
        $theme_data = wp_get_theme();
        // Check for current viewing tab
        $tab = null;
        if ( isset( $_GET['tab'] ) ) {
            $tab = $_GET['tab'];
        } else {
            $tab = null;
        }

        ?>

        <div class="wrap about-wrap theme_info_wrapper">
            <h1><?php printf(esc_html__('Welcome to %1s - Version %2s', 'kokoro'), $theme_data->Name, $theme_data->Version); ?></h1>

            <div class="about-text"><?php esc_html_e("Kokoro is a Responsive WordPress blog theme. The theme is a perfect combination of beautiful and professional.", 'kokoro') ?></div>
            <a target="_blank" href="<?php echo esc_url('https://zthemes.net/'); ?>"
               class="zthemes-badge wp-badge"><span><?php echo esc_html('ZThemes Studio', 'kokoro'); ?></span></a>

            <h2 class="nav-tab-wrapper">
                <a href="<?php echo esc_url( add_query_arg( array( 'page'=>'kokoro' ), admin_url( 'themes.php' ) ) ); ?>" class="nav-tab <?php echo ( ! $tab || $tab == 'kokoro' ) ? ' nav-tab-active' : ''; ?>"><?php echo esc_html($theme_data->Name); ?></a>
                <a href="<?php echo esc_url( add_query_arg( array( 'page'=>'kokoro', 'tab' => 'free_pro' ), admin_url( 'themes.php' ) ) ); ?>" class="nav-tab<?php echo $tab == 'free_pro' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Free vs PRO', 'kokoro' ); ?></span></a>
                <a href="<?php echo esc_url( add_query_arg( array( 'page'=>'kokoro', 'tab' => 'contribute' ), admin_url( 'themes.php' ) ) ); ?>" class="nav-tab<?php echo $tab == 'contribute' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Contribute', 'kokoro' ); ?><span class="dashicons dashicons-thumbs-up"></span></a>
            </h2>

            <?php if ( is_null( $tab ) ) { ?>
            <div class="theme_info">
                <div class="theme_info_column clearfix">
                    <div class="theme_info_left">
                        <div class="theme_link">
                            <h3><?php esc_html_e('Theme Customizer', 'kokoro'); ?></h3>

                            <p class="about"><?php printf(esc_html__('%s supports the Theme Customizer for all theme settings. Click "Customize" to start customize your site.', 'kokoro'), $theme_data->Name); ?></p>

                            <p>
                                <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-secondary"><?php esc_html_e('Start Customize', 'kokoro'); ?> &#8594;</a>
                            </p>
                        </div>
                        <div class="theme_link">
                            <h3><?php esc_html_e('Having Trouble, Need Support?', 'kokoro'); ?></h3>

                            <p class="about"><?php printf(esc_html__('Support for %s WordPress theme is conducted through ZThemes Studio support ticket system.', 'kokoro'), $theme_data->Name); ?></p>

                            <p>
                                <a class="button button-secondary" target="_blank" href="https://zthemes.net/support"><?php esc_html_e( 'Create a support ticket', 'kokoro' ) ?></a>&nbsp;
                                <a href="http://demo.zthemes.net/docs/" target="_blank" class="button button-secondary"><?php esc_html_e('Some Tips', 'kokoro'); ?></a>
                            </p>
                        </div>
                        <div class="theme_link">
                            <h3 class="kokoro-upgrade"><?php esc_html_e('Upgrade to Kokoro Pro', 'kokoro'); ?></h3>

                            <p class="about"><?php printf(esc_html__('Our #1 blogging WordPress theme with premium features designed for bloggers and content lovers.', 'kokoro'), $theme_data->Name); ?></p>

                            <p>
                                <a class="button button-secondary" target="_blank" href="http://demo.zthemes.net/kokoro/"><?php _e( 'Kokoro Pro Default Demo', 'kokoro' ) ?> &#8594;</a>&nbsp;
                                <a class="button button-primary" target="_blank" href="https://zthemes.net/themes/kokoro"><?php _e( 'Upgrade Now', 'kokoro' ) ?> &#8594;</a>
                            </p>
                            <p>
                                <a class="button button-secondary" target="_blank" href="http://demo.zthemes.net/kokoro-rose/"><?php _e( 'Kokoro Pro Rose', 'kokoro' ) ?> &#8594;</a>&nbsp;
                                <a class="button button-primary" target="_blank" href="https://zthemes.net/themes/kokoro"><?php _e( 'Upgrade Now', 'kokoro' ) ?> &#8594;</a>
                            </p>
                            <p>
                                <a class="button button-secondary" target="_blank" href="http://demo.zthemes.net/kokoro-interior/"><?php _e( 'Kokoro Pro Interior', 'kokoro' ) ?> &#8594;</a>&nbsp;
                                <a class="button button-primary" target="_blank" href="https://zthemes.net/themes/kokoro"><?php _e( 'Upgrade Now', 'kokoro' ) ?> &#8594;</a>
                            </p>
                        </div>
                    </div>

                    <div class="theme_info_right">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/screenshot.png" alt="<?php esc_attr_e('Theme Screenshot', 'kokoro'); ?>"/>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if ( $tab == 'contribute' ) { ?>
            <div class="contribute-tab-content feature-section three-col">
                <div class="col">
                    <div class="theme_info_boxed">
                        <p><strong><?php esc_html_e( 'Are you a polyglot? Want to translate Kokoro into your own language?', 'kokoro' ); ?></strong></p>
                        <p><?php esc_html_e('Get involved at WordPress.org.', 'kokoro'); ?></p>
                        <p>
                            <a href="https://translate.wordpress.org/projects/wp-themes/kokoro" target="_blank" class="button button-primary"><?php esc_html_e('Translate Kokoro', 'kokoro'); ?> <span class="dashicons dashicons-external"></span></a>
                        </p>
                    </div>
                </div>
                <div class="col">
                    <div class="theme_info_boxed">
                        <p><strong><?php esc_html_e( 'Are you enjoying Kokoro theme?', 'kokoro' ); ?></strong></p>
                        <p><?php printf( esc_html__('Rate our theme on %1s. We\'d really appreciate it!', 'kokoro'), '<a target="_blank" href="https://wordpress.org/support/theme/kokoro/reviews/?filter=5">WordPress.org</a>' ); ?></p>
                        <p><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></p>
                    </div>
                </div>
            </div>
        <?php } ?>

	        <?php if ( $tab == 'free_pro' ) { ?>
                <div id="free_pro" class="freepro-tab-content info-tab-content">
                    <table class="free-pro-table">
                        <thead><tr><th></th><th>Kokoro</th><th>Kokoro Pro</th></tr></thead>
                        <tbody>
                        <tr>
                            <td>
                                <h4>Responsive Design</h4>
                            </td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Translation Ready</h4>
                            </td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Upload Your Own Logo</h4>
                            </td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Sidebar Layout</h4>
                            </td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>1-CLICK DEMO IMPORT</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>900+ GOOGLE FONTS</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Featured Slider</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>WooCommerce Compatible</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Custom Widgets</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Posts/Page Settings</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Instagram Feed Widget</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Footer Widget Area</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Footer Menu</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Footer Copyright Editor</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>

                        <tr>
                            <td>
                                <h4>Mailchimp Support</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>


                        <tr class="ti-about-page-text-center"><td></td><td colspan="2"><a href="https://zthemes.net/themes/kokoro" target="_blank" class="button button-primary button-hero">Get Kokoro Pro now!</a></td></tr>
                        </tbody>
                    </table>
                </div>
	        <?php } ?>


        </div> <!-- END .theme_info -->
        <script type="text/javascript">
            jQuery(  document).ready( function( $ ){
                $( 'body').addClass( 'about-php' );
            } );
        </script>
        <?php
    }
}
