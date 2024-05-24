<?php

	add_action('acf/init', 'lb_register_hero');
	function lb_register_hero()
	{

	    // check function exists.
	    if( function_exists('acf_register_block_type') )
	    {

	    	$title 					= __('Hero', 'layback');
	    	$description 			= __('Hero block', 'layback');
	    	$tags 					=	array('Hero');
	    	$align 					= array('wide', 'full');
	    	$render 				= 'hero_block_render_callback';

	        // register a testimonial block.
	        acf_register_block_type(array(
	            'name'              => basename(__DIR__),
				'title'             => $title,
				'description'       => $description,
				'keywords'			=> $tags,
				'icon'              => '',
				'category'          => 'layback',
//					'post_types' 		=> array('post', 'page'),
				'supports' 			=> array(
					'mode'			=> 'auto',
					'align'			=> $align,
				),
				'render_callback'   => $render,
				'enqueue_style' 	=> get_stylesheet_directory_uri() . '/partials/blocks/' . basename(__DIR__) . '/style.css',
				'enqueue_script' 	=> get_stylesheet_directory_uri() . '/partials/blocks/' . basename(__DIR__) . '/script.js',
	        ));
	    }
	}

	function hero_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 )
	{

		/* Add all variables in the top
		-------------------------------------------------- */

		$block_name			= substr($block['name'], 4);
		$block_id 			= $block['id'];
		$block_title 		= strtolower(str_replace(" ","_",$block['title']));
		$block_filename 	= pathinfo(__FILE__, PATHINFO_FILENAME);
		
		if( !empty($block['align']) ) {
			$block_align 	= $block['align'];
		}

	  ?>
	
	    <div id="<?php echo $block_id; ?>" class="lb-block <?php if( !empty($block_align) ) { echo 'align-' . $block_align; } ?> block-<?php echo $block_name; ?>">
			<?php 
				if( have_rows('slideshow') ) :
					while ( have_rows('slideshow') ) : the_row();
						$img_video		= get_sub_field('img_video');
						$mp4 			= get_sub_field('mp4');
						$webm 			= get_sub_field('webm');
						$img 			= get_sub_field('img');
						$title 			= get_sub_field('title');
						$description 	= get_sub_field('description');
						$link 			= get_sub_field('link');
						?>

						<div class="slide">
							<?php 
								if($img_video === 'video')
								{
									?>
										<video autoplay loop muted>
											<?php if($mp4) : ?>
												<source src="<?php echo $mp4['url']; ?>" type="video/mp4">
											<?php endif; ?>

											<?php if($webm) : ?>
												<source src="<?php echo $webm['url']; ?>" type="video/webm">
											<?php endif; ?>

											Your browser does not support the video tag.
										</video>
									<?php 
								}

								if($img_video === 'img')
								{
									$srcset = wp_get_attachment_image_srcset($img['ID']);
									?>
										<img src="<?php echo $img['url']; ?>" srcset="<?php echo $srcset; ?>">
									<?php 
								}
							?>
								
								<div class="inner">			
									<h1><?php echo $title; ?></h1>
									<p><?php echo $description; ?></p>
									<?php if($link) : ?>
										<a href="<?php echo $link['url']; ?>" class="btn"><?php echo $link['title']; ?></a>
									<?php endif; ?>
								</div>

								<a href="#hero-scroll" class="scroll-down">
									<div class="mouse">
										<div class="scroller"></div>
									</div>				
								</a>
							</div>
						<?php 
					endwhile;
				endif; 
			?>				
	    </div>
    
    <?php }