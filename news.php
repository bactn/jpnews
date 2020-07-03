<?php
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_GET["x"], false);
$type = 1;
if(isset($_GET["type"])){
       $type = $_GET["type"];
} 
// Kết nối CSDL và lưu vào biến kết nối
// Các tham số gồm:
// - localhost: là tên server, thường mặc định là localhost luôn
// - root: là tên đăng nhập vào database
// - vertrigo: là mật khẩu đăng nhập vào database
// - demo: Là database sẽ xử lý
$servername = "";
	$username = "";
	$password = "";
	$dbname = "";
	// Parsing connnection string
	foreach ($_SERVER as $key => $value) {
	   if (strpos($key, "MYSQLCONNSTR_") !== 0) {
		  continue;
	   }

	   $servername = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
	   $dbname = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
	   $username = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
	   $password = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
  }
$conn = new mysqli($servername, $username, $password, $dbname);
 
// Câu truy vấn
$sql = 'SELECT mt.tenJPN as Title, bantin.tieude as Name, bantin.noidung as Details, bantin.insertDate as DateTime,
bantin.tieude as Location, bantin.urlImage as ImageUrl 
FROM bantin_tbl bantin 
INNER JOIN menu_tbl mt on mt.menuID = bantin.menuID where bantin.menuID='.$type;
 
// Thực hiện câu truy vấn, hàm này truyền hai tham số vào là biến kết nối và câu truy vấn
$result = mysqli_query($conn, $sql);
 
// Nếu thực thi không được thì thông báo truy vấn bị sai
if (!$result){
    die ('Câu truy vấn bị sai');
}

 
// Lặp qua kết quả và in ra ngoài màn hình
// Vì các field trong database là id, name, phone, address nên
// khi vardum mang sẽ có cấu trúc tương tự
$a=array();

while ($row = mysqli_fetch_assoc($result)){
  array_push($a,$row);

}
    echo json_encode($a);

// Xóa kết quả khỏi bộ nhớ
mysqli_free_result($result);
 
// Sau khi thực thi xong thì ngắt kết nối database
mysqli_close($conn);
