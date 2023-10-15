<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package saralite
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if(is_front_page()) : ?>
		<div class="entry-box-post">
	<?php endif; ?>

	<header class="entry-header">
		<div class="entry-box">
			<span class="entry-cate"><?php the_category(' '); ?></span>
			
			<span class="entry-meta"><?php saralite_posted_on(); ?></span>

		</div>
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) : ?>
		
		<?php
		endif; ?>
	</header><!-- .entry-header -->

	<?php if(has_post_thumbnail()) : ?>
	<div class="entry-thumb">
		<a href="<?php echo esc_url(get_permalink()); ?>"><?php the_post_thumbnail('saralite-full-thumb'); ?></a>
	</div>
	<?php endif; ?>

	<?php if(is_single()) : ?>

	<div class="entry-content">
		<?php the_content(); ?>

		<?php
		wp_link_pages( array(
			'before'      => '<div class="page-links">' . __( 'Pages:', 'saralite' ),
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		) );
		?>
	</div><!-- .entry-content -->
			
	<?php else : ?>
	<div class="entry-content">
		<?php the_excerpt(); ?>
	</div><!-- .entry-content -->

	<div class="entry-more">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php esc_html_e( 'Continue Reading', 'saralite' ); ?></a>
	</div>
	<?php endif; ?>

	<?php if(is_front_page()) : ?>
		</div>
	<?php endif; ?>

	<?php if(is_single()) : ?>
	<div class="entry-tags">
		<?php the_tags("",""); ?>
	</div>
	<?php endif; ?>

</article><!-- #post-## -->
