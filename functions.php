<?php    
session_start();    

function postData($key){
    return $_POST["$key"];
}

function guardLogin(){
    $dashboardPage = 'admin/dashboard.php';
    if(isset($_SESSION['email'])){
        header("Location: $dashboardPage");
    } 
}

function guardDashboard(){
    $loginPage = '../index.php';
    if(!isset($_SESSION['email'])){
        header("Location: $loginPage");
    }
}

function getConnection() {
    $host = 'localhost';
    $dbName = 'dct-ccs-finals';
    $username = 'root';
    $password = "";
    $charset = 'utf8mb4';
    
    try {
        $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

function validateLoginCredentials($email, $password) {
    $errors = [];

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = "Invalid email format.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    return $errors;
}

function displayErrors($errors) {
    $html = "<ul>";
    foreach ($errors as $error) {
        $html .= "<li>$error</li>";
    }
    $html .= "</ul>";
    return $html;
}

function login($email, $password) {
    $validateLogin = validateLoginCredentials($email, $password);

    if (count($validateLogin) > 0) {
        echo displayErrors($validateLogin);
        return;
    }

    $conn = getConnection();
    $hashedPassword = md5($password);

    $query = "SELECT * FROM users WHERE email = :email AND password = :password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['email'] = $user['email'];
        header("Location: admin/dashboard.php");
    } else {
        echo displayErrors(["Invalid email or password."]);
    }
}
