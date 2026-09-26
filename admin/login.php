
<?php
session_start();

require_once '../config/db.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter your email and password.';
    } else {

        $stmt = $pdo->prepare(
            'SELECT admin_id, email, password
             FROM admins
             WHERE email = ?
             LIMIT 1'
        );

        $stmt->execute([$email]);

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        $validPassword = false;

        if ($admin) {
            // Supports both the existing phpMyAdmin password and future password hashes.
            $validPassword = password_verify($password, $admin['password']);
            if (!$validPassword && hash_equals((string)$admin['password'], (string)$password)) {
                $validPassword = true;
                // Upgrade the existing plain-text password to a secure hash after login.
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $update = $pdo->prepare('UPDATE admins SET password = ? WHERE admin_id = ?');
                $update->execute([$newHash, $admin['admin_id']]);
            }
        }

        if ($validPassword) {

            session_regenerate_id(true);

            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_username'] = $admin['email'];

            header('Location: dashboard.php');
            exit;

        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Login | VisitHunnasgiriya</title>

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/style.css?v=4"
    >

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>admin/assets/css/admin.css?v=4"
    >

    <style>

        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            background: #eef6f0;
        }

        .auth-card {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            padding: 40px 45px;
            border-radius: 22px;
            border: 1px solid #e0ebe4;
            box-shadow: 0 12px 40px rgba(30, 80, 55, 0.12);
            box-sizing: border-box;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
        }

        .auth-logo img {
            width: 150px;
            height: auto;
        }

        .auth-card h1 {
            margin: 0 0 8px;
            text-align: center;
            color: #173b2b;
            font-size: 32px;
        }

        .auth-card > p {
            margin: 0 0 25px;
            text-align: center;
            color: #65746d;
            font-size: 15px;
        }

        .auth-card form > label {
            display: block;
            margin-bottom: 7px;
            color: #33483d;
            font-size: 15px;
            font-weight: 600;
        }

        .auth-card input {
            width: 100%;
            height: 52px;
            padding: 0 15px;
            margin-bottom: 18px;
            border: 1px solid #cbdcd2;
            border-radius: 10px;
            background: #ffffff;
            color: #26382f;
            font-size: 15px;
            outline: none;
            box-sizing: border-box;
        }

        .auth-card input:focus {
            border-color: #2e8b57;
            box-shadow: 0 0 0 3px rgba(46, 139, 87, 0.12);
        }

        .password-container {
            position: relative;
            width: 100%;
        }

        .password-container input {
            padding-right: 50px;
        }

        .password-eye {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            cursor: pointer;
            color: #6b7c72;
        }

        .password-eye:hover {
            color: #2e8b57;
        }

        .password-eye svg {
            width: 20px;
            height: 20px;
        }

        .auth-card .btn.full {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 10px;
            background: #2e8b57;
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .auth-card .btn.full:hover {
            background: #245f45;
        }

        .alert.error {
            margin-bottom: 20px;
            padding: 12px 15px;
            border-radius: 9px;
            background: #fdeaea;
            border: 1px solid #f2c8c8;
            color: #9b3333;
            font-size: 14px;
            text-align: center;
        }

        .auth-footer {
            margin: 23px 0 0;
            text-align: center;
            color: #52645b;
            font-size: 15px;
        }

        .auth-footer a {
            color: #6f159c;
            font-weight: 600;
            text-decoration: none;
        }

        .back-link {
            display: block;
            margin-top: 18px;
            text-align: center;
            color: #2e8b57;
            font-size: 15px;
            text-decoration: none;
        }

        @media (max-width: 600px) {

            .auth-page {
                padding: 20px 15px;
            }

            .auth-card {
                padding: 30px 24px;
                border-radius: 18px;
            }

            .auth-logo img {
                width: 125px;
            }

            .auth-card h1 {
                font-size: 28px;
            }
        }

    </style>

</head>

<body class="auth-page">

<div class="auth-card">

    <a
        class="auth-logo"
        href="<?= BASE_URL ?>index.php"
    >
        <img
            src="<?= BASE_URL ?>assets/images/logo.png"
            alt="Visit Hunnasgiriya Logo"
        >
    </a>

    <h1>Admin Login</h1>

    <p>Administration panel</p>

    <?php if ($error): ?>

        <div class="alert error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <form method="post">

        <label for="email">
            Email
        </label>

        <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter your email"
            autocomplete="username"
            required
            autofocus
        >

        <label for="adminPassword">
            Password
        </label>

        <div class="password-container">

            <input
                type="password"
                id="adminPassword"
                name="password"
                placeholder="Enter your password"
                autocomplete="current-password"
                required
            >

            <button
                type="button"
                class="password-eye"
                id="passwordEye"
                aria-label="Show password"
            >

                <svg
                    id="eyeOpen"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path
                        d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"
                    />
                    <circle
                        cx="12"
                        cy="12"
                        r="2.5"
                    />
                </svg>

                <svg
                    id="eyeClosed"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    style="display:none;"
                >
                    <path d="M3 3l18 18"/>
                    <path
                        d="M10.6 5.1A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a17.5 17.5 0 0 1-3.1 3.8"
                    />
                    <path
                        d="M6.2 6.2C3.6 8 2 12 2 12s3.5 7 10 7c1.6 0 3-.4 4.2-1"
                    />
                    <path
                        d="M9.9 9.9a3 3 0 0 0 4.2 4.2"
                    />
                </svg>

            </button>

        </div>

        <button
            class="btn full"
            type="submit"
        >
            Login
        </button>

    </form>

    <p class="auth-footer">

        <a href="<?= BASE_URL ?>login.php">
            User Login
        </a>

        ·

        <a href="<?= BASE_URL ?>index.php">
            Choose Login Type
        </a>

    </p>

    <a
        class="back-link"
        href="<?= BASE_URL ?>index.php"
    >
        ← Back to Website
    </a>

</div>

<script>

const password = document.getElementById('adminPassword');
const eyeButton = document.getElementById('passwordEye');
const eyeOpen = document.getElementById('eyeOpen');
const eyeClosed = document.getElementById('eyeClosed');

eyeButton.addEventListener('click', function () {

    if (password.type === 'password') {

        password.type = 'text';

        eyeOpen.style.display = 'none';
        eyeClosed.style.display = 'block';

        eyeButton.setAttribute(
            'aria-label',
            'Hide password'
        );

    } else {

        password.type = 'password';

        eyeOpen.style.display = 'block';
        eyeClosed.style.display = 'none';

        eyeButton.setAttribute(
            'aria-label',
            'Show password'
        );
    }

});

</script>

</body>

</html>
