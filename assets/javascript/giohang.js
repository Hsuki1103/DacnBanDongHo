document.addEventListener("DOMContentLoaded", function () {
  // Tích tất cả các checkbox mặc định
  const checkboxes = document.querySelectorAll(
    'input[type="checkbox"][name="chondh"]'
  );
  checkboxes.forEach((checkbox) => {
    checkbox.checked = true; // Tích checkbox mặc định
  });

  // Cập nhật tổng tiền khi tải trang
  updateTotalPrice();

  // Lắng nghe sự kiện thay đổi trên các checkbox
  checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      updateTotalPrice();
    });
  });

  // Lắng nghe sự kiện thay đổi số lượng
  const quantityInputs = document.querySelectorAll(".quantity-input");
  quantityInputs.forEach((input) => {
    input.addEventListener("input", function () {
      const productId = this.id.split("_")[1]; // Lấy mã sách từ id input
      const quantity = parseInt(this.value) || 1; // Lấy giá trị số lượng, mặc định là 1 nếu không hợp lệ
      this.value = Math.max(quantity, 1); // Đảm bảo số lượng không nhỏ hơn 1
      updateProductPrice(productId, this.value);
      updateTotalPrice();
      updateProductQuantityInDatabase(productId, this.value); // Cập nhật cơ sở dữ liệu
    });
  });
});

// Hàm cập nhật tổng tiền
function updateTotalPrice() {
  let totalPrice = 0;
  const checkboxes = document.querySelectorAll(
    'input[type="checkbox"][name="chondh"]'
  );
  checkboxes.forEach((checkbox) => {
    if (checkbox.checked) {
      const productId = checkbox.id.split("_")[1]; // Lấy mã sách từ id checkbox
      const quantity =
        parseInt(document.getElementById("quantity_" + productId).value) || 1;
      const price = parseFloat(
        document.getElementById("price_" + productId).dataset.price
      ); // Giá mỗi sản phẩm
      totalPrice += quantity * price; // Cộng tổng tiền
    }
  });

  // Cập nhật tổng tiền vào giao diện
  const shippingFee = 32000; // Phí vận chuyển cố định
  document.querySelector(".summary .price").textContent =
    (totalPrice + shippingFee).toLocaleString("vi-VN") + " VND";
}

// Hàm cập nhật giá từng sản phẩm
function updateProductPrice(productId, quantity) {
  const priceElement = document.getElementById("price_" + productId);
  const price = parseFloat(priceElement.dataset.price); // Giá gốc của sản phẩm
  const totalPrice = quantity * price;
  priceElement.textContent = totalPrice.toLocaleString("vi-VN") + " VND";
}

// Hàm gửi yêu cầu AJAX để cập nhật số lượng sản phẩm trong cơ sở dữ liệu
function updateProductQuantityInDatabase(productId, quantity) {
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "update_quantity.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  // Gửi dữ liệu tới server
  xhr.send("productId=" + productId + "&quantity=" + quantity);

  // Xử lý phản hồi từ server (nếu cần)
  xhr.onload = function () {
    if (xhr.status === 200) {
      console.log("Số lượng đã được cập nhật thành công trong cơ sở dữ liệu");
      // Tải lại giỏ hàng sau khi cập nhật
      updateTotalPrice();
    } else {
      console.error("Có lỗi khi cập nhật số lượng");
    }
  };
}

$(document).ready(function () {
  $("form").submit(function () {
    // Duyệt qua tất cả các sản phẩm trong giỏ hàng
    $(".product").each(function () {
      var madh = $(this)
        .find("input[type='number']")
        .attr("id")
        .replace("quantity_", "");
      var quantity = $(this).find("input[type='number']").val();

      // Cập nhật giá trị số lượng vào hidden input trong form
      $("input[name='cart_items']").val(function (index, currentValue) {
        var cartData = JSON.parse(currentValue);
        for (var i = 0; i < cartData.length; i++) {
          if (cartData[i].madh == madh) {
            cartData[i].soluong = quantity; // Cập nhật số lượng
          }
        }
        return JSON.stringify(cartData);
      });
    });
  });
});

$(document).ready(function () {
  // Cập nhật lại tổng tiền mỗi khi người dùng thay đổi số lượng
  $(".quantity-input").on("input", function () {
    var madh = $(this).attr("id").replace("quantity_", "");
    var quantity = $(this).val();
    var price = $("#price_" + madh).data("price");

    // Cập nhật lại tổng tiền cho từng sản phẩm
    var totalPrice = price * quantity;
    $("#price_" + madh).text(formatCurrency(totalPrice));

    // Cập nhật lại tổng tiền cho cả giỏ hàng
    var totalCartPrice = 0;
    $(".product").each(function () {
      var price = $(this).find(".price").data("price");
      var quantity = $(this).find(".quantity-input").val();
      totalCartPrice += price * quantity;
    });

    // Cập nhật tổng tiền trong form
    var shippingFee = 32000; // Phí vận chuyển cố định
    var totalAmount = totalCartPrice + shippingFee;
    $(".price").text(formatCurrency(totalAmount));
    $("input[name='tongtien']").val(totalAmount); // Gửi tổng tiền vào form
  });

  // Định dạng số tiền
  function formatCurrency(value) {
    return value.toLocaleString("vi-VN") + " VND";
  }
});
document.addEventListener("DOMContentLoaded", function () {
  // Lắng nghe sự kiện thay đổi số lượng
  const quantityInputs = document.querySelectorAll(".quantity-input");
  quantityInputs.forEach((input) => {
      input.addEventListener("input", function () {
          const productId = this.id.split("_")[1]; // Lấy mã sản phẩm từ id input
          const quantity = parseInt(this.value) || 1; // Lấy giá trị số lượng, mặc định là 1 nếu không hợp lệ
          this.value = Math.max(quantity, 1); // Đảm bảo số lượng không nhỏ hơn 1

          // Gửi yêu cầu AJAX để cập nhật số lượng sản phẩm
          updateProductQuantityInDatabase(productId, this.value);
      });
  });
});

// Hàm gửi yêu cầu AJAX để cập nhật số lượng sản phẩm trong cơ sở dữ liệu
function updateProductQuantityInDatabase(productId, quantity) {
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "update_quantity.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  // Gửi dữ liệu tới server
  xhr.send("productId=" + productId + "&quantity=" + quantity);

  // Xử lý phản hồi từ server (nếu cần)
  xhr.onload = function () {
      if (xhr.status === 200) {
          console.log("Số lượng đã được cập nhật thành công trong cơ sở dữ liệu");
          // Tải lại toàn bộ trang sau khi cập nhật
          location.reload();
      } else {
          console.error("Có lỗi khi cập nhật số lượng");
      }
  };
}
