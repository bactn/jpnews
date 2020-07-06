<!DOCTYPE html>
<html>

<body>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		Menu: <input type="text" name="fmenu">
		Title: <input type="text" name="ftieude">
		Content: <input type="text" name="fnoidung">
		<input type="submit">
	</form>
	<?php
	include ('dbconnection.php');
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// collect value of input field
		$menuID = $_POST['fmenu'];
		$tieude = $_POST['ftieude'];
		$noidung = $_POST['fnoidung'];
		$userID = 1;
		$date_now = date("Y-m-d");

		$servername = "";
		$username = "";
		$password = "";
		$dbname = "";
		// Parsing connection string
		foreach ($_SERVER as $key => $value) {
			if (strpos($key, "MYSQLconSTR_") !== 0) {
				continue;
			}

			$servername = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
			$dbname = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
			$username = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
			$password = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
		}

		$sql = "INSERT INTO menu_tbl (menuNo, tenVN, tenENG, tenJPN, insertDate) 
	VALUES (" . $menuID . "," . "'" . $tieude . "'" . ", " . "'" . $tieude . "'" . ", " . "'" . $noidung . "'" . "," . "'" . $date_now . "'" . ") ";
		if ($con->query($sql) === TRUE) {
			echo "created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $con->error;
		}

		$con->close();
	}
	?>
</body>

</html>