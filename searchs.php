<?php
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_GET["x"], false);
$keys = "";
if(isset($_GET["keys"])){
       $types = $_GET["keys"];
} 
// Kết nối CSDL và lưu vào biến kết nối
// Các tham số gồm:
// - localhost: là tên server, thường mặc định là localhost luôn
// - root: là tên đăng nhập vào database
// - vertrigo: là mật khẩu đăng nhập vào database
// - demo: Là database sẽ xử lý

$conn = mysqli_connect('fdb19.biz.nf', '3436937_mobilemysql', 'pmt20102018', '3436937_mobilemysql') or die ('Không thể kết nối tới database');
 
// Câu truy vấn
$sql = 'SELECT * FROM bantin_tbl where tieude like "%'.$keys.'%"';

 
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
?>
