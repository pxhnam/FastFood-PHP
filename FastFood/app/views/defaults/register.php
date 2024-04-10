<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <?php
    include_once 'app/views/layouts/styles.php'
    ?>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-3">
                <h3 class="text-center mt-5 mb-3">ĐĂNG KÝ</h3>
                <form action="" method="POST">

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control<?= isset($errors['username']) ? ' is-invalid' : '' ?>" name="username" id="inputUsername" placeholder="" autofocus>
                        <label for="inputUsername">Username</label>
                        <?= isset($errors['username']) ? '<small class="form-text text-danger">' . $errors['username'] . '</small>' : '' ?>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control<?= isset($errors['password']) ? ' is-invalid' : '' ?>" name=" password" id="inputPassword" placeholder="">
                        <label for="inputPassword">Password</label>
                        <?= isset($errors['password']) ? '<small class="form-text text-danger">' . $errors['password'] . '</small>' : '' ?>
                    </div>
                    <div class="form-group text-center mb-3">
                        <button type="submit" class="btn btn-primary text-center">Đăng Ký</button>
                    </div>
                    <p class="text-center">
                        <a href="/login" class="text-decoration-none">Trở lại trang đăng nhập</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
    <?php
    include_once 'app/views/layouts/scripts.php';
    ?>
    <script>
        $(document).ready(function() {
            $('input').on('input', function() {
                // Xóa thông báo lỗi cho username
                $(this).siblings('.text-danger').empty();
                $(this).removeClass('is-invalid');
            });
        });
    </script>
</body>

</html>