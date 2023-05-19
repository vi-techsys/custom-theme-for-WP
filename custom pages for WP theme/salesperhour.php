<?php
/*
Template Name: Sales Per Hour
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
            <h3>Sales Per Hour</h3>
        </center>
        <form action="" method="POST">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6">
                    <div class="form-group space_down">
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
                        </select> <label for="branch"> : Branch</label>
                    </div>
                    <div class="form-group space_down">
                        <input type="date" name="date">
                        <label for="date"> : Day</label>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6">
                    <div class="form-group space_down">
                        <input type="checkbox" name="sales" class="sales" checked>
                        <label for="sales"> : Sales</label>
                    </div>
                    <div class="form-group space_down">
                        <input type="checkbox" name="dine_in" class="dine_in" checked>
                        <label for="sales"> : Dine In</label>
                    </div>
                </div>

                <div class="form-group space_down">
                    <button id="submit" onclick="load_report()">Submit</button>
                </div>
            </div>
            <div class="row">
                <h5>Report</h5>
            </div>
        </form>

        <div class="col-lg-12 col-md-12 col-sm-12" style="overflow:auto;">
            <table class="table table-bordered" style="overflow: auto;">
                <thead>
                    <th>Hour</th>
                    <th>Total</th>
                </thead>
                <tbody id="rtbody"></tbody>
            </table>

        </div>
    </div>
</section>

<?php
add_action('wp_footer', function () { ?>
    <script>
        function load_report() {
            (function($) {
                $('#submit').val('Processing...');
                $('#submit').attr('disabled', 'disabled');
                var sales_check = $('#sales').val();
                var dine_in_check = $('#dine_in').val();
                var branch = $('#branch').val();
                var date = $('date').val();
                console.log(sales_check + " " + dine_in_check + " " + branch + " " + date);
                $('#rtbody').html('');
                //alert(txtser + " " + searcht);
                $.ajax({
                    url: "<?php bloginfo('url'); ?>/wp-content/themes/mobisilk/fetchsalesperhour.php",
                    method: "POST",
                    data: {
                        sales_check: sales_check,
                        dine_in_check: dine_in_check,
                        branch: branch,
                        date: date
                    },
                    success: function(response) {
                        console.log(response);
                        rep = JSON.parse(response);
                        $('#rtbody').html(rep.rows);
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
    </script>
<?php });
get_footer(); ?>