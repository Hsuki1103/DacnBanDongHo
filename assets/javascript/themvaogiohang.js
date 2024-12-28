document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".tg-btn2").forEach((button) => {
    button.addEventListener("click", function () {
      const id_dongho = this.getAttribute("data-madh");

      // Gửi yêu cầu AJAX để thêm vào giỏ hàng
      fetch("./themvaogiohang.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ madh: id_dongho }),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Không thể kết nối tới server.");
          }
          return response.json();
        })
        .then((data) => {
          if (data.success) {
            alert("Thêm vào giỏ hàng thành công!");
            updateCart(); // Cập nhật giao diện giỏ hàng
          } else {
            alert(data.message || "Đã xảy ra lỗi!");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert(
            "Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng. Vui lòng thử lại sau!"
          );
        });
    });
  });

  // Hàm gọi API để cập nhật giao diện giỏ hàng
  function updateCart() {
    fetch("./laygiohang.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Không thể lấy dữ liệu giỏ hàng.");
        }
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          // Cập nhật HTML cho giỏ hàng
          const cartBody = document.querySelector(".tg-minicartbody");
          const cartFoot = document.querySelector(".tg-minicartfoot");
          if (cartBody) {
            cartBody.innerHTML = data.cart_html || "<p>Giỏ hàng trống</p>";
          }
          if (cartFoot) {
            const totalItems = data.total_items || 0;
            document.querySelector(".tg-themebadge").innerText = totalItems;
            cartFoot.style.display = totalItems > 0 ? "block" : "none";
          }
        }
      })
      .catch((error) => console.error("Error:", error));
  }
});