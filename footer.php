<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Pentamint_WP_Theme
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

	</div><!-- #content -->

	<div id="top-footer" class="footer-wrapper">
		<div class="container">
			<div class="row">
				<!-- <div class="top-footer-nav col-md-9"> -->
					<?php
						wp_nav_menu( array(
							'theme_location'    => 'top-footer',
							'container_class'   => 'collapse navbar-collapse col-md-9',
							'container_id'      => 'top-footer-nav',
							'menu_class'        => 'nav navbar-nav',
							'menu_id'			=> 'top-footer-menu',
							'depth'             => 2,
							'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
							'walker'            => new wp_bootstrap_navwalker(),)
						);
					?>
				<!-- </div> -->
				<div class="top-footer-social-links col-md-3">
					<?php
						if(is_active_sidebar('top-footer-widget-1')){
							dynamic_sidebar('top-footer-widget-1');
						}
					?>
				</div>
			</div>
		</div>
	</div>

	<div id="main-footer" class="footer-wrapper">
		<div class="container">
			<footer id="footer-sidebar" class="row widget-area">
				<div id="footer-sidebar1" class="col-md-3">
					<?php
						if(is_active_sidebar('footer-sidebar-1')){
							dynamic_sidebar('footer-sidebar-1');
						}
					?>
				</div>
				<div id="footer-sidebar2" class="col-md-6">
					<?php
						if(is_active_sidebar('footer-sidebar-2')){
							dynamic_sidebar('footer-sidebar-2');
						}
					?>
				</div>
				<div id="footer-sidebar3" class="col-md-3">
					<?php
						if(is_active_sidebar('footer-sidebar-3')){
							dynamic_sidebar('footer-sidebar-3');
						}
					?>
				</div>
				<div id="footer-sidebar4">
					<?php
						if(is_active_sidebar('footer-sidebar-4')){
							dynamic_sidebar('footer-sidebar-4');
						}
					?>
				</div>
			</footer>
		</div><!-- .container -->
	</div><!-- .footer-wrapper -->

	<div id="bottom-footer" class="footer-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-9">
					<footer id="colophon" class="site-footer" role="contentinfo">
						<div class="site-info">
							All Rights Reserved © <?php echo date("Y");?> <span class="enfont">Spleet Co., Ltd.</span>
							<span class="sep"> | </span>
						    Developed by <a href="https://www.pentamint.com">Pentamint Co., Ltd.</a>.
						</div><!-- .site-info -->
					</footer><!-- #colophon -->
				</div><!-- .col-md-9 -->
				<div class="col-md-3">
					<!-- Default dropup button -->
					<div class="btn-group dropup">
						<button type="button" class="btn btn-tertiary btn-colophon-toggle" data-toggle="collapse" data-target="#colophon-brand-nav">
							Link Sites
						</button>
						<div class="collapse dropdown-menu" id="colophon-brand-nav">
							<!-- Dropdown menu links -->
							<?php
								wp_nav_menu( array(
									'theme_location'    => 'colophon',
									'container_class'   => 'collapse navbar-collapse',
									'container_id'      => 'colophon-brand-nav',
									'menu_class'        => 'nav navbar-nav',
									'menu_id'			=> 'colophon-brand-menu',
									'depth'             => 0,
									'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
									'walker'            => new wp_bootstrap_navwalker(),)
								);
							?>
						</div><!-- .dropdown-menu -->
					</div><!-- .btn-group -->
				</div><!-- .col-md-3 -->
			</div><!-- .row -->
		</div><!-- .container -->
	</div><!-- .colophon-wrapper -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
