<?php
/*
Template Name: Stock Inventory
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
            <h3>Stock Inventory</h3>
        </center>
        <form action="" method="POST">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6">
                    <div class="form-group space_down">
                        <input type="checkbox" name="apply_sub_group" class="apply" checked>
                        <select name="sub_group" id="sub_group">
                            <option value="0" selected>Select sub group</option>
                        </select> <label for="main_group"> : Sub Group</label>
                    </div>
                    <div class="form-group space_down">
                        <select name="quan" id="quan">
                            <option value=-1>select...</option>
                            <option value=0>All</option>
                            <option value=1>Equal</option>
                            <option value=2>More than or Equal</option>
                            <option value=3>less than or Equal</option>
                        </select> <label for="quan"> : Quan</label>
                    </div>
                    <div class="form-group space_down">
                        <input type="text" id="name" name="name" style="width: 170px;" class="">
                        <label for="name"> : Name</label>
                    </div>
                    <div class="form-group space_down">
                        <input type="text" id="textbox1" name="textbox1" style="width: 170px;" class="">
                        <label for="textbox1"></label>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6">
                    <div class="form-group space_down">
                        <input type="checkbox" name="apply_branch" class="apply" checked>
                        <select name="branch" id="branch">
                            <option value="0" selected>Select Branch</option>
                            <?php

                            for ($i = 1; $i < 51; $i++) {
                                echo '<option value ="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select> <label for="branch"> : Branch</label>
                    </div>


                    <div class="form-group space_down">
                        <input type="checkbox" name="apply_main_group" class="apply" checked>
                        <select onchange="load_subgroup()" name="main_group" id="main_group">
                            <option value="0" selected>Select group</option>
                            <?php
                            $stmt =  $mss_conn->prepare('SELECT * FROM Imcat');
                            $stmt->execute();
                            $brts = $stmt->fetchAll();
                            foreach ($brts as $b) {
                                echo '<option value ="' . $b['IMID'] . '">' . $b['IMname'] . '</option>';
                            }
                            ?>
                        </select> <label for="main_group"> : Main Group</label>
                    </div>

                    <div class="form-group space_down">
                        <input type="checkbox" name="apply_stock" class="apply" checked>
                        <select name="stock" id="stock">
                            <option value="0" selected>Select stock</option>
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
                        <input type="text" id="code" name="code" style="width: 170px;" class="">
                        <label for="stock"> : Code</label>
                    </div>
                    <div class="form-group space_down">
                        <button id="submit" onclick="load_inventory()">Submit</button>
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
                    <th>Code</th>
                    <th>Item Name</th>
                    <th>Quan</th>
                    <th>Purchase Price</th>
                    <th>Purchase Total</th>
                    <!--<th>Sale Price</th>
                    <th>Sale Total</th>-->
                    <th>Stock</th>
                </thead>
                <tbody id="rtbody"></tbody>
                <tr>
                    <td colspan="4"><span id="total_Q">0</span></td>
                    <td colspan="2"><span id="total_P">0</span></td>
                </tr>
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
        });
        //});
    </script>
</section>

<?php
add_action('wp_footer', function () { ?>
    <script>
        function load_subgroup() {
            (function($) {
                var mgroup = $('#main_group').val();
                $.ajax({
                    url: "<?php bloginfo('url'); ?>/wp-content/themes/mobisilk/fetchsubgroup.php",
                    method: "POST",
                    data: {
                        maingroup: mgroup,
                    },
                    success: function(response) {
                        $('#sub_group').html(response);
                    },
                    error: function(err) {
                        // alert(err.responseText);
                        console.log(err);
                    }
                });
            })(jQuery);
        }

        function load_inventory() {
            (function($) {
                $('#submit').val('Processing...');
                $('#submit').attr('disabled', 'disabled');
                var main_group = 0;
                var sub_group = 0;
                var stock = 0;
                var quan = $('#quan').val();
                var name = $('#name').val();
                var code = $('#code').val();
                var textbox1 = $('#textbox1').val();
                var branch = -1;
                if ($('#main_group').is(':disabled')) {
                    main_group = 0;
                } else {
                    main_group = $('#main_group').val();
                }
                if ($('#sub_group').is(':disabled')) {
                    sub_group = 0;
                } else {
                    sub_group = $('#sub_group').val();
                }
                if ($('#stock').is(':disabled')) {
                    stock = 0;
                } else {
                    stock = $('#stock').val();
                }
                if ($('#branch').is(':disabled')) {
                    branch = -1;
                } else {
                    branch = $('#branch').val();
                }
                $('#rtbody').html('');
                //alert(txtser + " " + searcht);
                $.ajax({
                    url: "<?php bloginfo('url'); ?>/wp-content/themes/mobisilk/fetchstock.php",
                    method: "POST",
                    data: {
                        main_group: main_group,
                        sub_group: sub_group,
                        stock: stock,
                        quan: quan,
                        name: name,
                        code: code,
                        textbox1: textbox1,
                        branch: branch
                    },
                    success: function(response) {
                        console.log(response);
                        rep = JSON.parse(response);
                        $('#rtbody').html(rep.rows);
                        $('#total_Q').text(rep.total_Q);
                        $('#total_P').text(rep.total_P);
                        $('#submit').val('Submit');
                        $('#submit').removeAttr('disabled');
                    },
                    error: function(err) {
                        //alert(err.responseText);
                        $('#rtbody').html('');
                        $('#total_Q').text(0);
                        $('#total_P').text(0);
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