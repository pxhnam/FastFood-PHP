<?php $dir = 'app/views/client/layouts/' ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <?php include_once $dir . 'styles.php' ?>
</head>

<body>
    <?php include_once $dir . 'header.php' ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-6">

                <h3 class="text-center mb-3">LIST CATEGORY</h3>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="searchKey" id="inputSearch" placeholder="" style="width: 300px;">
                        <label for="inputSearch">Enter search keyword...</label>
                    </div>
                    <a href="#" id="btnCreateCategory" class="btn btn-primary">CREATE</a>
                </div>
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center align-items-center py-5">
                <nav aria-label="Page navigation">
                    <ul class="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
    <?php
    include_once $dir . 'footer.php';
    include_once $dir . 'scripts.php';
    ?>
    <script>
        var id = '';
        var timer;
        var page = 1;
        $(document).ready(() => {
            loadData('', 1);
        });
        $('#inputSearch').on('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                loadData($(this).val(), 1);
            }, 500);
        });

        function loadData(search, page) {
            $.ajax({
                url: '/api/getCategories',
                data: {
                    search,
                    page
                },
                type: 'POST',
                success: (data) => {
                    if (data.success) {
                        $('tbody').empty();
                        $('.pagination').empty();
                        $(data.categories).each(function(i, v) {
                            $('tbody').append(createRow(v.id, (i + 1), v.name));
                        });
                        if (data.totalPages > 1) {
                            for (i = 1; i <= data.totalPages; i++) {
                                $('.pagination').append(`<li class="page-item${i == page ? ' active' : ' '}"><a class="page-link" href="#">${i}</a></li>`);
                            }
                        }
                    }
                },
                error: () => {}
            })
        }
        $('.pagination').on('click', 'li', function(e) {
            e.preventDefault();
            page = $(this).text();
            loadData($('#inputSearch').val(), page);
        })
        $('#btnCreateCategory').click(function() {
            id = '';
            showModal('Create Category',
                `<div class="form-floating mb-3">
                     <input type="text" class="form-control" id="name" name="name" placeholder="" autofocus>
                     <label for="name">Name</label>
                 </div>`);
        });
        $(document).on('click', '#btnEditCategory', function() {
            id = $(this).closest('tr').data('id');
            $.ajax({
                url: '/api/getCategory',
                data: {
                    id
                },
                type: 'POST',
                success: (data) => {
                    showModal('Update Category',
                        `<div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" name="name" 
                            value="${data.category.name}" placeholder="" autofocus>
                            <label for="name">Name</label>
                         </div>`);
                },
                error: () => {}
            })
        });
        $(document).on('click', '#btnSave', function() {
            $.ajax({
                url: '/api/updateCategory',
                data: {
                    id,
                    name: $('#name').val()
                },
                type: 'POST',
                success: (data) => {
                    if (data.success) {
                        $('.modal').modal('hide');
                        loadData($('#inputSearch').val(), 1);
                        toast({
                            title: "Thành công!",
                            message: data.message,
                            type: "success",
                            duration: 5000
                        });
                    } else {
                        toast({
                            title: "Thất bại!",
                            message: data.message,
                            type: "error",
                            duration: 5000
                        });
                    }
                },
                error: () => {}
            })
        })
        $(document).on('click', '#btnRemoveCategory', function() {
            $.ajax({
                url: '/api/removeCategory',
                data: {
                    id: $(this).closest('tr').data('id')
                },
                type: 'POST',
                success: (res) => {
                    if (res.success) {
                        toast({
                            title: "Thành Công",
                            message: res.message,
                            type: "success",
                            duration: 5000
                        });
                        loadData($('#inputSearch').val(), 1);
                    } else {
                        toast({
                            title: "Thất Bại",
                            message: res.message,
                            type: "error",
                            duration: 5000
                        });
                    }
                },
                error: () => {}
            });
        })

        function showModal(title, body) {
            let $this = $('.modal');
            $this.find('.modal-title').text(title);
            $this.find('.modal-body').empty().append(body);
            $this.find('.btn-primary').attr('id', 'btnSave');
            $this.modal('show');
        }

        function createRow(id, index, name) {
            return `<tr data-id='${id}'>
                        <th scope="row">${index}</th>
                        <td>${name}</td>
                        <td>
                            <a href="#" id="btnEditCategory" class="btn btn-success">
                                <i class="fas fa-pen"></i>
                            </a>
                            <button id="btnRemoveCategory" type="button" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
        }
    </script>
</body>

</html>