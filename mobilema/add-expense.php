<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['mobilemauid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $userid = $_SESSION['mobilemauid'];
        $dateexpense = $_POST['dateexpense'];
        $selectedCatID = $_POST['categoryOption'];
        $date_now = date("Y-m-d");
        $item = $_POST['item'];
        $costitem = $_POST['costitem'];
        $desURL = 'Testtesttest';

        echo $queryStr;
        $query = mysqli_query($con, "insert into bantin_tbl(menuID,tieude,noidung,userID,insertDate,urlImage) value('$selectedCatID','$item','$costitem','$userid','$date_now','$desURL')");
        if ($query) {
            echo "<script>alert('Expense has been added');</script>";
            echo "<script>window.location.href='manage-expense.php'</script>";
        } else {
            echo $query;
            echo "<script>alert('Something went wrong. Please try again');</script>";
        }
    }
}
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>FAITO - 掲示板詳細登録</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="css/datepicker3.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">

        <!--Custom Font-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
        <!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
    </head>

    <body>
        <?php include_once('includes/header.php'); ?>
        <?php include_once('includes/sidebar.php'); ?>

        <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
            <div class="row">
                <ol class="breadcrumb">
                    <li><a href="#"> <em class="fa fa-home"></em>
                        </a></li>
                    <li class="active">掲示板登録</li>
                </ol>
            </div>
            <!--/.row-->

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">掲示板詳細登録</div>
                        <div class="panel-body">
                            <p style="font-size: 16px; color: red" align="center"> <?php if ($msg) {
                                                                                        echo $msg;
                                                                                    } ?> </p>
                            <div class="col-md-12">

                                <form role="form" method="post" action="">
                                    <!-- メニュー-->
                                    <div class="form-group">
                                        <label>メニュー</label>
                                        <select class="form-control" type="option" value="" name="categoryOption" required="true">
                                            <option value="1">ニュース</option>
                                            <option value="2">生活</option>
                                            <option value="3">学習</option>
                                            <option value="3">お仕事</option>
                                        </select>
                                    </div>
                                    <!-- タイトル -->
                                    <div class="form-group">
                                        <label>タイトル</label>
                                        <input type="text" class="form-control" name="item" value="" required="true" placeholder="タイトルを入力して下さい。">
                                    </div>
                                    <!-- 内容 -->
                                    <div class="form-group">
                                        <label>内容</label>
                                        <!-- input class="form-control" type="text" value="" required="true" name="costitem" -->
                                        <textarea class="form-control" value="" required="true" name="costitem" placeholder="内容を入力して下さい。"></textarea>
                                    </div>
                                    <!-- ファイアプローチ-->
                                    <div class="form-group">
                                        <label>画像アップロード</label>
                                        <input class="form-control" type="file" name="fileToUpload" id="fileToUpload" required="true">
                                    </div>
                                    <!-- 登録 -->
                                    <div class="form-group has-success">
                                        <button type="submit" class="btn btn-primary" name="submit">登録</button>
                                    </div>

                            </div>

                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.panel-->
            </div>
            <!-- /.col-->
            <?php include_once('includes/footer.php'); ?>
        </div>
        <!-- /.row -->
        </div>
        <!--/.main-->

        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/chart.min.js"></script>
        <script src="js/chart-data.js"></script>
        <script src="js/easypiechart.js"></script>
        <script src="js/easypiechart-data.js"></script>
        <script src="js/bootstrap-datepicker.js"></script>
        <script src="js/custom.js"></script>

    </body>

    </html>