<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package kokoro
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'kokoro' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<nav id="site-navigation" class="main-navigation2 top-nav-kokoro" role="navigation">
			<div class="top-nav container">
				
				<?php wp_nav_menu( array( 'theme_location' => 'topmenu', 'menu_id' => 'top-menu', 'depth' => 1 ) ); ?>

				<div id="top-social">
					
					<?php if(get_theme_mod('kokoro_facebook')) : ?><a href="<?php echo esc_url(get_theme_mod('kokoro_facebook')); ?>" target="_blank"><i class="fa fa-facebook"></i></a><?php endif; ?>
					<?php if(get_theme_mod('kokoro_twitter')) : ?><a href="<?php echo esc_url(get_theme_mod('kokoro_twitter')); ?>" target="_blank"><i class="fa fa-twitter"></i></a><?php endif; ?>
					<?php if(get_theme_mod('kokoro_instagram')) : ?><a href="<?php echo esc_url(get_theme_mod('kokoro_instagram')); ?>" target="_blank"><i class="fa fa-instagram"></i></a><?php endif; ?>
					<?php if(get_theme_mod('kokoro_pinterest')) : ?><a href="<?php echo esc_url(get_theme_mod('kokoro_pinterest')); ?>" target="_blank"><i class="fa fa-pinterest"></i></a><?php endif; ?>
					<?php if(get_theme_mod('kokoro_bloglovin')) : ?><a href="<?php echo esc_url(get_theme_mod('kokoro_bloglovin')); ?>" target="_blank"><i class="fa fa-heart"></i></a><?php endif; ?>
					<?php if(get_theme_mod('kokoro_google')) : ?><a href="<?php echo esc_url(get_theme_mod('kokoro_google')); ?>" target="_blank"><i class="fa fa-google-plus"></i></a><?php endif; ?>
					<?php if(get_theme_mod('kokoro_tumblr')) : ?><a href="<?php echo esc_url(get_theme_mod('kokoro_tumblr')); ?>.tumblr.com/" target="_blank"><i class="fa fa-tumblr"></i></a><?php endif; ?>
					<?php if(get_theme_mod('kokoro_youtube')) : ?><a href="<?php echo esc_url(get_theme_mod('kokoro_youtube')); ?>" target="_blank"><i class="fa fa-youtube-play"></i></a><?php endif; ?>

					<?php if(get_theme_mod('kokoro_dribbble')) : ?><a href="<?php echo esc_url(get_theme_mod('kokoro_dribbble')); ?>" target="_blank"><i class="fa fa-dribbble"></i></a><?php endif; ?>
					<?php if(get_theme_mod('kokoro_soundcloud')) : ?><a href="<?php echo esc_url(get_theme_mod('kokoro_soundcloud')); ?>" target="_blank"><i class="fa fa-soundcloud"></i></a><?php endif; ?>
					<?php if(get_theme_mod('kokoro_vimeo')) : ?><a href="<?php echo esc_url(get_theme_mod('kokoro_vimeo')); ?>" target="_blank"><i class="fa fa-vimeo-square"></i></a><?php endif; ?>
					<?php if(get_theme_mod('kokoro_linkedin')) : ?><a href="<?php echo esc_url(get_theme_mod('kokoro_linkedin')); ?>" target="_blank"><i class="fa fa-linkedin"></i></a><?php endif; ?>
					<?php if(get_theme_mod('kokoro_rss')) : ?><a href="<?php echo esc_url(get_theme_mod('kokoro_rss')); ?>" target="_blank"><i class="fa fa-rss"></i></a><?php endif; ?>
					
				</div>
			</div>
		</nav><!-- #site-navigation -->

		<div class="site-branding container">

			<?php if ( has_custom_logo() ) { kokoro_the_custom_logo(); }?>

				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>

				<?php
					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) : ?>
						<p class="site-description"><?php bloginfo( 'description' ); ?></p>
				<?php endif; ?>

		</div><!-- .site-branding -->

		<!-- #main-menu -->
		<nav class="main-navigation" id="main-nav">
		<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
			<span class="m_menu_icon"></span>
			<span class="m_menu_icon"></span>
			<span class="m_menu_icon"></span>
		</button>
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => 'nav-menu menu' ) ); ?>
		</nav>

	</header><!-- #masthead -->

	<div id="content" class="site-content">
