<?php
$supporters_args = array(
	'post_type' => 'supporters',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'orderby' => 'title'
);

$supporters = new WP_Query($supporters_args);

if ($supporters->have_posts()):

	$html = '<ul class="thumbnails">';
	while ($supporters->have_posts()): $supporters->the_post();
	
		$link = get_post_meta(get_the_ID(), 'supporters_url', true);
		$html .= '<li id="supporter-'.get_the_ID().'" class="'.implode(' ', get_post_class('span4')).'">';
		$html .= '<div class="thumbnail">';
		$html .= '<a href="'.$link.'">';
		$html .= get_the_post_thumbnail(get_the_ID(), 'medium', array('class'=>'supporter-thumb','width'=>'','height'=>''));
		$html .= '</a><h3>'.get_the_title().'</h3><p>'.get_the_excerpt().'</p></div></li>';
	
	endwhile;
	$html .= '</ul>';
	echo $html;
	
else:

	echo '<h1>Not found</h1><p>Sorry, no supporters were found.</p>';

endif;