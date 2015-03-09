<?php
global $mdg_post;

$posts_per_page  = 2;
$offset          = 0;
$next_offset     = $posts_per_page + $offset;
$post_type       = $mdg_post->post_type;
$category        = ( is_archive() ) ? get_query_var( 'cat' ) : '';
$post_query_args = array(
	'posts_per_page' => $posts_per_page,
	'offset'         => $offset,
	'cat'            => $category,
);
$posts_query = $mdg_post->get_posts( $post_query_args, true );
$posts       = $posts_query->get_posts();
$max_posts   = $posts_query->found_posts;
$count_posts = count( $posts );
?>

	<?php if ( ! empty( $posts ) ) { ?>
	<div class="news-wrap">
		<div id="load_posts_container">
			<?php $mdg_post->posts_template( $posts ); ?>
		</div> <!-- /.load_container -->
	</div> <!-- /.news-wrap -->
	<?php if ( $max_posts > $posts_per_page ) { ?>
	<div class="row">
		<a
		href="#"
		id="load_posts_button"
		class="btn btn-default load-posts-btn col-xs-12"
		data-postsperpage="<?php echo esc_attr( $posts_per_page ); ?>"
		data-offset="<?php echo esc_attr( $next_offset ); ?>"
		data-posttype="<?php echo esc_attr( $post_type ); ?>"
		data-maxposts="<?php echo esc_attr( $max_posts ); ?>"
		data-countposts="<?php echo esc_attr( $count_posts ); ?>"
		data-category="<?php echo esc_attr( $category ); ?>"
		>LOAD MORE POSTS</a>
	</div> <!-- /.row -->
	<?php } // if() ?>
	<?php } // if() ?>