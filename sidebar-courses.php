<?php
/**
 * The Sidebar for all subpages.
 *
 * @package WordPress
 * @subpackage e-Teacher
 * @since e-Teacher 2.0
 */
?>
            
<aside id="sidebar-course" class="course span4">
	<div class="widget-container">
		<h2>About This Topics</h2>
		<?php 
			$reating_terms = get_the_terms ($post->id, 'topics');
    		foreach ($reating_terms as $term){
        	echo $term->description;
    	}
    	?>
	</div><!--end widget-container-->  
	
	<div class="widget-container">
	<h2>Latest Courses</h2>
		<?php
		$args = array( 'post_type' => 'courses', 'posts_per_page' => 3 );
		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) : $loop->the_post();?>
    		<article class="summary">
    			<?php
				if(has_post_thumbnail()) :?>
					<a href="<?php the_permalink(); ?>"><span class="featured-image"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'courses' ); } ?></span></a>
					<?php endif;?>
    			<h1><small><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></small></h1>
    			<h2><small><?php the_time('F j, Y') ?></small></h2>
       		</article><!--end post-->
		<?php endwhile; ?>
	</div><!--end widget-container-->  
</aside><!--end sidebar-sermon-->  
