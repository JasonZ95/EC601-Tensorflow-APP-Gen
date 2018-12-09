<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://getbootstrap.com/favicon.ico">

    <title>Floating labels example for Bootstrap</title>
    <!-- Bootstrap core CSS -->
    <link href="Floating%20labels%20example%20for%20Bootstrap_files/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="Floating%20labels%20example%20for%20Bootstrap_files/floating-labels.css" rel="stylesheet">
  </head>

  <body background="freebg5.jpg">
    <form class="form-signin" enctype="multipart/form-data" method="post" name="upform">
      <div class="text-center mb-4">
        <img class="mb-4" src="Floating%20labels%20example%20for%20Bootstrap_files/googlecamerahero.svg" alt="" width="92" height="72">
        <h1 class="h3 mb-3 font-weight-normal">machine learning app generator</h1>
        <p>Please upload images with label in .zip format.<code> You will receive a apk package from email.</code> We suggest you to use the <a href="https://www.neurala.com/">tagging system</a> to locate the object <a href="https://www.neurala.com/"></a></p>
      </div>

      <div class="form-label-group">
        <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="" name="email">
        <label for="inputEmail">Email address</label>
      </div>

      <div class="form-label-group">
        <input name="upfile" type="file">
      </div>

      <input class="btn btn-lg btn-primary btn-block" type="submit" value="Send"></input>
      <p class="mt-5 mb-3 text-muted text-center">Provided by android app generator project</p>
    </form>
  	<!--
  	<form enctype="multipart/form-data" method="post" name="upform">  
      file upload:  
      <input name="upfile" type="file">  
      <input type="submit" value="Upload"><br>
    </form>  
	-->
    <?php
    //type list
    $uptypes=array(
        'application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed'
    );  
      
    $max_file_size=50000000;     //size limit(BYTE)  
    $destination_folder='/var/www/html/hub-master/examples/image_retraining/'; //upload destination
    // $imgpreview=1;      //whether generate preview img
    // $imgpreviewsize=1/2;    //preview size
	



    $email = $_POST['email'];
	$filename = $_FILES['upfile']['name'];
	// echo $email;
	// echo $filename;
	// $py = popen("php -f backend.php $email $filename &", 'r');
	// pclose($py);

	


	// $handle = popen("mymail.php","w");
	// $params = [
	//     'to' => 'xxx',
	//     'content' => 'xxx',
	//     'subject' => 'xxx',
	// ];
	// fwrite($handle, serialize($params));
	// fclose($handle);

	if ($_SERVER['REQUEST_METHOD'] == 'POST')  
    {  
        if (!is_uploaded_file($_FILES["upfile"]["tmp_name"]))   
        {  
             echo "<script language=javascript>
        		alert('file not exist!');</script>";
             exit;  
        }  
      
        $file = $_FILES["upfile"];  
        if($max_file_size < $file["size"])  
        {  
            echo "<script language=javascript>
        		alert('file is too big!');</script>"; 
            exit;  
        }  
      
        if(!in_array($file["type"], $uptypes))  
        {  
            echo "<script language=javascript>
        		alert('file type not support!');</script>";
            exit;  
        }  
      
        if(!file_exists($destination_folder))  
        {  
			if (!@mkdir($destination_folder,777,true))
			{
			    $error = error_get_last();
			    $emsg = $error['message'];
			    echo "<script language=javascript>
        		alert($emsg);</script>";
			}
            
        }  
      
        $filename=$file["tmp_name"];
        $image_size = getimagesize($filename);
        $pinfo=pathinfo($file["name"]);  
        $ftype=$pinfo['extension'];  
        $targetzip = $destination_folder.time().".".$ftype;
        
        if (file_exists($targetzip) && $overwrite != true)  
        {  
            echo "<script language=javascript>
        		alert('file name exist');</script>";
            exit;  
        }  
      
        if(move_uploaded_file ($filename, $targetzip))  
        {   
        	$zip = new ZipArchive;

	        $x = $zip->open($targetzip);  // open the zip file to extract
	        
	        if ($x === true)
	        {
	            $zip->extractTo($destination_folder); // place in the directory with same name  
	            $zip->close();

	            unlink($targetzip);
	        }

	    }
	    else
	    {
	       echo "<script language=javascript>
        		alert('There was a problem with the upload. Please try again.');</script>";
	    }


        // $pinfo=pathinfo($destination);  
        // $fname=$pinfo["basename"];  

        // echo "<font color=red>upload successfully</font><br>file name:  <font color=blue>".$destination_folder.$fname."</font><br>";  
        echo "<script language=javascript>
        	alert('Upload successfully');</script>";
        // echo "<br> size:".$file["size"]." bytes"; 

    }  
    ?>  
    </body>  
</html>  
