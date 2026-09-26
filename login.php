<?php
session_start();
require 'config/db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $s = $pdo->prepare('SELECT * FROM users WHERE email=? LIMIT 1');
    $s->execute([$email]);
    $u = $s->fetch();

    if ($u && password_verify($password, $u['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $u['user_id'];
        $_SESSION['user_name'] = $u['full_name'];

        header('Location: home.php');
        exit;
    }

    $error = 'Invalid email or password.';
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>User Login | VisitHunnasgiriya</title>

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/style.css?v=4"
    >

    <style>

        /* =========================================
           LOGIN PAGE
           ========================================= */

        .auth-page {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px 20px;

            background: #eef6f0;
        }


        /* =========================================
           LOGIN CARD
           ========================================= */

        .auth-card {
            width: 100%;
            max-width: 480px;

            background: #ffffff;

            padding: 40px 45px;

            border-radius: 22px;

            border: 1px solid #e0ebe4;

            box-shadow:
                0 12px 40px rgba(30, 80, 55, 0.12);

            text-align: left;
        }


        /* =========================================
           LOGO
           ========================================= */

        .auth-logo {
            display: flex;

            align-items: center;
            justify-content: center;

            margin-bottom: 22px;

            text-decoration: none;
        }

        .auth-logo img {
            width: 150px;
            height: auto;

            object-fit: contain;
        }


        /* Hide text if any */
        .auth-logo span {
            display: none;
        }


        /* =========================================
           TITLE
           ========================================= */

        .auth-card h1 {
            margin: 0 0 8px;

            text-align: center;

            color: #173b2b;

            font-size: 32px;

            font-weight: 700;
        }


        /* =========================================
           DESCRIPTION
           ========================================= */

        .auth-card > p {
            margin: 0 0 25px;

            text-align: center;

            color: #65746d;

            font-size: 15px;

            line-height: 1.5;
        }


        /* =========================================
           FORM
           ========================================= */

        .auth-card form {
            display: block;

            margin: 0;
        }


        /* =========================================
           LABELS
           ========================================= */

        .auth-card form > label {
            display: block;

            margin-bottom: 7px;

            color: #33483d;

            font-size: 15px;

            font-weight: 600;
        }


        /* =========================================
           INPUTS
           ========================================= */

        .auth-card input {
            width: 100%;

            height: 52px;

            padding: 0 15px;

            margin-bottom: 18px;

            border: 1px solid #cbdcd2;

            border-radius: 10px;

            background: #ffffff;

            color: #26382f;

            font-family: inherit;

            font-size: 15px;

            outline: none;

            box-sizing: border-box;

            transition: 0.2s ease;
        }


        .auth-card input:focus {
            border-color: #2e8b57;

            box-shadow:
                0 0 0 3px rgba(46, 139, 87, 0.12);
        }


        /* =========================================
           PASSWORD CONTAINER
           ========================================= */

        .password-container {
            position: relative;

            width: 100%;
        }


        .password-container input {
            width: 100%;

            padding-right: 50px;
        }


        /* =========================================
           PASSWORD EYE
           ========================================= */

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

            padding: 0;
            margin: 0;

            cursor: pointer;

            color: #6b7c72;

            z-index: 5;
        }


        .password-eye:hover {
            background: transparent;

            color: #2e8b57;
        }


        .password-eye:focus {
            outline: none;

            box-shadow: none;
        }


        .password-eye svg {
            width: 20px;
            height: 20px;

            stroke: currentColor;
        }


        /* =========================================
           LOGIN BUTTON
           ========================================= */

        .auth-card .btn.full {
            width: 100%;

            height: 52px;

            display: flex;

            align-items: center;
            justify-content: center;

            margin-top: 3px;

            border: none;

            border-radius: 10px;

            background: #2e8b57;

            color: #ffffff !important;

            font-size: 16px;

            font-weight: 700;

            cursor: pointer;

            transition: all 0.25s ease;
        }


        .auth-card .btn.full:hover {
            background: #245f45;

            transform: translateY(-1px);

            box-shadow:
                0 7px 18px rgba(46, 125, 91, 0.20);
        }


        /* =========================================
           ERROR MESSAGE
           ========================================= */

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


        /* =========================================
           FOOTER
           ========================================= */

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


        .auth-footer a:hover {
            text-decoration: underline;
        }


        /* =========================================
           BACK TO WEBSITE
           ========================================= */

        .back-link {
            display: block;

            margin-top: 18px;

            text-align: center;

            color: #2e8b57;

            font-size: 15px;

            text-decoration: none;
        }


        .back-link:hover {
            color: #245f45;

            text-decoration: underline;
        }


        /* =========================================
           MOBILE
           ========================================= */

        @media (max-width: 600px) {

            .auth-page {
                padding: 20px 15px;
            }

            .auth-card {
                max-width: 100%;

                padding: 30px 24px;

                border-radius: 18px;
            }

            .auth-logo img {
                width: 125px;
            }

            .auth-card h1 {
                font-size: 28px;
            }

            .auth-card input {
                height: 50px;
            }

            .auth-card .btn.full {
                height: 50px;
            }
        }

    </style>

</head>


<body class="auth-page">

<div class="auth-card">

    <!-- LOGO -->
    <a
        class="auth-logo"
        href="<?= BASE_URL ?>index.php"
    >
        <img
            src="<?= BASE_URL ?>assets/images/logo.png"
            alt="Visit Hunnasgiriya Logo"
        >
    </a>


    <!-- TITLE -->
    <h1>User Login</h1>

    <p>
        Sign in to use your VisitHunnasgiriya account.
    </p>


    <!-- ERROR -->
    <?php if ($error): ?>

        <div class="alert error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>


    <!-- LOGIN FORM -->
    <form method="post">

        <!-- EMAIL -->
        <label for="email">
            Email
        </label>

        <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter your email"
            required
            autofocus
        >


        <!-- PASSWORD -->
        <label for="login_password">
            Password
        </label>

        <div class="password-container">

            <input
                type="password"
                id="login_password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <button
                type="button"
                class="password-eye"
                id="loginPasswordEye"
                aria-label="Show password"
            >

                <!-- OPEN EYE -->
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


                <!-- CLOSED EYE -->
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


        <!-- LOGIN BUTTON -->
        <button
            class="btn full"
            type="submit"
        >
            Login
        </button>

    </form>


    <!-- REGISTER -->
    <p class="auth-footer">

        New user?

        <a href="<?= BASE_URL ?>register.php">
            Create an account
        </a>

    </p>


    <!-- BACK -->
    <a
        class="back-link"
        href="<?= BASE_URL ?>index.php"
    >
        ← Choose Login Type
    </a>

</div>


<!-- PASSWORD SCRIPT -->
<script>

const password =
    document.getElementById('login_password');

const eyeButton =
    document.getElementById('loginPasswordEye');

const eyeOpen =
    document.getElementById('eyeOpen');

const eyeClosed =
    document.getElementById('eyeClosed');


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