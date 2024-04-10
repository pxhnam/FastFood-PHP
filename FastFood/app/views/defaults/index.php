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
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="searchKey" id="inputSearch" placeholder="" style="width: 300px;">
            <label for="inputSearch">Tìm kiếm</label>
        </div>
        <div class="row" id="main">
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

        function loadData(search) {
            $.ajax({
                url: '/api/getProducts',
                data: {
                    search
                },
                type: 'POST',
                success: (data) => {
                    if (data.success) {
                        $('#main').empty();
                        $(data.products).each(function(i, e) {
                            $('#main').append(cardBox(e.id, e.name, e.description, e.image));
                        });
                    }
                }
            });
        }
        $('#inputSearch').on('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                loadData($(this).val());
            }, 500);
        })
        $(document).on('click', '#btnInfor', function() {
            $.ajax({
                url: 'api/getProduct',
                type: 'POST',
                data: {
                    id: $(this).data('id')
                },
                success: (res) => {
                    showModal(
                        'Thông Tin Sản Phẩm',
                        createRow(res.image, res.name, res.price, res.category_name, res.description)
                    );
                },
                error: () => {
                    console.log('ERROR');
                }
            })
        });

        $(document).on('click', '#btnAddToCart', function() {
            $.ajax({
                url: 'cart/add',
                type: 'POST',
                data: {
                    id: $(this).data('id'),
                    quantity: 1
                },
                success: (res) => {
                    if (res.success) {
                        toast({
                            title: 'Thành công!',
                            message: 'Thêm thành công ' +
                                $(this).closest('.card').find('h5.card-title').text() +
                                ' vào giỏ hàng.',
                            type: 'success',
                            duration: 5000
                        });
                        countCart();
                    } else
                        toast({
                            title: 'Thất bại!',
                            message: 'Thêm thất bại ' +
                                $(this).closest('.card').find('h5.card-title').text() +
                                ' vào giỏ hàng.',
                            type: 'error',
                            duration: 5000
                        });
                },
                error: () => {
                    console.log('ERROR');
                }
            })
        });

        function cardBox(id, name, desc, img) {
            return `<div class="col-md-3 mb-4">
                        <div class="card" style="width: 18rem;">
                            <img src="/uploads/${img}" 
                            class="card-img-top" 
                            alt="${name}" 
                            height="280px">
                            <div class="card-body">
                                <h5 class="card-title">
                                <a id="btnInfor" 
                                data-id="${id}" 
                                href="#" 
                                class="text-decoration-none">${name}
                                </a>
                                </h5>
                                <p class="card-text">${desc}</p>
                                <button id="btnAddToCart"
                                 data-id="${id}" 
                                 class="btn btn-primary">Thêm vào giỏ hàng
                                </button>
                            </div>
                        </div>
                    </div>`;
        }

        function showModal(title, body) {
            let $this = $('.modal');
            $this.find('.btn-primary').remove();
            $this.find('.modal-title').text(title);
            $this.find('.modal-body').empty().append(body);
            $this.find('.modal-dialog').addClass('modal-xl');
            $this.modal('show');
        }

        function createRow(img, name, price, category, desc) {
            return (`<div class="row">
                    <div class="col-md-5">
                        <img src="/uploads/${img}" alt="${name}" width="450px">
                    </div>
                    <div class="col-md-7">
                        <h1 class="text-dark">${name}</h1>
                        <h3>Price: <strong class="text-warning">${formatCurrency(price)}</strong></h3>
                        <h5>Category:
                            <strong>
                                <a href="#" class="text-decoration-none">${category}</a>
                            </strong>
                        </h5>
                        <p>Description: ${desc}</p>
                    </div>
                </div>`);
        }
    </script>
</body>

</html>