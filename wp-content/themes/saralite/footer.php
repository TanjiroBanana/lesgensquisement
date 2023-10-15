<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package saralite
 */

?>

	</div><!-- #content -->


	<footer id="colophon" class="site-footer" role="contentinfo">

		<div id="instagram-footer" class="instagram-footer">

		<?php	/* Widgetised Area */	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('sidebar-2') ) ?>
		
		</div>

		<div id="footer-social" class="container">
					
					<?php if(get_theme_mod('saralite_facebook')) : ?><a href="<?php echo esc_url(get_theme_mod('saralite_facebook')); ?>" target="_blank"><i class="fa fa-facebook"></i></a><?php endif; ?>
					<?php if(get_theme_mod('saralite_twitter')) : ?><a href="<?php echo esc_url(get_theme_mod('saralite_twitter')); ?>" target="_blank"><i class="fa fa-twitter"></i></a><?php endif; ?>
					<?php if(get_theme_mod('saralite_instagram')) : ?><a href="<?php echo esc_url(get_theme_mod('saralite_instagram')); ?>" target="_blank"><i class="fa fa-instagram"></i></a><?php endif; ?>
					<?php if(get_theme_mod('saralite_pinterest')) : ?><a href="<?php echo esc_url(get_theme_mod('saralite_pinterest')); ?>" target="_blank"><i class="fa fa-pinterest"></i></a><?php endif; ?>
					<?php if(get_theme_mod('saralite_bloglovin')) : ?><a href="<?php echo esc_url(get_theme_mod('saralite_bloglovin')); ?>" target="_blank"><i class="fa fa-heart"></i></a><?php endif; ?>
					<?php if(get_theme_mod('saralite_google')) : ?><a href="<?php echo esc_url(get_theme_mod('saralite_google')); ?>" target="_blank"><i class="fa fa-google-plus"></i></a><?php endif; ?>
					<?php if(get_theme_mod('saralite_tumblr')) : ?><a href="<?php echo esc_url(get_theme_mod('saralite_tumblr')); ?>.tumblr.com/" target="_blank"><i class="fa fa-tumblr"></i></a><?php endif; ?>
					<?php if(get_theme_mod('saralite_youtube')) : ?><a href="<?php echo esc_url(get_theme_mod('saralite_youtube')); ?>" target="_blank"><i class="fa fa-youtube-play"></i></a><?php endif; ?>

					<?php if(get_theme_mod('saralite_dribbble')) : ?><a href="<?php echo esc_url(get_theme_mod('saralite_dribbble')); ?>" target="_blank"><i class="fa fa-dribbble"></i></a><?php endif; ?>
					<?php if(get_theme_mod('saralite_soundcloud')) : ?><a href="<?php echo esc_url(get_theme_mod('saralite_soundcloud')); ?>" target="_blank"><i class="fa fa-soundcloud"></i></a><?php endif; ?>
					<?php if(get_theme_mod('saralite_vimeo')) : ?><a href="<?php echo esc_url(get_theme_mod('saralite_vimeo')); ?>" target="_blank"><i class="fa fa-vimeo-square"></i></a><?php endif; ?>
					<?php if(get_theme_mod('saralite_linkedin')) : ?><a href="<?php echo esc_url(get_theme_mod('saralite_linkedin')); ?>" target="_blank"><i class="fa fa-linkedin"></i></a><?php endif; ?>
					<?php if(get_theme_mod('saralite_rss')) : ?><a href="<?php echo esc_url(get_theme_mod('saralite_rss')); ?>" target="_blank"><i class="fa fa-rss"></i></a><?php endif; ?>
					
		</div>
		<div class="site-info container">
			<?php printf(esc_html__('%1$s %2$s %3$s', 'saralite'), '&copy;', esc_attr(date_i18n(__('Y', 'saralite'))), esc_attr(get_bloginfo())); ?>
                <span class="sep"> &ndash; </span>
            <?php printf(esc_html__('%1$s SaraLite theme by %2$s', 'saralite'), '', '<a href="' . esc_url('https://zthemes.net/', 'saralite') . '">ZThemes</a>'); ?>
		</div><!-- .site-info -->
		
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
