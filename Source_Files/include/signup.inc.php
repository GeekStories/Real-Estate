<?php
//Validate User Account Details
function ValidateUserDetails($username, $pass, $passRepeat, $email){
    //Check if the email is a valid email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: ../signup.php?error=Invalid Email&mail=". $email ."&uid=". $username);
        exit();
    }
    //Check if the username is only Number and Letters
    if(!preg_match("/^[a-zA-Z0-9 ]*$/", $username)){
        header("Location: ../signup.php?error=Invalid Username&mail=". $email ."&uid=". $username);
        exit();
    }
    //Check if password is a minimum of 10 characters    
    if(strlen($pass) < 9){
        header("Location: ../signup.php?error=Password is too short! (Min. 10 characters)&mail=". $email ."&uid=". $username);
        exit();
    }
    //Check if password and repeated password match
    if($pass !== $passRepeat){
        header("Location: ../signup.php?error=Passwords don't match&mail=". $email ."&uid=". $username);
        exit();
    }

    return true;
}
//Check Existing User
function CheckUserExists($username){
    require '../config.php';
    $query = "SELECT id FROM users WHERE username=? OR email=?";

    if(!$user_exists_query = $db->prepare($query)){
        header("Location: ../signup.php?error=Failed to create account!");
        exit();
    }

    $user_exists_query->bind_param("ss", $username, $email);
    $user_exists_query->execute();

    $result = $user_exists_query->get_result();

    if($result->num_rows > 0){
        header("Location: ../signup.php?error=User already exists!");
        exit();
    }

    return false;
}
//Validate Invite Code For Agent
function CheckInviteCode($company_id, $invite_code){
    require '../config.php';
    $query = "SELECT company_name, invite_code FROM companies WHERE id=?";

    if(!$company_id_query = $db->prepare($query)){
        header("Location: ../signup.php?error=Failed to grab company id!");
        exit();
    }

    $company_id_query->bind_param("i", $company_id);
    $company_id_query->execute();

    $result = $company_id_query->get_result();
    $company_result = $result->fetch_assoc();

    $code = $company_result['invite_code'];

    if($invite_code !== $code){
        header("Location: ../signup.php?error=Incorrect Invite Code!");
        exit();
    }

    return true;
}

//Validate Company Name
function CheckCompanyName($company_name){
    if(!preg_match("/^[a-zA-Z0-9 ]*$/", $company_name)){
        header("Location: ../signup.php?error=Invalid Company Name!&mail=". $email ."&uid=". $username);
        exit();
    }
    return true;
}
//Check Existing Company
function CheckCompanyExists($company_name){
    require '../config.php';
    $query = "SELECT id FROM companies WHERE company_name=?";

    if(!$check_company_exists = $db->prepare($query)){
        header("Location: ../signup.php?error=Failed to check company name!&mail=". $email ."&uid=". $username);
        exit();
    }

    $check_company_exists->bind_param("s", $company_name);
    $check_company_exists->execute();
    $check_company_exists_results = $check_company_exists->get_result();

    if($check_company_exists_results->num_rows > 0){
        //Company name exists
        header("Location: ../signup.php?error=Company Exists!!&mail=". $email ."&uid=". $username);
        exit();
    }

    return false;
}
//Add New Company To Database
function AddNewCompany($company_name){
    require "../config.php";
    $insert_new_company_query = "INSERT INTO companies (company_name, invite_code) VALUES (?, ?);";
    
    if(!$insert_new_company = $db->prepare($insert_new_company_query)){
        header("Location: ../signup.php?error=Failed to add new company name");
        exit();
    }

    //Generate a semi-unique 10 digit invite code for Agents to use
    $invite_code = bin2hex(random_bytes(10));

    //Bind information and Insert new company into Database
    $insert_new_company->bind_param("ss", $company_name, $invite_code);
    $insert_new_company->execute();

    $company_id = mysqli_insert_id($db);

    return $company_id;
}
//Add New User To Database
function AddNewUser($username, $email, $password, $role_id, $company_id){
    require "../config.php";
    $query = "INSERT INTO users (username, email, password, role_id, company_id) VALUES (?, ?, ?, ?, ?)";

    if(!$insert_new_user = $db->prepare($query)){
        header("Location: ../signup.php?result=Failed to create account!");
        exit();
    }

    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

    $insert_new_user->bind_param("sssii", $username, $email, $hashedPwd, $role_id, $company_id);
    $insert_new_user->execute();

    $user_id = mysqli_insert_id($db);
    return $user_id;
}

//Begin SignUp Process If Form Was Submitted
if(isset($_POST['signup-submit'])){
    require '../config.php';

    //Grab the users input from $_POST
    $username = htmlspecialchars($_POST['uid']);
    $email = $_POST['email'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];

    //Assume the user is a general user, so default the id's to 0
    $role_id = 0;
    $company_id = 0;

    //Ensure the details are correct
    if(ValidateUserDetails($username, $password, $passwordRepeat, $email)){
        //Check if the username or email exists already
        if(!CheckUserExists($username)){
            //Check if the user is apart or owns a company
            if(isset($_POST['company-check']) === TRUE){
                $company_check = $_POST['company-check']; // "owner" or "agent"

                switch($company_check){
                    case "agent": //User is an Agent of another company
                        $company_id = $_POST['selected_company'];
                        $invite_code = $_POST['invite_code'];
                        $role_id = 1;
        
                        //Verify the users input code is correct for their selected company
                        if(CheckInviteCode($company_id, $invite_code)){
                            $company_name = $company_result['company_name'];
                        }
                        break;
                    case "owner": //User owns a company and wants to create a new profile
                        $company_name = $_POST['company_name'];
        
                        //Verify the company name
                        if(CheckCompanyName($company_name)){
                            //Check if the company exists
                            if(!CheckCompanyExists($company_name)){
                                //Add the new company!
                                $company_id = AddNewCompany($company_name);

                                //Set the users role ID as 2 (Owner)
                                $role_id = 2;
                            }
                        }
                        break;
                    default:
                        header("Location: ../signup.php?error=Invalid Selection&mail=". $email ."&uid=". $username);
                        exit();
                }
            }

            //Add the new user
            $user_id = AddNewUser($username, $email, $password, $role_id, $company_id);

            switch($role_id){
                case 1:
                    $query = "UPDATE companies SET staff=? WHERE id=?";

                    $staff_list = array();
                    $staff_list = explode(":", GetStaffMembersList($company_id));

                    array_push($staff_list, $user_id);

                    $new_staff_list = implode(":", $staff_list);
                    
                    if(!$update_company_manager_query = $db->prepare($query)){
                        header("Location: ../signup.php?result=Failed to update company staff!&mail=". $email ."&uid=". $username);
                        exit();
                    }

                    $update_company_manager_query->bind_param("si", $new_staff_list, $company_id);
                    $update_company_manager_query->execute();
                    break;
                case 2:
                    $query = "UPDATE companies SET manager=? WHERE id=?";
                    
                    if(!$update_company_manager_query = $db->prepare($query)){
                        header("Location: ../signup.php?result=Failed to set company manager!&mail=". $email ."&uid=". $username);
                        exit();
                    }

                    $update_company_manager_query->bind_param("ii", $user_id, $company_id);
                    $update_company_manager_query->execute();
                    break;
                default:
                    break;
            }

            //Account created! Back to the login page..
            switch ($role_id) {
                case 1:
                    header("Location: ../login.php?result=Account created! Please wait for Manager Verification! Your Manager will let you know when your account is setup!.&c=g");
                    exit();  
                case 2:
                    header("Location: ../login.php?result=Account created! Please wait for Email verification while we setup your company profile!.&c=g");
                    exit();  
                default:
                    header("Location: ../login.php?result=Account created! Login to access your account :).&c=g");
                    exit();  
            }
        }
    }
}else{
    header("Location: ../signup.php");
    exit();
}

function GetStaffMembersList($company_id){
    require "../config.php";

    $query = "SELECT staff FROM companies WHERE id=?";

    if(!$staff_list_query = $db->prepare($query)){
        header("Location: ../signup.php?error=Failed to get company staff list");
        exit();
    }

    $staff_list_query->bind_param("i", $company_id);
    $staff_list_query->execute();

    $staff_list_query_results = $staff_list_query->get_result();
    $staff_list_results = $staff_list_query_results->fetch_assoc();

    return $staff_list_results['staff'];
}
