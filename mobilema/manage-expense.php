<?php
header('Content-Type: text/html; charset=UTF-8');
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
			echo "<script>window.location.href='manage-expense.php'</script>";
		} else {
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

	<style> 
.tieude {
  white-space: nowrap; 
  width: 100px; 
  overflow: hidden;
  text-overflow: ellipsis; 
}

.noidung {
  white-space: nowrap; 
  width: 350px; 
  overflow: hidden;
  text-overflow: ellipsis; 
}
</style>
<script>
	function deleteId(deleteURL) {
		var confirmDialog = confirm('削除しますか。');
		if (confirmDialog == true) {
			document.location = deleteURL;
			return true;
		} else {
			alert('failed');
			return false;
		}
	}
</script>
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
												<th>メニュー</th>
												<th>タイトル</th>
												<th>内容</th>
												<th>登録日付</th>
												<th>動作</th>
											</tr>
										</thead>
										<?php
										$userid = $_SESSION['mobilemauid'];
										$ret = mysqli_query($con, 'select bantin.noidung as noidung, bantin.tieude as tieude,
										bantin.bantinID as bantinID, bantin.insertDate as insertDate, mt.tenJPN as menu from bantin_tbl bantin INNER JOIN menu_tbl mt on mt.menuID = bantin.menuID ORDER BY bantin.insertDate DESC');
										$cnt = 1;
										while ($row = mysqli_fetch_array($ret)) {
										?>
											<tbody>
												<tr>
													<td><?php echo $cnt; ?></td>
													<td><?php echo $row['menu']; ?></td>
													<td ><div class="tieude"><?php echo $row['tieude']; ?></td>
													<td> <div class="noidung"><?php echo $row['noidung']; ?></div></td>
													<td><?php echo $row['insertDate']; ?></td>
													<td>
														<a href="add-expense.php?editid=<?php echo $row['bantinID']; ?>">編集</a> - 
														<a onClick="javascript: return confirm('削除しますか。');" href='manage-expense.php?delid=<?php echo $row['bantinID']; ?>"'>削除</a>
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