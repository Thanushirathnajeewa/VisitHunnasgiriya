<?php
session_start();
require 'config/db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {

        $error = 'Please complete all fields.';

    } elseif ($password !== $confirm) {

        $error = 'Passwords do not match.';

    } elseif (strlen($password) < 6) {

        $error = 'Password must contain at least 6 characters.';

    } else {

        $s = $pdo->prepare(
            'SELECT user_id FROM users WHERE email=?'
        );

        $s->execute([$email]);

        if ($s->fetch()) {

            $error = 'An account with this email already exists.';

        } else {

            $s = $pdo->prepare(
                'INSERT INTO users(full_name,email,password) VALUES(?,?,?)'
            );

            $s->execute([
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            $success = 'Registration successful. You can now log in.';
        }
    }
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

    <title>Register | VisitHunnasgiriya</title>

    <!-- Main CSS -->
    <link
        rel="stylesheet"
        href="<?=BASE_URL?>assets/css/style.css?v=4"
    >

    <style>

        /* =========================================
           REGISTER PAGE
        ========================================= */

        body.auth-page {

            min-height: 100vh;

            margin: 0;

            background: #eef6f0;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 30px 15px;

            box-sizing: border-box;
        }


        /* =========================================
           REGISTER CARD
        ========================================= */

        .auth-card {

            width: 100%;

            max-width: 480px;

            background: #ffffff;

            padding: 35px 38px;

            border-radius: 18px;

            box-shadow:
                0 15px 50px rgba(30, 80, 55, 0.12);

            box-sizing: border-box;
        }


        /* =========================================
           LOGO
        ========================================= */

        .auth-logo {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 10px;

            margin-bottom: 22px;

            text-decoration: none;

            color: #173b2b;

            font-size: 20px;

            font-weight: 700;
        }


        .auth-logo img {

            width: 55px;

            height: 55px;

            object-fit: contain;
        }


        .auth-logo strong {

            color: #2e8b57;
        }


        /* =========================================
           HEADING
        ========================================= */

        .auth-card h1 {

            text-align: center;

            color: #245c43;

            margin: 0 0 8px;

            font-size: 28px;
        }


        .auth-card > p:not(.auth-footer) {

            text-align: center;

            color: #68786f;

            font-size: 14px;

            margin: 0 0 22px;
        }


        /* =========================================
           FORM
        ========================================= */

        .auth-card form {

            display: grid;

            gap: 9px;

            margin-top: 20px;
        }


        .auth-card label {

            color: #31483c;

            font-size: 14px;

            font-weight: 600;

            margin-top: 5px;
        }


        .auth-card input {

            width: 100%;

            padding: 12px 13px;

            border: 1px solid #cfdcd3;

            border-radius: 8px;

            background: #ffffff;

            color: #26382f;

            font-size: 14px;

            outline: none;

            box-sizing: border-box;

            transition:
                border-color 0.2s,
                box-shadow 0.2s;
        }


        .auth-card input:focus {

            border-color: #2e8b57;

            box-shadow:
                0 0 0 3px rgba(46, 139, 87, 0.10);
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

            box-sizing: border-box;
        }


        /* =========================================
           PASSWORD EYE BUTTON
        ========================================= */

        .password-eye {

            position: absolute;

            right: 10px;

            top: 50%;

            transform: translateY(-50%);

            width: 34px;

            height: 34px;

            border: none;

            background: transparent;

            padding: 0;

            margin: 0;

            cursor: pointer;

            display: flex;

            align-items: center;

            justify-content: center;

            color: #6b7c72;
        }


        .password-eye:hover {

            background: transparent;

            color: #26382f;
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
           REGISTER BUTTON
        ========================================= */

        .auth-card .btn.full {

            width: 100%;

            margin-top: 12px;

            padding: 12px;

            border: none;

            border-radius: 8px;

            background: #2e8b57;

            color: #ffffff;

            font-size: 15px;

            font-weight: 600;

            cursor: pointer;

            transition:
                background 0.2s,
                transform 0.2s;
        }


        .auth-card .btn.full:hover {

            background: #246f46;

            transform: translateY(-1px);
        }


        /* =========================================
           ALERT MESSAGES
        ========================================= */

        .alert {

            padding: 11px 13px;

            border-radius: 8px;

            margin: 15px 0;

            font-size: 14px;

            text-align: center;
        }


        .alert.error {

            background: #fff1f1;

            color: #a33a3a;

            border: 1px solid #f0cccc;
        }


        .alert.success {

            background: #edf8f0;

            color: #287044;

            border: 1px solid #c8e4cf;
        }


        /* =========================================
           FOOTER
        ========================================= */

        .auth-footer {

            margin: 20px 0 0;

            text-align: center;

            color: #68786f;

            font-size: 14px;
        }


        .auth-footer a {

            color: #2e8b57;

            font-weight: 600;

            text-decoration: none;
        }


        .auth-footer a:hover {

            text-decoration: underline;
        }


        /* =========================================
           BACK LINK
        ========================================= */

        .back-link {

            display: block;

            margin-top: 15px;

            text-align: center;

            color: #2e8b57;

            font-size: 14px;

            text-decoration: none;
        }


        .back-link:hover {

            text-decoration: underline;
        }


        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 500px) {

            body.auth-page {

                padding: 20px 12px;
            }


            .auth-card {

                padding: 28px 22px;
            }


            .auth-card h1 {

                font-size: 24px;
            }


            .auth-logo img {

                width: 48px;

                height: 48px;
            }
        }

    </style>

</head>


<body class="auth-page">


<div class="auth-card">


    <!-- =========================================
         LOGO
    ========================================== -->

    <a
        class="auth-logo"
        href="<?=BASE_URL?>index.php"
    >

        <img
            src="<?=BASE_URL?>assets/images/logo.png"
            alt="VisitHunnasgiriya Logo"
        >

        <span>
            Visit<strong>Hunnasgiriya</strong>
        </span>

    </a>


    <!-- =========================================
         TITLE
    ========================================== -->

    <h1>
        Create User Account
    </h1>


    <p>
        Register to use your VisitHunnasgiriya account.
    </p>


    <!-- =========================================
         ERROR MESSAGE
    ========================================== -->

    <?php if ($error): ?>

        <div class="alert error">

            <?=htmlspecialchars($error)?>

        </div>

    <?php endif; ?>


    <!-- =========================================
         SUCCESS MESSAGE
    ========================================== -->

    <?php if ($success): ?>

        <div class="alert success">

            <?=htmlspecialchars($success)?>

        </div>

    <?php endif; ?>


    <!-- =========================================
         REGISTRATION FORM
    ========================================== -->

    <form method="post">


        <!-- FULL NAME -->

        <label for="full_name">
            Full Name
        </label>


        <input
            type="text"
            id="full_name"
            name="full_name"
            required
            autocomplete="name"
        >


        <!-- EMAIL -->

        <label for="email">
            Email
        </label>


        <input
            type="email"
            id="email"
            name="email"
            required
            autocomplete="email"
        >


        <!-- PASSWORD -->

        <label for="register_password">
            Password
        </label>


        <div class="password-container">


            <input
                type="password"
                id="register_password"
                name="password"
                minlength="6"
                required
                autocomplete="new-password"
            >


            <button
                type="button"
                class="password-eye"
                id="registerPasswordEye"
                aria-label="Show password"
            >


                <!-- OPEN EYE -->

                <svg
                    id="registerEyeOpen"
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
                    id="registerEyeClosed"
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


        <!-- CONFIRM PASSWORD -->

        <label for="confirm_password">
            Confirm Password
        </label>


        <div class="password-container">


            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                minlength="6"
                required
                autocomplete="new-password"
            >


            <button
                type="button"
                class="password-eye"
                id="confirmPasswordEye"
                aria-label="Show confirm password"
            >


                <!-- OPEN EYE -->

                <svg
                    id="confirmEyeOpen"
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
                    id="confirmEyeClosed"
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


        <!-- REGISTER BUTTON -->

        <button
            class="btn full"
            type="submit"
        >
            Register
        </button>


    </form>


    <!-- =========================================
         LOGIN LINK
    ========================================== -->

    <p class="auth-footer">

        Already have an account?

        <a href="<?=BASE_URL?>login.php">
            User Login
        </a>

    </p>


    <!-- =========================================
         BACK TO WEBSITE
    ========================================== -->

    <a
        class="back-link"
        href="<?=BASE_URL?>index.php"
    >
        ← Choose Login Type
    </a>


</div>


<script>

/* =========================================
   REGISTER PASSWORD
========================================= */

const registerPassword =
    document.getElementById('register_password');

const registerPasswordEye =
    document.getElementById('registerPasswordEye');

const registerEyeOpen =
    document.getElementById('registerEyeOpen');

const registerEyeClosed =
    document.getElementById('registerEyeClosed');


registerPasswordEye.addEventListener(
    'click',
    function () {

        if (registerPassword.type === 'password') {

            registerPassword.type = 'text';

            registerEyeOpen.style.display = 'none';

            registerEyeClosed.style.display = 'block';

            registerPasswordEye.setAttribute(
                'aria-label',
                'Hide password'
            );

        } else {

            registerPassword.type = 'password';

            registerEyeOpen.style.display = 'block';

            registerEyeClosed.style.display = 'none';

            registerPasswordEye.setAttribute(
                'aria-label',
                'Show password'
            );
        }

    }
);


/* =========================================
   CONFIRM PASSWORD
========================================= */

const confirmPassword =
    document.getElementById('confirm_password');

const confirmPasswordEye =
    document.getElementById('confirmPasswordEye');

const confirmEyeOpen =
    document.getElementById('confirmEyeOpen');

const confirmEyeClosed =
    document.getElementById('confirmEyeClosed');


confirmPasswordEye.addEventListener(
    'click',
    function () {

        if (confirmPassword.type === 'password') {

            confirmPassword.type = 'text';

            confirmEyeOpen.style.display = 'none';

            confirmEyeClosed.style.display = 'block';

            confirmPasswordEye.setAttribute(
                'aria-label',
                'Hide confirm password'
            );

        } else {

            confirmPassword.type = 'password';

            confirmEyeOpen.style.display = 'block';

            confirmEyeClosed.style.display = 'none';

            confirmPasswordEye.setAttribute(
                'aria-label',
                'Show confirm password'
            );
        }

    }
);

</script>


</body>
</html>