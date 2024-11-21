<?php
session_start();

function postData($key) {
    return isset($_POST[$key]) ? $_POST[$key] : '';
}

function guardLogin() {
    $dashboardPage = 'admin/dashboard.php';
    if (isset($_SESSION['email'])) {
        header("Location: $dashboardPage");
        exit;
    }
}

function guardDashboard() {
    $loginPage = '../index.php';
    if (!isset($_SESSION['email'])) {
        header("Location: $loginPage");
        exit;
    }
}

function getConnection() {
    $host = 'localhost';
    $dbName = 'dct-ccs-finals';
    $username = 'root';
    $password = '';
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
<<<<<<< Updated upstream

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
=======
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    
    return $errors;
}



function displayErrors($errors) {
    if (empty($errors)) return "";

    $errorHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>System Alerts</strong><ul>';

    // Make sure each error is a string
    foreach ($errors as $error) {
        // Check if $error is an array or not
        if (is_array($error)) {
            // If it's an array, convert it to a string (you could adjust this to fit your needs)
            $errorHtml .= '<li>' . implode(", ", $error) . '</li>';
        } else {
            $errorHtml .= '<li>' . htmlspecialchars($error) . '</li>';
        }
    }

    $errorHtml .= '</ul><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';

    return $errorHtml;
}


function countAllSubjects() {
    try {
        // Get the database connection
        $conn = getConnection();

        // SQL query to count all subjects
        $sql = "SELECT COUNT(*) AS total_subjects FROM subjects";
        $stmt = $conn->prepare($sql);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the count
        return $result['total_subjects'];
    } catch (PDOException $e) {
        // Handle any errors
        return "Error: " . $e->getMessage();
    }
}


function countAllStudents() {
    try {
        // Get the database connection
        $conn = getConnection();

        // SQL query to count all students
        $sql = "SELECT COUNT(*) AS total_students FROM students";
        $stmt = $conn->prepare($sql);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the count
        return $result['total_students'];
    } catch (PDOException $e) {
        // Handle any errors
        return "Error: " . $e->getMessage();
    }
}


function calculateTotalPassedAndFailedStudents() {
    try {
        // Get the database connection
        $conn = getConnection();

        // SQL query to calculate the total grade for each student and their count of assigned subjects
        $sql = "SELECT student_id, 
                       SUM(grade) AS total_grades, 
                       COUNT(subject_id) AS total_subjects 
                FROM students_subjects 
                GROUP BY student_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize counters
        $passed = 0;
        $failed = 0;

        // Loop through each student
        foreach ($students as $student) {
            $average_grade = $student['total_grades'] / $student['total_subjects'];
            if ($average_grade >= 75) {
                $passed++;
            } else {
                $failed++;
            }
        }

        // Return the total passed and failed students
        return [
            'passed' => $passed,
            'failed' => $failed
        ];
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}
>>>>>>> Stashed changes 
