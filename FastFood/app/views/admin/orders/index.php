<?php $dir = 'app/views/client/layouts/' ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Orders</title>
  <?php include_once $dir . 'styles.php' ?>
</head>

<body>
  <?php include_once $dir . 'header.php' ?>
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
  include_once $dir . 'footer.php';
  include_once $dir . 'scripts.php';
  ?>
  <script>
    var timer;
    var page = 1;
    $(document).ready(function() {
      loadData('', 1);
    })

    $('#inputSearch').on('input', function() {
      clearTimeout(timer);
      timer = setTimeout(() => {
        loadData($(this).val(), 1);
      }, 500);
    });

    function loadData(search, page) {
      $.ajax({
        url: '/api/getOrders',
        data: {
          search,
          page
        },
        type: 'POST',
        success: (data) => {
          if (data.success) {
            $('.pagination').empty();
            $('#tbl-Orders').find('tbody').empty();
            $(data.orders).each(function(i, v) {
              $('#tbl-Orders').find('tbody').append(
                createRow(
                  v.id, v.content.split('-')[0], v.total, v.order_date
                )
              );
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

    $(document).on('click', '#btn-detail', function() {
      let id = $(this).closest('tr').data('id');
      $.ajax({
        url: '/api/order',
        data: {
          id
        },
        type: 'POST',
        success: (res) => {
          if (res.success) {
            let data = res.order
            console.log();
            showModal('Chi Tiết Đơn Hàng',
              createBox(
                data[0].content.split('-')[0],
                data[0].order_date,
                data[0].content,
                data,
                data[0].total,
              )
            );
          }
        },
        error: () => {}
      })
    })

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
                        <td>${value.name}</td>
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
                    <a id="btn-detail" href="#" class="text-decoration-none">Xem</a>
                  </td>
              </tr>`;
    }
  </script>
</body>

</html>