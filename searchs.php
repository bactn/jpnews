<?php
include ('dbconnection.php');
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_GET["x"], false);
$keys = "";
if(isset($_GET["keys"])){
       $types = $_GET["keys"];
}
 
// Câu truy vấn
$sql = 'SELECT * FROM bantin_tbl where tieude like "%'.$keys.'%"';

 
// Thực hiện câu truy vấn, hàm này truyền hai tham số vào là biến kết nối và câu truy vấn
$result = mysqli_query($con, $sql);
 
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
?>
