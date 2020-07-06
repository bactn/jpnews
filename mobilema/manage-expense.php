<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['mobilemauid'] == 0)) {
	header('location:logout.php');
} else {
	// code deletion
	if (isset($_GET['delid'])) {
		$rowid = intval($_GET['delid']);
		$query = mysqli_query($con, "delete from bantin_tbl where bantinID='$rowid'");
		if ($query) {
			echo "<script>alert('Record successfully deleted');</script>";
			echo "<script>window.location.href='manage-expense.php'</script>";
		} else {
			echo "<script>alert('Something went wrong. Please try again');</script>";
		}
	}

?>
	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>FAITO - 掲示板管理</title>
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
					<li class="active">掲示板一覧管理</li>
				</ol>
			</div>
			<!--/.row-->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">掲示板一覧</div>
						<div class="panel-body">
							<p style="font-size: 16px; color: red" align="center"> <?php if ($msg) {
																						echo $msg;
																					} ?> </p>
							<div class="col-md-12">

								<div class="table-responsive">
									<table class="table table-bordered mg-b-0">
										<thead>
											<tr>
												<th>No</th>
												<!-- <th>メニュー</th> -->
												<th>タイトル</th>
												<th>内容</th>
												<th>登録日付</th>
												<th>動作</th>
											</tr>
										</thead>
										<?php
										$userid = $_SESSION['mobilemauid'];
										$ret = mysqli_query($con, "select * from bantin_tbl ORDER BY insertDate DESC");
										$cnt = 1;
										while ($row = mysqli_fetch_array($ret)) {
										?>
											<tbody>
												<tr>
													<td><?php echo $cnt; ?></td>
													<!-- <td>ニュース</td> -->
													<td><?php echo substr($row['tieude'],0,10); ?></td>
													<td><?php echo substr($row['noidung'],0,50); ?></td>
													<td><?php echo $row['insertDate']; ?></td>
													<td>
														<a href="add-expense.php?editid=<?php echo $row['bantinID']; ?>">編集</a> -
														<a href="manage-expense.php?delid=<?php echo $row['bantinID']; ?>">削除</a>
													</td>
												</tr>
											<?php $cnt = $cnt + 1;
										} ?>
											</tbody>
									</table>
								</div>
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
<?php }  ?>