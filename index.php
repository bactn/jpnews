<!DOCTYPE html>
<html>

<head>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css">
   <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
   <section class="hero">
      <div class="hero-body">
         <div class="container">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
               <div class="field">
                  <label class="label">メニュー：</label>
                  <label class="checkbox">
                     <input type="checkbox">
                     ホーム
                  </label>
                  <label class="checkbox">
                     <input type="checkbox">
                     ニュース
                  </label>
                  <label class="checkbox">
                     <input type="checkbox">
                     生活
                  </label>
                  <label class="checkbox">
                     <input type="checkbox">
                     学習
                  </label>
                  <label class="checkbox">
                     <input type="checkbox">
                     お仕事
                  </label>
               </div>
               <div>
                  <label class="label">タイトル：</label>
                  <input class="input is-primary" name="ftieude" type="text" placeholder="タイトルを入力して下さい。">
               </div>
               <div>
                  <label class="label">内容：</label>
                  <textarea class="textarea is-primary" name="fnoidung" placeholder="内容を入力して下さい。"></textarea>
               </div>
               <label class="label"></label>
               <input type="file" name="fileToUpload" id="fileToUpload">
               <label class="label"></label>
               <div>
                  <button class="button" type="submit">完了</button>
               </div>
            </form>
            <?php
            $imageName = 'test';
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
               $imageName = basename($_FILES['fileToUpload']['name']);
               $accesskey = "eogTzrMdKpT4I+7P/xk0HrPpGrrEE8VTHVh8QJk49+u5g5krLzkjH4kt4NHfZir2NjU3vCAj9czKX/EcEtlLKQ==";
               $storageAccount = 'jpnews';
               $filetoUpload = $_FILES['fileToUpload']['tmp_name'];
               $containerName = 'public-contents';
               $blobName = basename($_FILES["fileToUpload"]["name"]);

               $destinationURL = "https://$storageAccount.blob.core.windows.net/$containerName/$blobName";
               uploadBlob($filetoUpload, $storageAccount, $containerName, $blobName, $destinationURL, $accesskey);
            }


            function uploadBlob($filetoUpload, $storageAccount, $containerName, $blobName, $destinationURL, $accesskey)
            {

               $currentDate = gmdate("D, d M Y H:i:s T", time());
               $handle = fopen($filetoUpload, 'r');
               $fileLen = filesize($filetoUpload);
               echo "file size:" . $fileLen;
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

               echo ('Error<br/>');
               print_r(curl_error($ch));
               // upload image

               // collect value of input field
               $menuID = 1;
               $tieude = $_POST['ftieude'];
               $noidung = $_POST['fnoidung'];
               $userID = 1;
               $servername = "127.0.0.1:50659";
               $username = "root";
               $password = "root";
               $dbname = "localdb";
               $date_now = date("Y-m-d h:i:s");

               $dsn = 'mysql:dbname=localdb;host=127.0.0.1:50659;charset=utf8';
               $user = 'root';
               $password = 'root';
               
               try {
                   $dbh = new PDO($dsn, $user, $password);
               } catch (PDOException $e) {
                   echo 'Connection failed: ' . $e->getMessage();
                   exit;
               }
               // Create connection
               $conn = new mysqli($servername, $username, $password, $dbname);
               // Check connection
               if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
               }

               $sql = "INSERT INTO bantin_tbl (menuID, tieude, noidung, userID, insertDate, urlImage)
                   VALUES (" . $menuID . "," . "'" . $tieude . "'" . ", " . "'" . $noidung . "'" . ", " . "'" . $userID . "'" . "," . "'" . $date_now . "'" . "," . "'" . $imageName . "'" . ") ";

               if ($conn->query($sql) === TRUE) {
                  printf("New Record has id %d.\n", $conn->insert_id);
                  echo "created successfully:";
               } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
               }

               $conn->close();
               curl_close($ch);
            }
            ?>
         </div>
      </div>
   </section>
</body>

</html>