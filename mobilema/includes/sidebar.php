<?php
session_start();
error_reporting(0);
include ('includes/dbconnection.php');
?>


<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
	<div class="profile-sidebar">
		<div class="profile-userpic">
			<img src="" class="img-responsive" alt="">
		</div>
		<div class="profile-usertitle">
<?php
$uid = $_SESSION['mobilemauid'];
$ret = mysqli_query($con, "select FullName from tbluser where ID='$uid'");
$row = mysqli_fetch_array($ret);
$name = $row['FullName'];

?>
                <div class="profile-usertitle-name"><?php echo $name; ?></div>
			<div class="profile-usertitle-status">
				<span class="indicator label-success"></span>Online
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="divider"></div>

	<ul class="nav menu">
		<li class="active"><a href="dashboard.php"><em class="fa fa-dashboard">&nbsp;</em>Dashboard</a></li>
		<li class="parent ">
			<a data-toggle="collapse" href="#sub-item-1">
				<em class="fa fa-navicon">&nbsp;</em>
				掲示板管理
				<span
					data-toggle="collapse" href="#sub-item-1" class="icon pull-right">
					<em class="fa fa-plus"></em>
				</span>
			</a>
			<ul class="children collapse" id="sub-item-1">
				<li>
					<a class="" href="add-expense.php">
					<span class="fa fa-arrow-right">&nbsp;</span> 掲示板登録
					</a>
				</li>
				<li>
					<a class="" href="manage-expense.php">
					<span class="fa fa-arrow-right">&nbsp;</span>掲示板一覧
					</a>
				</li>
			</ul>
		</li>

		<li class="parent "><a data-toggle="collapse" href="#sub-item-2">
			<em class="fa fa-navicon">&nbsp;</em>
			掲示板レポート
			<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right">
				<em class="fa fa-plus"></em>
			</span>
		</a>
			<ul class="children collapse" id="sub-item-2">
				<li>
					<a class="" href="expense-datewise-reports.php">
						<span class="fa fa-arrow-right">&nbsp;</span>
						日毎
					</a>
				</li>
				<li>
					<a class="" href="expense-monthwise-reports.php">
						<span class="fa fa-arrow-right">&nbsp;</span>
						月毎
					</a>
				</li>
				<li>
					<a class="" href="expense-yearwise-reports.php">
						<span class="fa fa-arrow-right">&nbsp;</span>
						年毎
					</a>
				</li>

			</ul>
		</li>
		<li>
			<a href="user-profile.php">
			<em class="fa fa-user">&nbsp;</em>
			プロフィール
			</a>
		</li>
		<li>
			<a href="change-password.php">
			<em class="fa fa-clone">&nbsp;</em>
			パスワード変更
			</a>
		</li>
		<li>
			<a href="logout.php">
				<em class="fa fa-power-off">&nbsp;</em>
				ログアウト
			</a>
		</li>
	</ul>
</div>