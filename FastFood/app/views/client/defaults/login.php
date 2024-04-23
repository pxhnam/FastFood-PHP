<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <?php
    include_once 'app/views/client/layouts/styles.php'
    ?>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-3">
                <h3 class="text-center mt-5 mb-3">ĐĂNG NHẬP</h3>
                <?= isset($error) ? '<p class="text-danger">' . $error . '</p>' : '' ?>
                <form action="" method="POST" class="form-floating">

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="username" id="inputUsername" placeholder="" autofocus>
                        <label for="inputUsername">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="password" id="inputPassword" placeholder="">
                        <label for="inputPassword">Password</label>
                    </div>

                    <div class="form-group text-center mb-3">
                        <button type="submit" class="btn btn-primary text-center">Đăng Nhập</button>
                    </div>
                    <p class="text-center">
                        <a href="/register" class="text-decoration-none">Đăng ký ngay!</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
    <?php
    include_once 'app/views/client/layouts/scripts.php';
    ?>
    <script>
        $(document).ready(function() {
            $('input').on('input', function() {
                $('.text-danger').remove();
            });
        });
    </script>
</body>

</html>