<?php
/** supporters.php
 * Template Name: Supporters
 *
 * The template for displaying supporters.
 *
 * @author		Philip John
 * @package		We Love Lichfield Fund
 * @since		0.4 - 23/12/2012
 */

get_header(); ?>

<div id="primary" class="span8">
	<?php tha_content_before(); ?>
	<div id="content" role="main">
		<?php tha_content_top();
		
		the_post();
		get_template_part( '/partials/content', 'page' );
		get_template_part('loop', 'supporters');
		comments_template();

		tha_content_bottom(); ?>
	</div><!-- #content -->
	<?php tha_content_after(); ?>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();


/* End of file page.php */
/* Location: ./wp-content/themes/welovelichfieldfund/supporters.php */