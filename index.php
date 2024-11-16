<?php
// Start the session
session_start();

// Mock user data (replace this with your database query in production)
$users = [
    'user1@example.com' => 'password1',
    'user2@example.com' => 'password2',
    'user3@example.com' => 'password3',
    'user4@example.com' => 'password4',
    'user5@example.com' => 'password5',
];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate credentials
    if (isset($users[$email]) && $users[$email] === $password) {
        // Login successful
        $_SESSION['user'] = $email; // Store user info in session
        header('Location: dashboard.php'); // Redirect to dashboard
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login</title>
</head>

<body class="bg-secondary-subtle">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-3">
            <!-- Display Server-Side Validation Messages -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <h1 class="h3 mb-4 fw-normal">Login</h1>
                    <form method="post" action="" id="login-form">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="email" name="email" placeholder="user1@example.com" value="user1@example.com" required>
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="password1" required>
                            <label for="password">Password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        // Automatically submit the form
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('login-form').submit();
        });
    </script>
</body>

</html>
