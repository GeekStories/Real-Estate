<?php

function GetCompanyName($id){
    require "../../config.php";
    $query = "SELECT company_name FROM companies WHERE id=?";
    $company = $db->prepare($query);
    $company->bind_param("i", $id);
    $company->execute();

    $company_name_result = $company->get_result();
    $name = $company_name_result->fetch_assoc();

    return $name['company_name'];
}

if(isset($_POST['login-submit'])){
    require "../../config.php";

    $mailuid = htmlspecialchars($_POST['mailuid']);
    $password = $_POST['pwd'];

    $get_user_role = $db->prepare("SELECT id, username, password, role_id, company_id, wishlist FROM users WHERE username=? OR email=?");
    $get_user_role->bind_param("ss", $mailuid, $mailuid);
    $get_user_role->execute();

    $get_user_role_result = $get_user_role->get_result();
    $user_role_id = $get_user_role_result->fetch_assoc();

    switch ($user_role_id['role_id']){
        case 3:
            if(password_verify($password, $user_role_id['password'])){
                session_start();
                $_SESSION['username'] = $user_role_id['username'];
                $_SESSION['role_id'] = $user_role_id['role_id'];
                $_SESSION['user_id'] = $user_role_id['id'];
                $_SESSION['wishlist'] = $user_role_id['wishlist'];
                $_SESSION['company_id'] = $user_role_id['company_id'];
                $_SESSION['company_name'] = GetCompanyName(intval($user_role_id['company_id']));
    
                header("Location: ../controlpanel.php?login=success");
                exit();
            } else {
                header("Location: ../admin-login.php?login=incorrect");
                exit();
            }
        default:
            header("Location: ../../index.php");
            exit();
    }
} else { //We aren't meant to be here!
    header("Location: ../../index.php");
    exit();
}