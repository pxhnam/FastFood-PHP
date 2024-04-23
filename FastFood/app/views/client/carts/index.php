<?php $dir = 'app/views/client/layouts/' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <?php include_once $dir . 'styles.php' ?>
</head>

<body>
    <?php include_once $dir . 'header.php' ?>
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-8 card mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h5 class="mb-0">Cart</h5>
                    <a href="#" class="text-decoration-none text-danger" id="btnClearCart">Clear</a>
                </div>
                <div class="card-body" id="main-cart"></div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4" id="box-information" data-id="0">
                    <div class="card-header py-3">
                        <h5 class="mb-0">Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-floating flex-fill me-1">
                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="">
                                <label for="first_name">Firt name</label>
                            </div>
                            <div class="form-floating flex-fill ms-1">
                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="">
                                <label for="last_name">Last name</label>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="phone-number" id="phone-number" placeholder="">
                            <label for="phone-number">Phone number</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="address" id="address" placeholder="">
                            <label for="address">Address</label>
                        </div>
                        <?php if (isset($_SESSION['User'])) : ?>
                            <a href="#" class="text-decoration-none" id="btn-address">
                                Sử dụng địa chỉ cũ
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <!--  -->
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h5 class="mb-0">Summary</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                Products
                                <span class="total-bill"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                Shipping
                                <span>Gratis</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                <div>
                                    <strong>Total amount</strong>
                                    <strong>
                                        <p class="mb-0">(including VAT)</p>
                                    </strong>
                                </div>
                                <span><strong class="total-bill"></strong></span>
                            </li>
                        </ul>

                        <button type="button" id="btn-checkout" class="btn btn-primary btn-lg btn-block">
                            Go to checkout
                            <i class="bi bi-caret-right-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once $dir . 'footer.php';
    include_once $dir . 'scripts.php';
    ?>
    <script>
        $(document).ready(function() {
            loadData();
        });

        function loadData() {
            $.ajax({
                url: '/api/getCarts',
                type: 'GET',
                success: (data) => {
                    $('#main-cart').empty();
                    $.each(data, (index, value) => {
                        $('#main-cart').append(
                            createCard(
                                value.id,
                                value.image,
                                value.name,
                                value.category_name,
                                value.price,
                                value.quantity
                            ));
                    });
                },
                error: () => {},
                complete: () => {
                    setTotal();
                }
            })
        }

        $("#btn-address").click(function() {
            $.ajax({
                url: '/api/getInformation',
                type: 'GET',
                success: (data) => {
                    if (data.success) {
                        $('#phone-number').val(data['0'].phone_number);
                        $('#address').val(data['0'].address);
                        $('#box-information').attr('data-id', data['0'].id);
                    } else {
                        toast({
                            title: 'Thông Báo',
                            message: data.message,
                            type: 'info',
                            duration: 5000
                        });
                    }
                },
                error: () => {
                    console.log('error');
                }
            })
        });
        $("#btn-checkout").click(function() {
            $.ajax({
                url: '/api/checkout',
                type: 'POST',
                data: {
                    id: $('#box-information').data('id'),
                    name: $('#first_name').val() + ' ' + $('#last_name').val(),
                    phone_number: $('#phone-number').val(),
                    address: $('#address').val()
                },
                success: (data) => {
                    if (data.success) {
                        clearForm();
                        countCart();
                    }
                    toast({
                        title: 'Thông Báo',
                        message: data.message,
                        type: data.type,
                        duration: 5000
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error:', textStatus, errorThrown);
                }
            })
        });

        $(document).on('input', 'input[name="quantity"]', function() {
            let parent = $(this).closest('.cart-item');
            if ($(this).val() < 1) {
                $(this).val(1);
            }
            changeQuantity(parent.data('id'), $(this).val());
            setTotalItem($(this));
        });
        $(document).on('click', '.btn-down', function() {
            let parent = $(this).closest('.cart-item');
            if (parent.find('input').val() < 1) {
                parent.find('input').val(1);
            }
            changeQuantity(parent.data('id'), parent.find('input').val());
            setTotalItem($(this));
        });
        $(document).on('click', '.btn-up', function() {
            let parent = $(this).closest('.cart-item');
            if (parent.find('input').val() < 1) {
                parent.find('input').val(1);
            }
            changeQuantity(parent.data('id'), parent.find('input').val());
            setTotalItem($(this));
        });

        $(document).on('click', '#btnRemoveCart', function() {
            let $this = $(this).closest('.cart-item');
            $.ajax({
                url: '/cart/remove',
                type: 'POST',
                data: {
                    id: $this.data('id')
                },
                success: (data) => {
                    loadData();
                    countCart();
                },
                error: () => {}
            })
        });

        $('#btnClearCart').click(function() {
            $.ajax({
                url: '/cart/clear',
                type: 'GET',
                success: () => {
                    loadData();
                    countCart();
                    $('.total-bill').text('0đ');
                },
                error: () => {}
            })
        });

        function changeQuantity(id, quantity) {
            $.ajax({
                url: 'cart/setQuantity',
                type: 'POST',
                data: {
                    id,
                    quantity
                },
                complete: () => {
                    setTotal();
                }
            })
        }

        function setTotalItem($this) {
            let parent = $this.closest('.cart-item');
            parent.find('.total-price').text(
                calculateTotal(parent.find('.price').text(),
                    parent.find('input').val()
                )
            );
        }
        $('#address').on('input', function() {
            $('#box-information').attr('data-id', '0');
        })
        $('#phone-number').on('input', function() {
            $('#box-information').attr('data-id', '0');
        })

        function setTotal() {
            let total = 0;
            $('.cart-item').each(function() {
                let cost = parseInt(
                    $(this).find('.total-price').text().replace(/\./g, '').replace('đ', '')
                );
                total += cost;
            });
            $('.total-bill').text(formatCurrency(total));
        }

        function clearForm() {
            $('#first_name').val('');
            $('#last_name').val('');
            $('#phone-number').val('');
            $('#address').val('');
            $('.total-bill').text('0đ');
            $('#main-cart').empty();
        }

        function showModal(title, body) {
            let $this = $('.modal');
            $this.find('.btn-primary').remove();
            $this.find('.modal-title').text(title);
            $this.find('.modal-body').empty().append(body);
            $this.modal('show');
        }

        function createCard(id, img, name, category, price, quantity) {
            return (` <div class="row cart-item" data-id="${id}">
                        <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                            <div class="bg-image hover-overlay hover-zoom ripple rounded" data-mdb-ripple-color="light">
                                <img src="/uploads/${img}" class="w-100" alt="Blue Jeans Jacket">
                                <a href="#!">
                                    <div class="mask" style="background-color: rgba(251, 251, 251, 0.2)">
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                            <p><strong>${name}</strong></p>
                            <p>Category: ${category}</p>
                            <p class="text-start">
                                Giá:
                                <strong class="price">${formatCurrency(price)}</strong>
                            </p>
                            <button id="btnRemoveCart" type="button" class="btn btn-danger btn-sm me-1 mb-2" data-mdb-toggle="tooltip" title="Remove item">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                            <div class="d-flex mb-4" style="max-width: 300px">
                                <button class="btn-down btn btn-primary px-3 me-2" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <div class="form-floating">
                                    <input id="form1" min="0" name="quantity" value="${quantity}" type="number" class="form-control">
                                    <label for="form1">Quantity</label>
                                </div>
                                <button class="btn-up btn btn-primary px-3 ms-2" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <p class="text-start text-md-center">
                                Thành tiền:
                                <strong class="total-price">${formatCurrency(price*quantity)}</strong>
                            </p>
                        </div>
                    </div>
                    <hr class="my-4">`);
        }
    </script>
</body>

</html>