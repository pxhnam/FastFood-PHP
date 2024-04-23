<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">FF</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Trang Chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                </li>
            </ul>
            <ul class="navbar-nav mb-2 mb-lg-0">
                <a type="button" class="btn btn-dark position-relative" style="font-size: 18px;" href="/Cart">
                    <i class="bi bi-cart3"></i>
                    <span id="count-cart" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo isset($_SESSION['Cart']) ? count($_SESSION['Cart']) : '0'; ?>
                    </span>
                </a>
                <?php if (isset($_SESSION['User'])) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Xin chào, <?= $_SESSION['User']['username']; ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/password">Đổi Mật Khẩu</a></li>
                            <?php if ($_SESSION['User']['role'] === 'admin') : ?>
                                <li><a class="dropdown-item" href="/admin/product">Sản Phẩm</a></li>
                                <li><a class="dropdown-item" href="/admin/category">Loại SP</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="/admin/order">Đơn hàng</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="/logout">Đăng Xuất</a></li>
                        </ul>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Đăng Nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Đăng Ký</a>
                    </li>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>