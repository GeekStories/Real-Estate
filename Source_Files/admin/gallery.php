<?php 
    session_start();

    if(isset($_SESSION['_name'])){
        require "../config.php";

        $result = $mysqli->query("SELECT _permissions FROM users WHERE _id=".$_SESSION['_id']."");
        $row = $result->fetch_assoc();

        //If we don't have admin login permissions, then move away
        if($row['_permissions'][2] !== "1"){
            header("Location: ../index.php");
            exit();
        }
    } else {
        header("Location: ../../index.php");
        exit();
    }
?>

<html>
    <head>
        <title>Real-Estate</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> 
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col">
                    <a class='btn btn-light' href='controlpanel.php'>Back</a>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <h1>Property Gallery</h1>
                    <h6>...click on an image to remove it...</h6>
                    <div class="d-flex flex-wrap align-content-center">
                        <?php 
                            $id = $_GET['property'];

                            $result = $mysqli->query("SELECT * FROM properties WHERE _id=".$id."");
                            $row = mysqli_fetch_assoc($result);

                            $path = "../properties/" . $row['unique_id'] . "/";

                            $value = "";
                            $files = array_slice(scandir($path, ), 2); //Removes the first two elements which are '.' and '..', both would throw an error later on;
                    
                            if(count($files) == 0)
                                echo "No images uploaded!";
                            
                            for($x=0;$x<count($files);$x++){ 
                                $value.= "<form action='include/remove-image.inc.php' method='post' style='float:left;p-0'> 
                                            <button type='submit' class='btn p-1' name='image' value='../".($path . $files[$x])."'> 
                                                <img src='".$path . $files[$x]."' style='width:150px;border-radius:10%;'/>
                                            </button>
                                            <input name='property' value='".$row['_id']."' hidden />
                                        </form>";
                            }

                            echo $value;
                        ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col" align=center>
                    <form action='include/upload-pictures.inc.php' method='post' enctype="multipart/form-data">
                        <div class='form-group w-25 p-2' style='border:1px solid black;'>
                            Select image(s) to upload: <br />
                            <input class='fileinput' type="file" name="upload[]" id="fileToUpload" multiple>
                            <input name='unique' value=<?php echo "'".$row['unique_id']."'" ?> hidden>

                            <script>
                                    //Warn the user they selected too many images
                                    $('.fileinput').change(function(){
                                        if(this.files.length>20){
                                            alert('Max. 20 images');
                                            this.value = '';
                                        }
                                    });

                                    // Prevent submission if limit is exceeded.
                                    $('form').submit(function(){
                                        if(this.files.length>20){
                                            alert('Max. 20 images');
                                            return false;
                                        }
                                    });
                                </script>
                        </div>

                        <div class='form-group w-25 p-2'>
                            <button class='btn btn-primary' type='submit' name='submit-property'>Upload</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>