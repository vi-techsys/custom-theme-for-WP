<?php
$msg = "";
/*
Template Name: User Login Form
*/
include_once('userheader.php');
if ($$_SESSION['logged_in'] == 'true') {
	if ($_SESSION['access'] = 1) {
		echo '<script>location.href="https://mobisilk.com/admin-dashboard/";</script>';
	} else {
		echo '<script>location.href="https://mobisilk.com/dashboard/";</script>';
	}
}
//get_header( ); 
?>

<!-- Primary header -->
<section id="client">
	<div class="container">
		<?php
		if (!empty($_POST["username"])) {
			include_once('dbconfig.php');
			//check username and email
			$psw = addslashes($_POST['password']);
			$pdo_statement = $pdo_conn->prepare("SELECT * FROM wpd3_software_users where email ='" . addslashes($_POST['username']) . "' or username = '" . addslashes($_POST['username']) . "' and password = '" . md5($psw) . "'");
			$pdo_statement->execute();
			$result = $pdo_statement->fetchAll();
			if (!empty($result)) {
				$date_exp = new DateTime($result[0]['expiration']);
				$now = new DateTime();
				$approve = $result[0]['approved'];
				$access = $result[0]['access'];
				if ($approve == 1) {
					if ($now < $date_exp || $access == 1) {
						$_SESSION['email'] = $result[0]['email'];
						$_SESSION['id'] = $result[0]['user_id'];
						$_SESSION['username'] = $result[0]['username'];
						if ($access == 1) {
							$_SESSION['logged_in'] = 'true';
							$_SESSION['access'] = 1;
							echo '<script>location.href="https://mobisilk.com/admin-dashboard/";</script>';
						} else {
							$_SESSION['logged_in'] = 'true';
							$_SESSION['access'] = 0;
							echo '<script>location.href="https://mobisilk.com/dashboard/";</script>';
						}
					} else {
						$msg = '<center><h5 style ="color:red;">Subscription is expired</h5></center>';
					}
				} else {
					$msg = '<center><h5 style ="color:red;">Account not authorized</h5></center>';
				}
			} else {
				$msg = '<center><h5 style ="color:red;">Login failed</h5></center>';
			}
		}
		?><div style="margin-top:50px;"></div>
		<div class="row">
			<center>
				<h2 style="margin-top:10px; color:blue;">MobisilK</h2>
				<center>
					<h3 style="margin-top:20px;">Login</h3>
					<?php echo $msg; ?>
				</center>
				<div style="margin:auto;" class="col-lg-5 col-md-10 col-sm-12">

					<form id="register_form" action="" method="post">
						<div class="input-group mb-3">
							<input type="text" name="username" class="form-control field-subscribe" value="" placeholder="Enter Your Username" required="">
						</div>
						<div class="input-group mb-3">
							<input type="password" name="password" class="form-control field-subscribe" placeholder="Enter Your Password" required="">
						</div>
						<p class="mb-0"><input value="Submit" name="add_user" type="submit" class="btn w-100"></p>
					</form>
				</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>