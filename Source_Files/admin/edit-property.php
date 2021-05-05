<?php
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header("Location: ../index.php");
        exit();
    }

    require "../config.php";

    if(!$stmt = $mysqli->prepare("SELECT * FROM properties WHERE _id=?")){
        header("Location: controlpanel.php?error=sqlerror");
        exit();
    } else {
        $stmt->bind_param("i", $_POST['property']);
        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();
        if($result->num_rows === 0){
            header("Location: controlpanel.php?error=no-property-found");
            exit();
        } else {
            $row = $result->fetch_assoc();

            session_start();
            $_SESSION['house_id'] = $_POST['property'];

            $islandResult = $mysqli->query("SELECT _name FROM islands WHERE _id=".$row['island_id']."");
            $islandRow = $islandResult->fetch_assoc();
        }
    }
?>

<html>
    <head>
        <title>Real-Estate</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> 
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col" align='center'>
                    <a class='btn btn-light' role='button' href='controlpanel.php'>Back</a>
                    <h1>Edit Property Details</h1>
                    <form class='form-property' method='post' action='include/edit-property.inc.php'>
                        <div class="form-row w-50" style='border:1px solid black'>
                            <div class="form-group w-25 p-2">
                                <label style='float:left;'>Street Number</label>
                                <input class='form-control' type='number' name='number' placeholder='Street Number' value='<?php echo number_format( $row['st_number'] ) ?>' required>
                            </div>

                            <div class="form-group p-2 w-75">
                                <label style='float:left;'>Street Name</label>
                                <input class='form-control' type='text' name='name' placeholder='Street Name' value='<?php echo $row['st_name'] ?>' required>
                            </div>

                            <div class="form-group w-100 p-2">
                                <label style='float:left;'>Neighbourhood</label>
                                <input class='form-control p-2' type='text' name='area' placeholder='Neighbourhood' value='<?php echo $row['_area'] ?>' required>
                            </div>
                            
                            <div class="form-group w-75 p-2">
                                <label style='float:left;'>City</label>
                                <input class='form-control p-2' type='text' name='city' placeholder='City' value='<?php echo $row['_city'] ?>' required>
                            </div>
                            
                            <div class="form-group w-25 p-2">
                                <label style='float:left;'>Island</label>
                                <select class='form-control' name='island'>
                                    <option value='North' <?php echo ($islandRow['_name'] == "North") ? "selected" : ""; ?>>North</option>
                                    <option value='South' <?php echo ($islandRow['_name'] == "South") ? "selected" : ""; ?>>South</option>
                                </select>
                            </div>

                            <div class='form-group w-100 p-2'>
                                <label style='float:left;'>Price</label>
                                <input class='form-control' type='number' name='price' placeholder='Price' value='<?php echo $row['_price'] ?>' required>
                            </div>

                            <div class="form-group w-100 p-2">
                                <label style='float:left;'>Description</label>
                                <textarea class='form-control' type='text' name='description' placeholder='Description' rows='7' required><?php echo $row['_description'] ?></textarea>
                            </div>

                            <?php
                                $facilities = explode(":", $row['_facilities']);
                            ?>                                
                            <div class="form-group w-25 p-1" style='float:left;'>
                                <label style='float:left;'>Garage Space</label>
                                <input class='form-control' type='number' name='garage' placeholder='# of cars spaces in Garage' value='<?php echo number_format( $facilities[0] ) ?>'  required>
                            </div>

                            <div class="form-group w-25 p-1" style='float:left;'>
                                <label style='float:left;'>Bedrooms</label>
                                <input class='form-control' type='number' name='beds' placeholder='# of Bedrooms' value='<?php echo number_format( $facilities[1] ) ?>' required>
                            </div>

                            <div class="form-group w-25 p-1" style='float:left;'>
                                <label style='float:left;'>Bathrooms</label>
                                <input class='form-control' type='number' name='bath' placeholder='# of Bathrooms' value='<?php echo number_format( $facilities[2] ) ?>' required>
                            </div>

                            <div class="form-group w-25 p-1" style='float:left;'>
                                <label style='float:left;'>Toilets</label>
                                <input class='form-control' type='number' name='toilets' placeholder='# of Toilets' value='<?php echo number_format( $facilities[3] ) ?>' required>
                            </div>

                            <div class="form-group w-100">
                                <button class='btn btn-primary w-75' type='submit' name='submit-property'>Update</button>
                            </div>
                        </div> 
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>