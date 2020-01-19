<?php
 if($_SERVER['REQUEST_METHOD']=='POST'){
  	// echo $_SERVER["DOCUMENT_ROOT"];  // /home1/demonuts/public_html
	//including the database connection file
  	include_once("../../MySQLrose/secureprepared.php");
  	$db_connect =new mysqli(HOST,USER,PASSWORD,DB_NAME);
    $emparray = array();
  	//$_FILES['image']['name']   give original name from parameter where 'image' == parametername eg. city.jpg
  	//$_FILES['image']['tmp_name']  temporary system generated name
  
        $originalImgName= $_FILES['filename']['name'];
        $tempName= $_FILES['filename']['tmp_name'];
        $ImageSavefolder="images/";
        $url="http://www.redrosedreams.com/androidapp/images/".$originalImgName;
        
        if(move_uploaded_file($tempName,$ImageSavefolder.$originalImgName)){ 
            
                $insertusersql = "INSERT into image_upload (image_name) VALUES(?)";
            
            
                if($stmt = $db_connect->prepare(($insertusersql))){
                    $stmt->bind_param("s", $url);
                    $stmt->execute();
                    
                	$query= "SELECT * FROM image_upload WHERE image_name='$url'";
                    $result= mysqli_query($db_connect, $query);
	         
	                if(mysqli_num_rows($result) > 0){  
	                     while ($row = mysqli_fetch_assoc($result)) {
                                     $emparray[] = $row;
                                   }
                                   echo json_encode(array( "status" => "true","message" => "Successfully file added!" , "data" => $emparray) );
                                   
	                     }else{
	                     		echo json_encode(array( "status" => "false","message" => "Getting from DB Failed!") );
	                     }
			   
                }else{
                	echo json_encode(array( "status" => "false","message" => "Insert into DB Failed!") );
                }
        	//echo "moved to ".$url;
        }else{
        	echo json_encode(array( "status" => "false","message" => "Upload on Server Failed!") );
        }
  }
?>