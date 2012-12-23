<?php
$supporters_args = array(
	'post_type' => 'supporters',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'orderby' => 'title'
);

$supporters = new WP_Query($supporters_args);

if ($supporters->have_posts()): while ($supporters->have_posts()): $supporters->the_post();

	$html = '<div id="'.get_the_ID().'" class="'.get_post_class('supporter').'">';
	$html .= '<a href="'.get_permalink().'">';
	$html .= get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('class'=>'supporter-thumb'));
	$html .= '<span>'.get_the_title().'</span></a></div>';
	echo $html;

endwhile; else:

	echo '<h1>Not found</h1><p>Sorry, no supporters were found.</p>';

endif;