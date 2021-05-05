<?php
    include "config.php";
    include "header.php";
?>

<html>
    <head>
        <title>Real-Estate</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> 
        <script src="libs/jquery-3.5.1.min.js"></script>
    </head>

    <body>
        <div class="container">
            <div class="row-auto">
                <div class="col-5 text-center m-auto">
                    <h1 class="m-auto" style="font-weight:100;">Create an account.</h1> <br/>
                    <h4 class="error_message m-auto" style="color:red;font-size:1em;font-weight:200;">
                        <?php 
                            if(!empty($_GET['error'])){
                                echo $_GET['error'];
                            } 
                        ?>
                    </h4> <br />
                    <form class="form-signup m-auto" action="include/signup.inc.php" method="post" id="signup-form">
                        <div class="form-group"><input class="form-control" type="text" name="uid" placeholder="Username" value='<?php echo (!empty($_GET['uid'])) ? $_GET['uid'] : ""; ?>' required></div>
                        <div class="form-group"><input class="form-control" type="text" name="email" placeholder="E-mail" value='<?php echo(!empty($_GET['mail'])) ? $_GET['mail'] : ""; ?>'required></div>
                        
                        <div class="form-group">
                            <input class="form-control" style="margin-bottom:1rem;" type="password" name="pwd" placeholder="Password" required>
                            <input class="form-control" type="password" name="pwd-repeat" placeholder="Repeat Password" required>
                            <span class="small">Min. 10 Characters (0-9, a-Z)
                        </div>

                        <div class="form-group">
                            <input type="radio" class="form-check-input in-company company-check" value="agent" name="company-check">
                            <label class="form-check-label">
                                Apart of a company?
                            </label>
                                <br/>
                            <input type="radio" class="form-check-input own-company company-check" value="owner" name="company-check">
                            <label class="form-check-label">
                                Own a company?
                            </label>
                                <br />
                            <button type="button" class="btn btn-primary btn-sm" onclick="ClearSelection()">Clear selection</button>
                        </div>

                        <div class="form-group select-company-name">
                            <input type="text" class="form-control" name="invite_code" placeholder="Enter Invite Code">

                            <select class="form-control" name="selected_company">
                                <option selected>Select Company (if aplicable)</option>
                                <?php
                                    $query = "SELECT id, company_name FROM companies";

                                    if(!$get_names_query = $db->prepare($query)){
                                        echo "<option>Error fetching company names</option>";
                                    }
                                    
                                    $get_names_query->execute();
                                    $result = $get_names_query->get_result();

                                    if($result->num_rows === 0 ){
                                        echo "<option disabled>No companies available</option>";
                                    }

                                    while($row = $result->fetch_assoc()){
                                        echo "<option value='". $row['id']."'>".$row['company_name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group new-company-name">
                            <input type="text" class="form-control" name="company_name" placeholder="Company name">
                        </div>
                        
                        <button class="btn btn-primary w-75" type="submit" name="signup-submit">Create Account</button>
                    </form>
                </div>
            </div>
        </div>
    </body>

    <script>
        $(document).ready(function(){
            $(".new-company-name").hide();
            $(".select-company-name").hide();

            $(".company-check").click(function(){
                if($(".in-company").is(':checked')){
                    $(".new-company-name").hide();
                    $('.select-company-name').show();
                }

                if($(".own-company").is(':checked')){
                    $(".new-company-name").show();
                    $(".select-company-name").hide();
                }

                if($(".no-company").is(':checked')){
                    $(".new-company-name").hide();
                    $(".select-company-name").hide();
                }
            });
        });

        function ClearSelection(){
            $(".new-company-name").hide();
            $(".select-company-name").hide();

            $(".company-check").prop('checked', false);
        }
    </script>
</html>