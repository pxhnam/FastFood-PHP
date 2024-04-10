<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Orders</title>
  <?php
  include_once 'app/views/layouts/styles.php'
  ?>
</head>

<body>
  <?php
  include_once 'app/views/layouts/header.php'
  ?>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-9">
        <h3 class="text-center mb-3">LIST ORDER</h3>
        <div class="d-flex justify-content-between align-items-center">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" name="searchKey" id="inputSearch" placeholder="" style="width: 300px;">
            <label for="inputSearch">Enter search keyword...</label>
          </div>
        </div>
        <table id="tbl-Orders" class="table table-bordered text-center">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Tên Khách Hàng</th>
              <th scope="col">Tổng</th>
              <th scope="col">Ngày Tạo</th>
              <th scope="col">Chi Tiết</th>
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
  include_once 'app/views/layouts/footer.php';
  include_once 'app/views/layouts/scripts.php';
  ?>
  <script>
    var timer;
    $(document).ready(function() {
      loadData('');
    })

    $('#inputSearch').on('input', function() {
      clearTimeout(timer);
      timer = setTimeout(() => {
        loadData($(this).val(), 1);
      }, 500);
    });

    function loadData(search) {
      $.ajax({
        url: '/api/getOrders',
        data: {
          search
        },
        type: 'POST',
        success: (data) => {
          $('#tbl-Orders').find('tbody').empty();
          $(data.orders).each(function(i, v) {
            $('#tbl-Orders').find('tbody').append(
              createRow(
                v.id, v.content.split('-')[0], v.total, v.order_date
              )
            );
          });
        },
        error: () => {}
      })
    }

    function showModal(title, body) {
      let $this = $('.modal');
      $this.find('.modal-title').text(title);
      $this.find('.modal-dialog').addClass('modal-lg');
      $this.find('.modal-body').empty().append(body);
      $this.find('.btn-primary').remove();
      $this.modal('show');
    }

    function createBox(name, date, content, details, total) {
      let tableRows = '';
      $(details).each(function(index, value) {
        tableRows += `<tr>
                        <th scope="row">${(index + 1)}</th>
                        <td>${value.product.name}</td>
                        <td>${value.quantity}</td>
                        <td>${formatCurrency(value.price)}</td>
                        <td>${formatCurrency(value.price * value.quantity)}</td>
                      </tr>`;
      })
      return `<p>Tên Khách Hàng: ${name}</p>
              <p>Ngày đặt: ${date}</p>
              <p>Ghi chú: ${content}</p>
              <table id="tbl-Orders" class="table table-bordered text-center">
                  <thead>
                      <tr>
                          <th scope="col">#</th>
                          <th scope="col">Sản phẩm</th>
                          <th scope="col">Số lượng</th>
                          <th scope="col">Giá</th>
                          <th scope="col">Thành tiền</th>
                      </tr>
                  </thead>
                  <tbody>${tableRows}</tbody>
              </table>
              <p class="text-end fw-bolder">TỔNG: ${formatCurrency(total)}</p>`;
    }

    function createRow(id, name, total, date) {
      return `<tr data-id='${id}'>
                  <th scope="row">${id}</th>
                  <td>${name}</td>
                  <td>${formatCurrency(total)}</td>
                  <td>${(date)}</td>
                  <td>
                    <a href="#" class="text-decoration-none">Xem</a>
                  </td>
              </tr>`;
    }
    //formattedDate
  </script>
</body>

</html>