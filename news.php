<?php
include ('dbconnection.php');
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_GET["x"], false);
$type = 0;
if (isset($_GET["type"])) {
	$type = $_GET["type"] - 1;
}
// Kết nối CSDL và lưu vào biến kết nối
// Các tham số gồm:
// - localhost: là tên server, thường mặc định là localhost luôn
// - root: là tên đăng nhập vào database
// - vertrigo: là mật khẩu đăng nhập vào database
// - demo: Là database sẽ xử lý

if ($type == 0) {
	$sql = 'SELECT mt.tenJPN as Title, bantin.tieude as Name, bantin.noidung as Details, DATE_FORMAT(bantin.insertDate,"%Y-%m-%d") as DateTime,
    bantin.tieude as Location, bantin.urlImage as ImageUrl 
    FROM bantin_tbl bantin 
    INNER JOIN menu_tbl mt on mt.menuID = bantin.menuID where bantin.menuID= 1 ORDER BY bantin.insertDate DESC LIMIT 2';

	$sql1 = 'SELECT mt.tenJPN as Title, bantin.tieude as Name, bantin.noidung as Details, DATE_FORMAT(bantin.insertDate,"%Y-%m-%d")as DateTime,
    bantin.tieude as Location, bantin.urlImage as ImageUrl 
    FROM bantin_tbl bantin 
    INNER JOIN menu_tbl mt on mt.menuID = bantin.menuID where bantin.menuID= 2 ORDER BY bantin.insertDate DESC LIMIT 2';
	$sql2 = 'SELECT mt.tenJPN as Title, bantin.tieude as Name, bantin.noidung as Details, DATE_FORMAT(bantin.insertDate,"%Y-%m-%d") as DateTime,
    bantin.tieude as Location, bantin.urlImage as ImageUrl 
    FROM bantin_tbl bantin 
    INNER JOIN menu_tbl mt on mt.menuID = bantin.menuID where bantin.menuID= 3 ORDER BY bantin.insertDate DESC LIMIT 2';
	$sql3 = 'SELECT mt.tenJPN as Title, bantin.tieude as Name, bantin.noidung as Details, DATE_FORMAT(bantin.insertDate,"%Y-%m-%d") as DateTime,
    bantin.tieude as Location, bantin.urlImage as ImageUrl 
    FROM bantin_tbl bantin 
    INNER JOIN menu_tbl mt on mt.menuID = bantin.menuID where bantin.menuID= 4 ORDER BY bantin.insertDate DESC LIMIT 2';
	// Thực hiện câu truy vấn, hàm này truyền hai tham số vào là biến kết nối và câu truy vấn
	$result = mysqli_query($con, $sql);
	$result1 = mysqli_query($con, $sql1);
	$result2 = mysqli_query($con, $sql2);
	$result3 = mysqli_query($con, $sql3);

	// Nếu thực thi không được thì thông báo truy vấn bị sai
	if (!$result) {
		die('Câu truy vấn bị sai');
	}


	//echo .$result;
	// Lặp qua kết quả và in ra ngoài màn hình
	// Vì các field trong database là id, name, phone, address nên
	// khi vardum mang sẽ có cấu trúc tương tự
	$a = array();

	while ($row = mysqli_fetch_assoc($result)) {
		array_push($a, $row);
	}
	while ($row = mysqli_fetch_assoc($result1)) {
		array_push($a, $row);
	}
	while ($row = mysqli_fetch_assoc($result2)) {
		array_push($a, $row);
	}
	while ($row = mysqli_fetch_assoc($result3)) {
		array_push($a, $row);
	}
	echo json_encode($a);

	// Xóa kết quả khỏi bộ nhớ
	mysqli_free_result($result);
} else {


	// Câu truy vấn
	$sql = 'SELECT mt.tenJPN as Title, bantin.tieude as Name, bantin.noidung as Details, DATE_FORMAT(bantin.insertDate,"%Y-%m-%d") as DateTime,
bantin.tieude as Location, bantin.urlImage as ImageUrl 
FROM bantin_tbl bantin 
INNER JOIN menu_tbl mt on mt.menuID = bantin.menuID where bantin.menuID='.$type .' ORDER BY bantin.insertDate DESC';

	// Thực hiện câu truy vấn, hàm này truyền hai tham số vào là biến kết nối và câu truy vấn
	$result = mysqli_query($con, $sql);

	// Nếu thực thi không được thì thông báo truy vấn bị sai
	if (!$result) {
		die('Câu truy vấn bị sai');
	}


	//echo .$result;
	// Lặp qua kết quả và in ra ngoài màn hình
	// Vì các field trong database là id, name, phone, address nên
	// khi vardum mang sẽ có cấu trúc tương tự
	$a = array();

	while ($row = mysqli_fetch_assoc($result)) {
		array_push($a, $row);
	}
	echo json_encode($a);

	// Xóa kết quả khỏi bộ nhớ
	mysqli_free_result($result);
}
// Sau khi thực thi xong thì ngắt kết nối database

mysqli_close($con);
?>

