<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package kokoro
 */

?>

	</div><!-- #content -->

	<div id="instagram-footer" class="instagram-footer">

		<?php	/* Widgetised Area */	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('sidebar-2') ) ?>
		
	</div>

	<footer id="colophon" class="site-footer" role="contentinfo">

		<div class="site-info container">
			<?php printf(esc_html__('%1$s %2$s %3$s', 'kokoro'), '&copy;', esc_attr(date_i18n(__('Y', 'kokoro'))), esc_attr(get_bloginfo())); ?>
                <span class="sep"> &ndash; </span>
            <?php printf(esc_html__('%1$s Kokoro Theme by %2$s', 'kokoro'), '', '<a href="' . esc_url('https://zthemes.net/', 'kokoro') . '">ZThemes Studio</a>'); ?>
		</div><!-- .site-info -->
		
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
