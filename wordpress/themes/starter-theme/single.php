<?php
/**
 * The template for displaying a single post.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>
	<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<h1><?php the_title(); ?></h1>
		<div class="entry-meta"><?php echo esc_html( get_the_date() ); ?></div>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="entry-thumbnail"><?php the_post_thumbnail(); ?></div>
		<?php endif; ?>
		<div class="entry-content"><?php the_content(); ?></div>
	</article>

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<?php comments_template(); ?>
	<?php endif; ?>
<?php endwhile; ?>

<?php
get_footer();
