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
         <p class="has-text-centered has-text-weight-bold title">FAITO</p>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
               <label class="label">メニュー：</label>
               <div class="select is-primary">
                  <select name="categoryOption">
                     <option value="1">ニュース</option>
                     <option value="2">生活</option>
                     <option value="3">学習</option>
                     <option value="4">お仕事</option>
                  </select>
               </div>

               <div class="mt-5">
                  <label class="label">タイトル：</label>
                  <input class="input is-primary" name="ftieude" type="text" placeholder="タイトルを入力して下さい。">
               </div>

               <div class="mt-5">
                  <label class="label">内容：</label>
                  <textarea class="textarea is-primary" name="fnoidung" placeholder="内容を入力して下さい。"></textarea>
               </div>

               <div class="mt-5">
                  <label class="label"></label>
                  <input type="file" name="fileToUpload" id="fileToUpload">
                  <label class="label"></label>
               </div>

               <div class="mt-5">
                  <button class="button" type="submit">完了</button>
               </div>
            </form>
            <?php

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
               // $imageName = basename($_FILES['fileToUpload']['name']);
               $accesskey = "eogTzrMdKpT4I+7P/xk0HrPpGrrEE8VTHVh8QJk49+u5g5krLzkjH4kt4NHfZir2NjU3vCAj9czKX/EcEtlLKQ==";
               $storageAccount = 'jpnewsstorage';
               $filetoUpload = $_FILES['fileToUpload']['tmp_name'];
               $containerName = 'public-contents';
               $blobName = basename($_FILES["fileToUpload"]["name"]);

               $destinationURL = "";
               if ($blobName != "") {
                  $destinationURL = "https://$storageAccount.blob.core.windows.net/$containerName/$blobName";
                  // Upload URL   
                  uploadBlob($filetoUpload, $storageAccount, $containerName, $blobName, $destinationURL, $accesskey);
               }

               // Update DB
               updateDB($destinationURL);
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

            function updateDB($destinationURL)
            {
               // collect value of input field
               $selectedCatID = $_POST['categoryOption'];
               //echo "cat select: " .$selectOption;
               $tieude = $_POST['ftieude'];
               $noidung = $_POST['fnoidung'];
               $userID = 1;
               $date_now = date("Y-m-d h:i:s");
               
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

               // Create connection
               $conn = new mysqli($servername, $username, $password, $dbname);
               // Check connection
               if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
               } else {
                  // echo "connection successful<br/>";
               }

               $sql = "INSERT INTO bantin_tbl (menuID, tieude, noidung, userID, insertDate, urlImage)
                   VALUES (" . $selectedCatID . "," . "'" . $tieude . "'" . ", " . "'" . $noidung . "'" . ", " . "'" . $userID . "'" . "," . "'" . $date_now . "'" . "," . "'" . $destinationURL . "'" . ") ";

               if ($conn->query($sql) === TRUE) {
                  // printf("New Record has id %d.\n", $conn->insert_id);
                  echo "created successfully";
               } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
               }

               $conn->close();
            }
            ?>
         </div>
      </div>
   </section>
</body>

</html>