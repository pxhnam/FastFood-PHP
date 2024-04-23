<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ajaxStart(function() {});

    $(document).ajaxStop(function() {});

    function toast({
        title = '',
        message = '',
        type = 'info',
        duration = 3000
    }) {
        const main = document.getElementById('toast');
        if (main) {
            const toast = document.createElement('div');

            const autoRemoveId = setTimeout(function() {
                main.removeChild(toast);
            }, duration + 1000);

            toast.onclick = function(e) {
                if (e.target.closest('.toast__close')) {
                    main.removeChild(toast);
                    clearTimeout(autoRemoveId);
                }
            };

            const icons = {
                success: 'fas fa-check-circle',
                info: 'fas fa-info-circle',
                warning: 'fas fa-exclamation-circle',
                error: 'fas fa-exclamation-circle',
            };
            const icon = icons[type];
            const delay = (duration / 1000).toFixed(2);

            toast.classList.add('toast-box', `toast--${type}`);
            toast.style.animation = `slideInLeft ease .3s, fadeOut linear 1s ${delay}s forwards`;

            toast.innerHTML = `
                      <div class="toast__icon">
                          <i class="${icon}"></i>
                      </div>
                      <div class="toast__body">
                          <h3 class="toast__title">${title}</h3>
                          <p class="toast__msg">${message}</p>
                      </div>
                      <div class="toast__close">
                          <i class="fas fa-times"></i>
                      </div>
                  `;
            main.appendChild(toast);
        }
    }

    function countCart() {
        $.ajax({
            url: '/cart/count',
            type: 'GET',
            success: (data) => {
                if (data.success) {
                    $('#count-cart').text(data.count);
                }
            },
            error: () => {}
        })
    }

    function formatCurrency(price) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(price)
    }

    function calculateTotal(priceString, quantity) {
        const price = parseInt(priceString.replace(/\./g, '').replace('đ', ''));

        if (isNaN(price)) {
            console.error('Invalid price string!');
            return null;
        }

        return formatCurrency(price * quantity);
    }
</script>