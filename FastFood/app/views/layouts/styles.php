<link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.3/components/error-404s/error-404-1/assets/css/error-404-1.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
<style>
    /* width */
    ::-webkit-scrollbar {
        width: 5px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background-color: #fafafa;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background-color: #ced4da;
    }

    .card-text {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        /* Số dòng muốn hiển thị */
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .img-thumbnail {
        border-radius: 12px;
    }

    .btn .badge {
        top: 5px !important;
        left: 35px !important
    }

    /* Ẩn nút tăng giảm cho tất cả các trình duyệt */
    input[type="number"] {
        -moz-appearance: textfield;
        /* Firefox */
    }

    /* Ẩn nút tăng giảm cho trình duyệt Chrome và Safari */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input,
    select,
    textarea,
    button,
    .page-link {
        box-shadow: none !important;
    }

    /* ======= Toast message ======== */

    #toast {
        position: fixed;
        top: 32px;
        right: 32px;
        z-index: 999999;
    }

    .toast-box {
        display: flex;
        align-items: center;
        background-color: #fff;
        border-radius: 2px;
        padding: 20px 0;
        min-width: 400px;
        max-width: 450px;
        border-left: 4px solid;
        box-shadow: 0 5px 8px rgba(0, 0, 0, 0.08);
        transition: all linear 0.3s;
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(calc(100% + 32px));
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
        }
    }

    .toast--success {
        border-color: #47d864;
    }

    .toast--success .toast__icon {
        color: #47d864;
    }

    .toast--info {
        border-color: #2f86eb;
    }

    .toast--info .toast__icon {
        color: #2f86eb;
    }

    .toast--warning {
        border-color: #ffc021;
    }

    .toast--warning .toast__icon {
        color: #ffc021;
    }

    .toast--error {
        border-color: #ff623d;
    }

    .toast--error .toast__icon {
        color: #ff623d;
    }

    .toast-box+.toast-box {
        margin-top: 24px;
    }

    .toast__icon {
        font-size: 24px;
    }

    .toast__icon,
    .toast__close {
        padding: 0 16px;
    }

    .toast__body {
        flex-grow: 1;
    }

    .toast__title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .toast__msg {
        font-size: 14px;
        color: #888;
        margin-top: 6px;
        line-height: 1.5;
    }

    .toast__close {
        font-size: 20px;
        color: rgba(0, 0, 0, 0.3);
        cursor: pointer;
    }
</style>