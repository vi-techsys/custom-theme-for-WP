<?php
/*
Template Name: Admin Home
*/
//get_header( ); 
include_once('adminheader.php');
//include_once('auth.php');
if ($_SESSION['access'] != 1) {
	session_destroy();
	echo '<script>location.href="https://mobisilk.com/user-login/";</script>';
}
include_once('dbconfig.php'); ?>
<!-- Primary header -->
<section class="page-title valign parallax"></section>
<section id="client">
	<div class="container">
		<div class="col-lg-6">
			<style>
				h5 {
					color: white;
					border-radius: 3px;
				}
			</style>
			<div class="row">
				<p>
					<?php
					$num = 0;
					$pdo_statement = $pdo_conn->prepare("SELECT count(user_id) as num FROM wpd3_software_users where access =0");
					$pdo_statement->execute();
					$result = $pdo_statement->fetchAll();
					$num = $result[0]['num'];
					echo '<h5 class="bg-primary">All Users: ' . $num . '</h5>';
					?>
				</p>
			</div>
			<div class="row">
				<p>
					<?php
					$pdo_statement = $pdo_conn->prepare("SELECT count(user_id) as active FROM wpd3_software_users where approved =1 and access =0");
					$pdo_statement->execute();
					$result = $pdo_statement->fetchAll();
					$active = $result[0]['active'];
					echo '<h5 class="bg-success">Active Users: ' . $active . '</h5>';
					$ic = $num - $active;
					echo '<h5 class="bg-danger">Inactive Users: ' . $ic . '</h5>';
					?>
				</p>
			</div>
		</div>
	</div>
</section>


<?php get_footer(); ?>