<?php
/*
Template Name: Sales Report
*/
//get_header( ); 
include_once('userheader.php');
include_once('auth.php');
include_once('dbconfig.php');
include_once('mssconfig.php');
?>
<!-- Primary header -->
<section id="client">
	<div class="container">
		<style>
			.space_down {
				margin-bottom: 10px;
			}

			#DGIT .selected {
				background-color: brown;
				color: #FFF;
			}

			label {
				color: white;
			}

			td {
				font-weight: bolder;
			}

			h3 {
				color: white;
			}

			h5 {
				color: white;
			}

			.table {
				background-color: white;
			}
		</style>
		<center>
			<h3>Sales Report</h3>
		</center>
		<form action="" method="POST">
			<div class="row">
				<div class="col-lg-4 col-md-4">
					<h5>Items</h5>
					<hr style="border: 1px solid black; width: 100%;">
					<div class="form-group space_down">
						<input type="checkbox" name="apply_main_category" class="apply">
						<select name="main_category" onchange="load_subcats()" id="main_category" disabled>
							<option value="">Select main category</option>
							<?php
							$stmt =  $mss_conn->prepare('SELECT * FROM maincat');
							$stmt->execute();
							$mcats_ = $stmt->fetchAll();
							foreach ($mcats_ as $b) {
								echo '<option value ="' . $b['Mcid'] . '">' . $b['Mcname'] . '</option>';
							}
							?>
						</select><label for="main_cateogry"> : Main category</label>
					</div>
					<div class="form-group space_down">
						<input type="checkbox" name="apply_sub_category" class="apply">
						<select name="sub_category" id="sub_category" onchange="load_DGIT()" disabled>
							<option value="">Select sub category</option>
						</select><label for="sub_cateogry"> : Sub category</label>
					</div>
					<div class="form-group space_down">
						<input type="text" name="txtser" onchange="load_DGIT()" style="width: 170px;" class="" id="txtser">
						<input type="radio" name="searcht" class="searcht" id="searcht" value="code"> <label for=""> : Code</label>
						<input type="radio" name="searcht" class="searcht" id="searcht" value="name" checked> <label for=""> : Name</label>
					</div>
					<a role="button" onclick="load_DGIT()">Clear Table</a>
					<div class="form-group space_down" style="max-height: 200px; overflow:auto;"><input type="hidden" id="DGIT_value">
						<table class="table table-bordered dgit" id="DGIT">
							<thead>
								<tr>
									<th>Ser</th>
									<th>Code</th>
									<th>Item</th>
									<th>ITID</th>
								</tr>
							</thead>
							<tbody id="tbody">
								<?php
								$stmt =  $mss_conn->prepare('SELECT Items.Itcode,Items.Itcode, Items.itname,Items.ItID FROM (Items LEFT JOIN Subcat ON (Items.itscat = Subcat.Scid) AND (Items.itmcat = Subcat.ScMccode)) LEFT JOIN maincat ON Items.itmcat = maincat.Mcid');
								$stmt->execute();
								$brts = $stmt->fetchAll();
								foreach ($brts as $b) {
									echo '<tr><td>' . $b['Itcode'] . '</td><td>' . $b['Itcode'] . '</td><td>' . $b['itname'] . '</td><td>' . $b['ItID'] . '</td></tr>';
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-lg-4 col-md-4">
					<div class="form-group space_down">
						<select name="sales_type" id="sales_type" required>
							<option value="0">مبيعات Sales</option>
							<option value="1">مرتجعات Sales return</option>
							<option value="2">مبيعات جملة Not used</option>
							<option value="3">مرتجعات جملة not used</option>
							<option value="4">مبيعات صالة Dine-In</option>
							<option selected value="5">ALL</option>
						</select> <label for="sales_type"> : Sales Type</label>
					</div>
					<div class="form-group space_down">
						<input type="checkbox" name="apply_employee" class="apply">
						<select name="employee" id="employee" disabled="disabled">
							<option value="">Select employee</option>
							<?php
							$stmt =  $mss_conn->prepare('SELECT * FROM workerT');
							$stmt->execute();
							$brts = $stmt->fetchAll();
							foreach ($brts as $b) {
								echo '<option value ="' . $b['WID'] . '">' . $b['Wname'] . '</option>';
							}
							?>
						</select> <label for="employee"> : Employee</label>
					</div>
					<div class="form-group space_down">
						<input type="text" name="rec_num" style="width: 150px;" class="" id="rec_num"> <label> : Rec Num</label>
					</div>
					<div class="form-group space_down">
						<input type="checkbox" name="apply_stock" class="apply">
						<select name="stock" id="stock" disabled>
							<option value="">Select Stock</option>
							<?php
							$stmt =  $mss_conn->prepare('SELECT * FROM stocks');
							$stmt->execute();
							$brts = $stmt->fetchAll();
							foreach ($brts as $b) {
								echo '<option value ="' . $b['StockID'] . '">' . $b['Stockname'] . '</option>';
							}
							?>
						</select> <label for="stock"> : Stock</label>
					</div>
					<div class="form-group space_down">
						<input type="checkbox" name="apply_table" class="apply">
						<select name="table" id="table" disabled>
							<option value="">Select Table</option>
						</select> <label for="table"> : Table</label>
					</div>
					<div class="form-group space_down">

						<select name="customer" id="customer">
							<option value="">Select Customer</option>
							<?php
							$stmt =  $mss_conn->prepare('SELECT * FROM customers');
							$stmt->execute();
							$brts = $stmt->fetchAll();
							foreach ($brts as $b) {
								echo '<option value ="' . $b['custid'] . '">' . $b['custname'] . '</option>';
							}
							?>
						</select> <label for="customer"> : Customer</label>
					</div>
					<input type="button" onclick="load_report()" name="Submit" id="submit" class="btn btn-md btn-danger" value="Submit">
				</div>
				<div class="col-lg-4 col-md-4">
					<div class="form-group space_down">
						<input type="checkbox" name="apply_branch" class="apply" checked>
						<select name="branch" onchange="load_users()" id="branch">
							<option value="0">Select Branch</option>
							<?php
							$stmt =  $mss_conn->prepare('SELECT * FROM BranT');
							$stmt->execute();
							$brts = $stmt->fetchAll();
							foreach ($brts as $b) {
								echo '<option value ="' . $b['Brnom'] . '">' . $b['BRname'] . '</option>';
							}
							?>
						</select> <label for="table"> : Branch</label>
					</div>
					<div class="form-group space_down">
						<input type="checkbox" name="apply_user" class="apply">
						<select name="user" id="user" disabled>
							<option value="">Select User</option>
						</select> <label for="user"> : User</label>
					</div>
					<div class="form-group space_down">
						<input type="checkbox" name="apply_hall" class="apply">
						<select name="hall" onchange="load_table()" id="hall" disabled>
							<option value="">Select Hall</option>
							<?php
							$stmt =  $mss_conn->prepare('SELECT * FROM HallT');
							$stmt->execute();
							$brts = $stmt->fetchAll();
							foreach ($brts as $b) {
								echo '<option value ="' . $b['HID'] . '">' . $b['Hname'] . '</option>';
							}
							?>
						</select> <label for="hall"> : Hall</label>
					</div>
					<div class="form-group space_down">
						<h6>Payment Type</h6>
						<input type="radio" name="ptype" id="ptype" class="ptype" value="credit"> <label for=""> : Credit</label>
						<input type="radio" name="ptype" id="ptype" class="ptype" value="cash"> <label for=""> : Cash</label>
						<input type="radio" name="ptype" id="ptype" class="ptype" value="visa"> <label for=""> : Visa</label>
						<input type="radio" name="ptype" id="ptype" class="ptype" checked value="all"> <label for=""> : All</label>
					</div>
					<div class="form-group space_down">
						<h6>في الفترة :</h6>
						<input type="radio" name="time_v" id="time_v" class="time_v" checked value="all"> <label for=""> : All</label><br>
						<input type="radio" name="time_v" id="time_v" class="time_v" value="time"> <label for=""> : Time</label><br>
						<input type="datetime-local" name="datefrom" disabled id="datefrom" class="space_down"> <label for="datefrom"> :From</label>
						<input type="datetime-local" name="dateto" id="dateto" disabled class=""> <label for="dateto"> :To</label>
					</div>
				</div>
			</div>
			<div class="row">
				<h5>Report</h5>
			</div>
		</form>
		<style>
			td {
				font-weight: bolder;
			}
		</style><br><br><br>
		<div class="col-lg-12 col-md-12 col-sm-12" style="overflow:auto;">
			<table class="table table-bordered" style="overflow: auto;">
				<thead>
					<th>ser</th>
					<th>Receipt Num</th>
					<th>Item Name</th>
					<th>Price</th>
					<th>Disc</th>
					<th>Sale Type</th>
					<th>Pay Type</th>
					<th>Customer</th>
					<th>Date</th>
					<th>User</th>
					<th>Stock</th>
					<th>Quan</th>
					<th>Hall</th>
					<th>Table</th>
					<th>Service</th>
					<th>Vat</th>

					<th>Unit Price</th>
				</thead>
				<tbody id="rtbody"></tbody>
			</table>
		</div>
	</div>
	<script>
		//	(function($) {
		jQuery(document).ready(function($) {

			$('.apply').change(function() {
				if ($(this).is(':checked')) {
					if ($(this).attr('name') == 'apply_main_category') {
						$('#main_category').removeAttr('disabled');
						//   $('#sub_category').removeAttr('disabled');
					}
					if ($(this).attr('name') == 'apply_sub_category') {
						$('#sub_category').removeAttr('disabled');
					}
					if ($(this).attr('name') == 'apply_employee') {
						$('#employee').removeAttr('disabled');
					}
					if ($(this).attr('name') == 'apply_stock') {
						$('#stock').removeAttr('disabled');
					}
					if ($(this).attr('name') == 'apply_table') {
						$('#table').removeAttr('disabled');
					}
					//  if ($(this).attr('name') == 'apply_customer') {
					//    $('#customer').removeAttr('disabled');
					//}
					if ($(this).attr('name') == 'apply_branch') {
						$('#branch').removeAttr('disabled');
					}
					if ($(this).attr('name') == 'apply_user') {
						$('#user').removeAttr('disabled');
					}
					if ($(this).attr('name') == 'apply_hall') {
						$('#hall').removeAttr('disabled');
					}
				} else {
					if ($(this).attr('name') == 'apply_employee') {
						$('#employee').attr('disabled', 'disabled');
					}
					if ($(this).attr('name') == 'apply_user') {
						$('#user').attr('disabled', 'disbaled');
					}
					if ($(this).attr('name') == 'apply_hall') {
						$('#hall').attr('disabled', 'disbaled');
					}
					if ($(this).attr('name') == 'apply_branch') {
						$('#branch').attr('disabled', 'disbaled');
					}
					//  if ($(this).attr('name') == 'apply_customer') {
					//    $('#customer').attr('disabled', 'disbaled');
					//}
					if ($(this).attr('name') == 'apply_table') {
						$('#table').attr('disabled', 'disbaled');
					}
					if ($(this).attr('name') == 'apply_stock') {
						$('#stock').attr('disabled', 'disbaled');
					}
					if ($(this).attr('name') == 'apply_sub_category') {
						$('#sub_category').attr('disabled', 'disbaled');
					}
					if ($(this).attr('name') == 'apply_main_category') {
						$('#main_category').attr('disabled', 'disbaled');
						// $('#sub_category').attr('disabled', 'disbaled');
					}
				}
			});

			$('.ptype').change(function() {
				if ($(this).is(':checked')) {
					if ($(this).val() != 'visa') {
						$('#customer').removeAttr('disabled');
					} else {
						$('#customer').attr('disabled', 'disbaled');
					}
				}
			});
			//time_v
			$('.time_v').change(function() {
				if ($(this).is(':checked')) {
					if ($(this).val() != 'all') {
						$('#datefrom').removeAttr('disabled');
						$('#dateto').removeAttr('disabled');
					} else {
						$('#datefrom').attr('disabled', 'disbaled');
						$('#dateto').attr('disabled', 'disbaled');
					}
				}
			});
		});
		//});
	</script>
</section>

<?php
add_action('wp_footer', function () { ?>
	<script>
		function load_users() {
			(function($) {
				var branch = $('#branch').val();
				$.ajax({
					url: "<?php bloginfo('url'); ?>/wp-content/themes/mobisilk/fetchusers.php ",
					method: "POST",
					data: {
						branch: branch,
					},
					success: function(response) {
						$('#user').html(response);
					},
					error: function(err) {
						// alert(err);
						conso.log(err);
					}
				});
			})(jQuery);
		}

		function load_subcats() {
			(function($) {
				var mcat = $('#main_category').val();
				$.ajax({
					url: "<?php bloginfo('url'); ?>/wp-content/themes/mobisilk/fetchsubcategory.php",
					method: "POST",
					data: {
						category: mcat,
					},
					success: function(response) {
						$('#sub_category').html(response);
						load_DGIT();
					},
					error: function(err) {
						// alert(err.responseText);
						console.log(err);
					}
				});
			})(jQuery);
		}

		function load_table() {
			(function($) {
				var hall = $('#hall').val();
				$.ajax({
					url: "<?php bloginfo('url'); ?>/wp-content/themes/mobisilk/fetchtable.php",
					method: "POST",
					data: {
						hall: hall,
					},
					success: function(response) {
						$('#table').html(response);
					},
					error: function(err) {
						//alert(err);
					}
				});
			})(jQuery);
		}

		function load_DGIT() {
			(function($) {
				var main_cat = -1;
				var sub_cat = -1;
				if ($('#main_category').is(':disabled')) {
					main_cat = -1;
				} else {
					main_cat = $('#main_category').val();
				}
				if ($('#sub_category').is(':disabled')) {
					sub_cat = -1;
				} else {
					sub_cat = $('#sub_category').val();
				}
				var txtser = $('#txtser').val();
				var searcht = $('.searcht:checked').val();
				//alert(txtser + " " + searcht);
				$.ajax({
					url: "<?php bloginfo('url'); ?>/wp-content/themes/mobisilk/fetchDGIT.php",
					method: "POST",
					data: {
						main_cat: main_cat,
						sub_cat: sub_cat,
						txtser: txtser,
						searcht: searcht
					},
					success: function(response) {
						$('#tbody').html(response);
						$('#DGIT_value').val('');
						hide_columns();
						rebind();
					},
					error: function(err) {
						alert(err.responseText);
						//console.log(err);
					}
				});
			})(jQuery);
		}

		function load_report() {
			(function($) {
				$('#submit').val('Processing...');
				$('#submit').attr('disabled', 'disabled');
				var main_cat = -1;
				var sub_cat = -1;
				var sales_type = $('#sales_type').val();
				var customer = $('#customer').val();
				var hall = -1;
				var user = -1;
				var table = -1;
				var stock = -1;
				var employee = -1;
				var branch = 0;
				if ($('#hall').is(':disabled')) {
					hall = -1;
				} else {
					hall = $('#hall').val();
				}
				if ($('#user').is(':disabled')) {
					user = -1;
				} else {
					user = $('#user').val();
				}
				if ($('#table').is(':disabled')) {
					table = -1;
				} else {
					table = $('#table').val();
				}

				if ($('#main_category').is(':disabled')) {
					main_cat = -1;
				} else {
					main_cat = $('#main_category').val();
				}
				if ($('#sub_category').is(':disabled')) {
					sub_cat = -1;
				} else {
					sub_cat = $('#sub_category').val();
				}
				if ($('#stock').is(':disabled')) {
					stock = -1;
				} else {
					stock = $('#stock').val();
				}
				if ($('#employee').is(':disabled')) {
					employee = -1;
				} else {
					employee = $('#employee').val();
				}
				if ($('#branch').is(':disabled')) {
					branch = 0;
				} else {
					branch = $('#branch').val();
				}
				// var txtser = $('#txtser').val();
				var searcht = $('.searcht:checked').val();
				var time_v = $('.time_v:checked').val();
				var ptype = $('.ptype:checked').val();

				var datefrom = '';
				var dateto = '';
				if (time_v == 'time') {
					datefrom = $('#datefrom').val();
					dateto = $('#dateto').val();
				}
				var DGIT_value = $('#DGIT_value').val();
				var recnum = $('#rec_num').val();
				$('#rtbody').html('');
				//alert(txtser + " " + searcht);
				$.ajax({
					url: "<?php bloginfo('url'); ?>/wp-content/themes/mobisilk/fetchreport.php",
					method: "POST",
					data: {
						salestype: sales_type,
						ptype: ptype,
						customer: customer,
						hall: hall,
						user: user,
						table: table,
						time_v: time_v,
						main_category: main_cat,
						sub_category: sub_cat,
						DGIT_value: DGIT_value,
						stock: stock,
						recnum: recnum,
						employee: employee,
						branch: branch,
						datefrom: datefrom,
						dateto: dateto
					},
					success: function(response) {
						//console.log(response);
						$('#rtbody').html(response);
						$('#submit').val('Submit');
						$('#submit').removeAttr('disabled');
					},
					error: function(err) {
						//alert(err.responseText);
						$('#rtbody').html('');
						$('#submit').val('Submit');
						$('#submit').removeAttr('disabled');
						//console.log(err);
					}
				});
			})(jQuery);
		}

		function hide_columns() {
			(function($) {
				$('#DGIT tr > *:nth-child(1)').hide();
				$('#DGIT tr > *:nth-child(4)').hide();
			})(jQuery);
		}
		hide_columns();
		(function($) {
			$("#DGIT tr").click(function() {
				$(this).addClass('selected').siblings().removeClass('selected');
				var value = $(this).find('td:nth-child(4)').html();
				// alert(value);
				$('#DGIT_value').val(value);
				load_report();
			});
		})(jQuery);

		function rebind() {
			(function($) {
				$("#DGIT tr").click(function() {
					$(this).addClass('selected').siblings().removeClass('selected');
					var value = $(this).find('td:nth-child(4)').html();
					// alert(value);
					$('#DGIT_value').val(value);
					load_report();
				});
			})(jQuery);
		}
		/*
		$('.ok').on('click', function(e) {
		alert($("#table tr.selected td:nth-child(4)").html());
		});*/
	</script>
<?php });
get_footer(); ?>