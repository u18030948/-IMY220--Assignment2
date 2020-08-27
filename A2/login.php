<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Munashe Muganiwa">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form method='post' action='' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='fileToUpload' id='fileToUpload' /><br/>
									<input type='hidden' name='loginEmail' value='$email'/>
									<input type='hidden' name='loginPass' value='$pass'/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
									
								</div>
							  </form>";


							  
							  if(isset($_POST['submit']))
							  {

								
					$target_dir = "Gallery/";
					$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
					$filenam =  basename( $_FILES["fileToUpload"]["name"]);
					$useridvar = $row['user_id'];

					$uploadOk = 1;
					$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

					// Check if image file is a actual image or fake image
					if(isset($_POST["submit"])) {
					$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
					if($check !== false) {
						echo "File is an image - " . $check["mime"] . ".";
						$uploadOk = 1;
					} else {
						echo "File is not an image.";
						$uploadOk = 0;
					}
					}

					// Check if file already exists
				

					// Check file size
					if ($_FILES["fileToUpload"]["size"] > 1024*1024) {
					echo "Sorry, your file is too large.";
					$uploadOk = 0;
					}

					// Allow certain file formats
					if($imageFileType != "jpg" && $imageFileType != "jpeg") {
					echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
					$uploadOk = 0;
					}

					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0) {
					echo "Sorry, your file was not uploaded.";
					// if everything is ok, try to upload file
					} else {
					if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
						echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

						echo "$filenam";



					//inserting new values into the table

					$sql = "INSERT INTO tbgallery (user_id, filename)
					VALUES ('$useridvar' , '$filenam' )";

					$res = mysqli_query($mysqli, $sql) == TRUE;



					} else {
						echo "Sorry, there was an error uploading your file.";
					}
					}
					
							  }
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}

			$users = $row['user_id'];
			echo "<h1>Image Gallery </h1>";
			echo "<div class='row imageGallery'>";
			$query2 =  "SELECT distinct filename FROM tbgallery WHERE user_id = '$users'";
			$ires = $mysqli->query($query2);
			while($row2 = mysqli_fetch_array($ires)){
                $filenam = $row2["filename"];


                
                echo "<div class='col-3' style='background-image: url(Gallery/$filenam);'>";
                echo "</div>";
               

			}
			echo "</div>"
		?>
		

	</div>
</body>
</html>