<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <?php
    include_once 'app/views/layouts/styles.php'
    ?>
</head>

<body>
    <?php
    include_once 'app/views/layouts/header.php'
    ?>
    <div class="container">
        <h3 class="text-center">DANH SÁCH SẢN PHẨM</h3>
        <div class="d-flex justify-content-between align-items-center">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="searchKey" id="inputSearch" placeholder="" style="width: 300px;">
                <label for="inputSearch">Tìm kiếm</label>
            </div>
            <a href="/product/create" class="btn btn-primary">Thêm</a>
        </div>
        <div class="row">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Image</th>
                        <th scope="col">Price</th>
                        <th scope="col">Category</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <?php
    include_once 'app/views/layouts/footer.php';
    include_once 'app/views/layouts/scripts.php';
    ?>
    <script>
        var timer;
        $(document).ready(() => {
            loadData('');
        });
        $('#inputSearch').on('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                loadData($(this).val());
            }, 500);
        })

        function loadData(search) {
            $.ajax({
                url: '/api/getProducts',
                data: {
                    search
                },
                type: 'POST',
                success: (data) => {
                    if (data.success) {
                        $('tbody').empty();
                        $(data.products).each(function(i, e) {
                            $('tbody').append(createRow(e.id, e.name, e.image, e.price, e.category_name));
                        });
                    }
                }
            })
        }
        $(document).on('click', '#btnRemoveProduct', function() {
            let id = $(this).closest('tr').data('id');
            console.log(id);
            $.ajax({
                url: '/api/removeProduct',
                data: {
                    id
                },
                type: 'POST',
                success: (data) => {
                    if (data.success) {
                        toast({
                            title: 'Thành Công',
                            message: data.message,
                            type: 'success',
                            duration: 5000
                        });
                        loadData();
                    } else {
                        toast({
                            title: 'Thất Bại',
                            message: data.message,
                            type: 'error',
                            duration: 5000
                        });
                    }
                },
                error: () => {}
            })
        })

        function createRow(id, name, image, price, category) {
            return `<tr data-id="${id}">
                        <th scope="row">${id}</th>
                        <td>${name}</td>
                        <td><img src="/uploads/${image}" alt="${name}" height="120px"></td>
                        <td>${formatCurrency(price)}</td>
                        <td>${category}</td>
                        <td>
                            <a href="/product/update/${id}" class="btn btn-success">
                                <i class="fas fa-pen"></i>
                            </a>
                            <button id="btnRemoveProduct" type="button" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
        }
    </script>
</body>

</html>