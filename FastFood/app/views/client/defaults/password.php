<?php $dir = 'app/views/client/layouts/' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Mật Khẩu</title>
    <?php include_once $dir . 'styles.php' ?>
</head>

<body>
    <?php include_once $dir . 'header.php' ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-3">
                <h3 class="text-center mt-5 mb-3">ĐỔI MẬT KHẨU</h3>
                <form action="" method="POST" class="form-floating">
                    <?= isset($errors['all']) ? '<p class="text-danger danger-all text-center">' . $errors['all'] . '</p>' : '' ?>
                    <?= isset($success) ? '<p class="text-success text-center">' . $success . '</p>' : '' ?>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control<?= isset($errors['old_password']) ? ' is-invalid' : '' ?>" name="old_password" id="old_password" placeholder="">
                        <label for="old_password">Old Password</label>
                        <?= isset($errors['old_password']) ? '<small class="form-text text-danger">' . $errors['old_password'] . '</small>' : '' ?>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control<?= isset($errors['new_password']) ? ' is-invalid' : '' ?>" name="new_password" id="new_password" placeholder="">
                        <label for="new_password">New Password</label>
                        <?= isset($errors['new_password']) ? '<small class="form-text text-danger">' . $errors['new_password'] . '</small>' : '' ?>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control<?= isset($errors['confirm_password']) ? ' is-invalid' : '' ?>" name="confirm_password" id="confirm_password" placeholder="">
                        <label for="confirm_password">Confirm Password</label>
                        <?= isset($errors['confirm_password']) ? '<small class="form-text text-danger">' . $errors['confirm_password'] . '</small>' : '' ?>
                    </div>

                    <div class="form-group text-center mb-3">
                        <button type="submit" class="btn btn-primary text-center">ĐỔI</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    include_once $dir . 'footer.php';
    include_once $dir . 'scripts.php';
    ?>
    <script>
        $(document).ready(function() {});
        $(document).on('input', 'input', function() {
            $(this).siblings('.text-danger').empty();
            $(this).removeClass('is-invalid');
            $('.danger-all').remove();
        })
    </script>
</body>

</html>