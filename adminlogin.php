<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background:rgb(8, 0, 0);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .box {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .box h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 92%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid green;
            border-radius: 4px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color:rgb(0, 0, 0);
        }

        .form-group .submit-btn {
            background: #4B12E7;
            color: #fff;
            border: 1px solid black;
            padding: 8px 12px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .form-group .submit-btn:hover {
            background:rgb(0, 2, 4);
        }

        @media (max-width: 500px) {
            .box {
                padding: 20px;
            }

            .form-group label {
                font-size: 12px;
            }

            .form-group input, .form-group .submit-btn {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <main>
        <div class="box">
            <h2>Admin Login</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="submit-btn">Login</button>
                </div>
            </form>
        </div>
    </main>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if ($username === 'QuickTickets' && $password === 'QuickAdmin') {
            echo "<script>alert('Login successful! Redirecting...'); window.location.href='admindashboard.html';</script>";
            exit();
        } else {
            echo "<script>alert('Invalid username or password!');</script>";
        }
    }
    ?>
</body>
</html>
