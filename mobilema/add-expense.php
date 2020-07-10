<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['mobilemauid'] == 0)) {
    header('location:logout.php');
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $accesskey = "X78SYPbFTckbY7jcu/Kxr4dkXGzTxLTlbwJuNY0a1ANypFpK9kNZySpGPqzXDBDnxle1vfWBY3y7X2Ivoq4jWQ==";
        $storageAccount = 'faitostorage';
        $filetoUpload = $_FILES['fileToUpload']['tmp_name'];
        $containerName = 'public-contents';
        $blobName = basename($_FILES["fileToUpload"]["name"]);
        echo "blobname: " .$blobName;

        $destinationURL = "";
        if ($blobName != "") {
           $destinationURL = "https://$storageAccount.blob.core.windows.net/$containerName/$blobName";
           // Upload URL   
           uploadBlob($filetoUpload, $storageAccount, $containerName, $blobName, $destinationURL, $accesskey);
        }

        // Update DB
        updateDB($destinationURL, $con);
        $con->close();
    }
}



function uploadBlob($filetoUpload, $storageAccount, $containerName, $blobName, $destinationURL, $accesskey)
{
    $currentDate = gmdate("D, d M Y H:i:s T", time());
    $handle = fopen($filetoUpload, 'r');
    $fileLen = filesize($filetoUpload);
    $headerResource = "x-ms-blob-cache-control:max-age=3600\nx-ms-blob-type:BlockBlob\nx-ms-date:$currentDate\nx-ms-version:2015-12-11";
    $urlResource = "/$storageAccount/$containerName/$blobName";

    $arraysign = array();
    $arraysign[] = 'PUT';               /*HTTP Verb*/
    $arraysign[] = '';                  /*Content-Encoding*/
    $arraysign[] = '';                  /*Content-Language*/
    $arraysign[] = $fileLen;            /*Content-Length (include value when zero)*/
    $arraysign[] = '';                  /*Content-MD5*/
    $arraysign[] = 'image/png';         /*Content-Type*/
    $arraysign[] = '';                  /*Date*/
    $arraysign[] = '';                  /*If-Modified-Since */
    $arraysign[] = '';                  /*If-Match*/
    $arraysign[] = '';                  /*If-None-Match*/
    $arraysign[] = '';                  /*If-Unmodified-Since*/
    $arraysign[] = '';                  /*Range*/
    $arraysign[] = $headerResource;     /*CanonicalizedHeaders*/
    $arraysign[] = $urlResource;        /*CanonicalizedResource*/

    $str2sign = implode("\n", $arraysign);

    $sig = base64_encode(hash_hmac('sha256', urldecode(utf8_encode($str2sign)), base64_decode($accesskey), true));
    $authHeader = "SharedKey $storageAccount:$sig";

    $headers = [
        'Authorization: ' . $authHeader,
        'x-ms-blob-cache-control: max-age=3600',
        'x-ms-blob-type: BlockBlob',
        'x-ms-date: ' . $currentDate,
        'x-ms-version: 2015-12-11',
        'Content-Type: image/png',
        'Content-Length: ' . $fileLen
    ];

    $ch = curl_init($destinationURL);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_INFILE, $handle);
    curl_setopt($ch, CURLOPT_INFILESIZE, $fileLen);
    curl_setopt($ch, CURLOPT_UPLOAD, true);
    $result = curl_exec($ch);

    echo ('Result<br/>');
    print_r($result);

    // echo ('Error<br/>');
    print_r(curl_error($ch));
    // upload image
    curl_close($ch);
}

function updateDB($destinationURL, $con)
{
    $userid = $_SESSION['mobilemauid'];
    $selectedCatID = $_POST['categoryOption'];
    $date_now = date("Y-m-d H:i:s");
    $item = $_POST['item'];
    $costitem = $_POST['costitem'];

    $query = mysqli_query($con, "insert into bantin_tbl(menuID,tieude,noidung,userID,insertDate,urlImage) value('$selectedCatID','$item','$costitem','$userid','$date_now','$destinationURL')");
    if ($query) {
        echo "<script>alert('Expense has been added');</script>";
        echo "<script>window.location.href='manage-expense.php'</script>";
    } else {
        echo $query;
        echo "<script>alert('Something went wrong. Please try again');</script>";
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

                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                <!-- メニュー-->
                                <div class="form-group">
                                    <label>メニュー</label>
                                    <select class="form-control" type="option" value="" name="categoryOption" required="true">
                                        <option value="1">ニュース</option>
                                        <option value="2">生活</option>
                                        <option value="3">学習</option>
                                        <option value="4">お仕事</option>
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
                                    <textarea class="form-control" cols="50" rows="24" value="" required="true" name="costitem" placeholder="内容を入力して下さい。"></textarea>
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