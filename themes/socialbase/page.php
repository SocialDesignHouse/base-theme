<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<article class="post" id="post-<?php the_ID(); ?>">
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
		<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	</article>
	<?php endwhile; endif; ?>

<?php get_footer(); ?>
