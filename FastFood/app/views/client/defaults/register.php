<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <?php
    include_once 'app/views/client/layouts/styles.php'
    ?>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-3">
                <h3 class="text-center mt-5 mb-3">ĐĂNG KÝ</h3>
                <?= isset($errors['all']) ? '<p class="text-danger text-center danger-all">' . $errors['all'] . '</p>' : '' ?>

                <form action="" method="POST">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-floating flex-fill me-1">
                            <input type="text" class="form-control<?= isset($errors['first_name']) ? ' is-invalid' : '' ?>" name="first_name" id="first_name" placeholder="" value="<?= $first_name ?? '' ?>" autofocus>
                            <label for="first_name">Firt name</label>
                            <?= isset($errors['first_name']) ? '<small class="form-text text-danger">' . $errors['first_name'] . '</small>' : '' ?>
                        </div>
                        <div class="form-floating flex-fill ms-1">
                            <input type="text" class="form-control<?= isset($errors['last_name']) ? ' is-invalid' : '' ?>" name="last_name" id="last_name" placeholder="" value="<?= $last_name ?? '' ?>">
                            <label for="last_name">Last name</label>
                            <?= isset($errors['last_name']) ? '<small class="form-text text-danger">' . $errors['last_name'] . '</small>' : '' ?>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control<?= isset($errors['username']) ? ' is-invalid' : '' ?>" name="username" id="inputUsername" placeholder="" value="<?= $username ?? '' ?>">
                        <label for="inputUsername">Username</label>
                        <?= isset($errors['username']) ? '<small class="form-text text-danger">' . $errors['username'] . '</small>' : '' ?>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control<?= isset($errors['password']) ? ' is-invalid' : '' ?>" name=" password" id="inputPassword" placeholder="">
                        <label for="inputPassword">Password</label>
                        <?= isset($errors['password']) ? '<small class="form-text text-danger">' . $errors['password'] . '</small>' : '' ?>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-floating mb-3">
                            <select class="form-select<?= isset($errors['day']) ? ' is-invalid' : '' ?>" id="day" name="day" aria-label="Floating label select example">
                                <option value="0">---</option>
                            </select>
                            <label for="day">Day</label>
                            <?= isset($errors['day']) ? '<small class="form-text text-danger">' . $errors['day'] . '</small>' : '' ?>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select<?= isset($errors['month']) ? ' is-invalid' : '' ?>" id="month" name="month" aria-label="Floating label select example">
                                <option value="0">---</option>
                            </select>
                            <label for="month">Month</label>
                            <?= isset($errors['month']) ? '<small class="form-text text-danger">' . $errors['month'] . '</small>' : '' ?>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select<?= isset($errors['year']) ? ' is-invalid' : '' ?>" id="year" name="year" aria-label="Floating label select example">
                                <option value="0">---</option>
                            </select>
                            <label for="year">Year</label>
                            <?= isset($errors['year']) ? '<small class="form-text text-danger">' . $errors['year'] . '</small>' : '' ?>
                        </div>
                    </div>
                    <div class="box-genders text-center mb-3">
                        <label>Gender: </label>
                        <?= isset($errors['gender']) ? '<small class="form-text text-danger">' . $errors['gender'] . '</small>' : '' ?>
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
    include_once 'app/views/client/layouts/scripts.php';
    ?>
    <script>
        $(document).ready(function() {
            makeDate();
            getSex();
        });
        $(document).on('input', 'input', function() {
            $(this).siblings('.text-danger').empty();
            $(this).removeClass('is-invalid');
            $('.danger-all').remove();
        })
        $(document).on('change', 'input[name="gender"]', function() {
            $('.box-genders').find('.text-danger').remove();
        })
        $(document).on('change', 'select', function() {
            $(this).siblings('.text-danger').empty();
            $(this).removeClass('is-invalid');
        })

        function getSex() {
            $.ajax({
                url: '/api/getSex',
                type: 'GET',
                success: (data) => {
                    data.genders.forEach(function(gender) {
                        var radioDiv = $('<div>').addClass('form-check form-check-inline');
                        var radioBtn = $('<input class="form-check-input" type="radio">').attr({
                            'name': 'gender',
                            'value': gender,
                            'id': 'inlineRadio' + gender
                        });
                        var label = $('<label>').addClass('form-check-label').attr('for', 'inlineRadio' + gender).text(gender);
                        radioDiv.append(radioBtn, label);
                        $('.box-genders').append(radioDiv);
                    });
                },
                error: () => {}
            })
        }

        function makeDate() {
            var daySelect = $('#day');
            for (var i = 1; i <= 31; i++) {
                daySelect.append($('<option></option>').attr('value', i).text(i));
            }
            var monthSelect = $('#month');
            for (var i = 1; i <= 12; i++) {
                monthSelect.append($('<option></option>').attr('value', i).text(i));
            }
            var yearSelect = $('#year');
            var currentYear = new Date().getFullYear();
            for (var i = 1900; i <= currentYear; i++) {
                yearSelect.append($('<option></option>').attr('value', i).text(i));
            }
        }
    </script>
</body>

</html>