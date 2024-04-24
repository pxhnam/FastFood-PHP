<?php $dir = 'app/views/client/layouts/' ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <?php include_once $dir . 'styles.php' ?>
</head>

<body>
    <?php include_once $dir . 'header.php' ?>
    <div class="container" style="width: 600px;">
        <div class="row">
            <h3 class="text-center mb-3">UPDATE PRODUCT</h3>
            <form action="" method="POST" enctype="multipart/form-data" class="form-floating">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>" id="name" name="name" placeholder="" value="<?= $product->name ?>">
                    <label for="name">Name</label>
                    <?= isset($errors['name']) ? '<small class="form-text text-danger">' . $errors['name'] . '</small>' : '' ?>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label btn btn-outline-secondary">
                        <i class="far fa-image"></i>
                    </label>
                    <input class="form-control d-none" type="file" id="image" name="image">
                    <?= isset($errors['image']) ? '<small class="form-text text-danger">' . $errors['image'] . '</small>' : '' ?>
                    <div id="imagePreview" class="mt-2">
                        <img src="/uploads/<?= $product->image ?>" alt="" width="600px" class="img-thumbnail">
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control<?= isset($errors['price']) ? ' is-invalid' : '' ?>" id="price" name="price" placeholder="" value="<?= $product->price ?>">
                    <label for="price">Price</label>
                    <?= isset($errors['price']) ? '<small class="form-text text-danger">' . $errors['price'] . '</small>' : '' ?>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select<?= isset($errors['category']) ? ' is-invalid' : '' ?>" id="category" name="category" aria-label="Floating label select example">
                        <option>--- Choose Category ---</option>
                        <?php
                        foreach ($categories as $row) {
                            $selected = ($row['id'] == $product->category_id) ? 'selected' : '';
                            echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="category">Categories</label>
                    <?= isset($errors['category']) ? '<small class="form-text text-danger">' . $errors['category'] . '</small>' : '' ?>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control<?= isset($errors['description']) ? ' is-invalid' : '' ?>" placeholder="" id="description" name="description" style="height: 100px"><?= $product->description ?></textarea>
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
    include_once $dir . 'footer.php';
    include_once $dir . 'scripts.php';
    ?>
    <script>
        $(document).ready(() => {});

        $('input').on('input', function() {
            $(this).siblings('.text-danger').empty();
            $(this).removeClass('is-invalid');
        });

        $('select').on('change', function() {
            $(this).siblings('.text-danger').empty();
            $(this).removeClass('is-invalid');
        });

        $('#image').change(function() {

            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#imagePreview').html('<img src="' + e.target.result + '" class="img-thumbnail">');
                }

                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>

</html>