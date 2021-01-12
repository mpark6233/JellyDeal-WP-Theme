<?php
/**
 * The template for thank you page
 * 
 * @package Pentamint_WP_Theme
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>

<div id="primary" class="content-area" style="margin:0">
	<main id="main" class="site-main" style="margin:0">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

		<div class="container">
			<div class="wrapper">
				<div class="title-block">
					<span class="ty-title">서비스 신청이 성공적으로 완료되었습니다.</span>
				</div>
				<div class="copy-block">
					<span class="ty-copy">심사는 수일내로 빠르게 이루어지며 심사결과에 따라 추가정보가 필요한 경우 이메일이나 유선을 통해 연락을 드릴 수 있으니 참고 바랍니다. 감사합니다.</span>
				</div>
				<div class="btn-block">
					<a href="<?php bloginfo('url'); ?>/" class="ty-btn btn btn-primary100">홈으로 돌아가기</a>
				</div>
			</div>
		</div>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();