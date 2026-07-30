<?php
/**
 * The main template file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<div class="entry-meta"><?php echo esc_html( get_the_date() ); ?></div>
			<div class="entry-summary"><?php the_excerpt(); ?></div>
		</article>
	<?php endwhile; ?>

	<?php the_posts_pagination(); ?>
<?php else : ?>
	<p><?php esc_html_e( 'Nothing found.', 'starter-theme' ); ?></p>
<?php endif; ?>

<?php
get_footer();
