<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <?php
    include_once 'app/views/layouts/styles.php'
    ?>
</head>

<body>
    <?php
    include_once 'app/views/layouts/header.php'
    ?>
    <div class="container" style="width: 600px;">
        <div class="row">
            <h3 class="text-center mb-3">CREATE PRODUCT</h3>
            <form action="" method="POST" enctype="multipart/form-data" class="form-floating">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>" id="name" name="name" placeholder="">
                    <label for="name">Name</label>
                    <?= isset($errors['name']) ? '<small class="form-text text-danger">' . $errors['name'] . '</small>' : '' ?>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input class="form-control" type="file" id="image" name="image">
                    <?= isset($errors['image']) ? '<small class="form-text text-danger">' . $errors['image'] . '</small>' : '' ?>
                    <div id="imagePreview" class="mt-2"></div>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control<?= isset($errors['price']) ? ' is-invalid' : '' ?>" id="price" name="price" placeholder="">
                    <label for="price">Price</label>
                    <?= isset($errors['price']) ? '<small class="form-text text-danger">' . $errors['price'] . '</small>' : '' ?>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select<?= isset($errors['category']) ? ' is-invalid' : '' ?>" id="category" name="category" aria-label="Floating label select example">
                        <option selected>--- Choose Category ---</option>
                        <?php
                        foreach ($categories as $row) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="category">Categories</label>
                    <?= isset($errors['category']) ? '<small class="form-text text-danger">' . $errors['category'] . '</small>' : '' ?>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control<?= isset($errors['description']) ? ' is-invalid' : '' ?>" placeholder="" id="description" name="description" style="height: 100px"></textarea>
                    <label for="description">Description</label>
                    <?= isset($errors['description']) ? '<small class="form-text text-danger">' . $errors['description'] . '</small>' : '' ?>
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-primary text-center">Save</button>
                </div>
            </form>
        </div>
    </div>
    <?php
    include_once 'app/views/layouts/footer.php';
    include_once 'app/views/layouts/scripts.php';
    ?>
    <script>
        $(document).ready(() => {});
        $('input').on('input', function() {
            // Xóa thông báo lỗi cho username
            $(this).siblings('.text-danger').empty();
            $(this).removeClass('is-invalid');
        });
        $('select').on('change', function() {
            // Xóa thông báo lỗi cho username
            $(this).siblings('.text-danger').empty();
            $(this).removeClass('is-invalid');
        });
        // Sự kiện khi chọn tệp hình ảnh
        $('#image').change(function() {
            // Kiểm tra xem có tệp được chọn không
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                // Sự kiện khi đọc hoàn thành
                reader.onload = function(e) {
                    // Hiển thị hình ảnh đã đọc trong thẻ <img>
                    $('#imagePreview').html('<img src="' + e.target.result + '" class="img-thumbnail">');
                }

                // Đọc tệp hình ảnh
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>

</html>