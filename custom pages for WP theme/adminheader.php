<?php
session_start();
global $naxos_config; ?>
<?php $isFrontPage = Naxos_Theme::naxos_is_front_page(get_the_ID()); ?>
<!DOCTYPE html>
<html class="no-js <?php echo (is_admin_bar_showing() ? 'wp-bar' : ''); ?>" <?php language_attributes(); ?>>

<head>

	<!-- Meta -->
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<?php
	$logo_dark = !empty($naxos_config['logo-dark']['url']) ? $naxos_config['logo-dark']['url'] : get_template_directory_uri() . '/images/logo.png';
	$logo_light = !empty($naxos_config['logo-light']['url']) ? $naxos_config['logo-light']['url'] : get_template_directory_uri() . '/images/logo-white.png';
	$search_icon = $naxos_config['search-icon'] ? 'nav-search-icon' : '';
	?>

	<?php if ($naxos_config['preloader'] or $naxos_config === null) { ?>
		<?php if (($naxos_config['preloader-only-home'] and $isFrontPage) or !$naxos_config['preloader-only-home'] or $naxos_config == null) { ?>
			<!-- Loader -->
			<div class="page-loader">
				<div class="progress"></div>
			</div>
		<?php  } ?>
	<?php  } ?>

	<!-- Header -->
	<header id="top-page" class="header">

		<!-- Main menu -->
		<div id="mainNav" class="main-menu-area animated">
			<div class="container">
				<div class="row align-items-center">

					<div class="col-12 col-lg-2 d-flex justify-content-between align-items-center">

						<!-- Logo -->
						<div class="logo">

							<a class="navbar-brand navbar-brand1" href="<?php echo esc_url(home_url()); ?>">
								<img src="<?php echo esc_url($logo_light); ?>" alt="<?php bloginfo('name'); ?>" data-rjs="2" />
							</a>

							<a class="navbar-brand navbar-brand2" href="<?php echo esc_url(home_url()); ?>">
								<img src="<?php echo esc_url($logo_dark); ?>" alt="<?php bloginfo('name'); ?>" data-rjs="2" />
							</a>

						</div>

						<!-- Burger menu -->
						<div class="menu-bar d-lg-none">
							<span></span>
							<span></span>
							<span></span>
						</div>

					</div>

					<div class="op-mobile-menu col-lg-10 p-0 d-lg-flex align-items-center justify-content-end">

						<!-- Mobile menu -->
						<div class="m-menu-header d-flex justify-content-between align-items-center d-lg-none">

							<!-- Logo -->
							<a href="<?php echo esc_url(home_url()); ?>" class="logo">
								<img src="<?php echo esc_url($logo_dark); ?>" alt="<?php bloginfo('name'); ?>" data-rjs="2" />
							</a>

							<!-- Close button -->
							<span class="close-button"></span>
						</div>

						<!-- Items -->
						<?php //Naxos_Theme::naxos_main_menu( get_the_ID( ), 'nav-menu d-lg-flex flex-wrap list-unstyled justify-content-center ' . $search_icon ); 
						?>
						<ul id="menu-main-menu" class="nav-menu d-lg-flex flex-wrap list-unstyled justify-content-center ">
							<?php if ($_SESSION['logged_in'] == 'true' && $_SESSION['access'] == 1) { ?>
								<li id="menu-item-67" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-61 current_page_item menu-item-67 nav-item"><a href="https://mobisilk.com/admin-dashboard/" aria-current="page" class="nav-link js-scroll-trigger"><span>Home</span></a></li>
								<li id="menu-item-180" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-180 nav-item"><a href="https://mobisilk.com/registered-users/" class="nav-link js-scroll-trigger"><span>Users</span></a></li>
								<li id="menu-item-180" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-180 nav-item"><a href="https://mobisilk.com/my-profile/" class="nav-link js-scroll-trigger"><span>My Profile</span></a></li>
								<li id="menu-item-180" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-180 nav-item"><a href="https://mobisilk.com/log-out/" class="nav-link js-scroll-trigger"><span>Log Out</span></a></li>
							<?php } else {
								echo '<script>location.href="https://mobisilk.com/user-logout/";</script>';
							} ?>
						</ul>
					</div>

				</div>
			</div>
		</div>

	</header>

	<?php if ($naxos_config['search-icon'] or $naxos_config === null) { ?>
		<!-- Search wrapper -->
		<div class="search-wrapper">

			<!-- Search form -->
			<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url()); ?>">
				<input type="search" name="s" id="s" placeholder="<?php echo esc_attr__('Search Keyword', 'naxos'); ?>" class="searchbox-input" autocomplete="off" required />

				<span><?php esc_html_e('Input your search keywords and press Enter.', 'naxos'); ?></span>
			</form>

			<!-- Close button -->
			<div class="search-wrapper-close">
				<span class="search-close-btn"></span>
			</div>

		</div>
	<?php } ?>