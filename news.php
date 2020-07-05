<?php
header("Content-Type: application/json; charset=UTF-8");
include ('dbconnection.php');

$obj = json_decode($_GET["x"], false);
$type = 1;
if (isset($_GET["type"])) {
	$type = $_GET["type"];
}

// Câu truy vấn
$sql = 'SELECT mt.tenJPN as Title, bantin.tieude as Name, bantin.noidung as Details, bantin.insertDate as DateTime,
bantin.tieude as Location, bantin.urlImage as ImageUrl 
FROM bantin_tbl bantin 
INNER JOIN menu_tbl mt on mt.menuID = bantin.menuID where bantin.menuID=' . $type;

// Thực hiện câu truy vấn, hàm này truyền hai tham số vào là biến kết nối và câu truy vấn
$result = mysqli_query($conn, $sql);

// Nếu thực thi không được thì thông báo truy vấn bị sai
if (!$result) {
	die('Câu truy vấn bị sai');
}


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

// Sau khi thực thi xong thì ngắt kết nối database
mysqli_close($conn);
