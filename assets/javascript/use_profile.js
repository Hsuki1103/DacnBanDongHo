// Hiển thị form chỉnh sửa khi nhấn nút "Chỉnh sửa thông tin"
document.getElementById("editButton").addEventListener("click", function () {
  document.getElementById("editForm").style.display = "block";
  document.querySelector(".user-info").style.display = "none";
});

// Hủy bỏ chỉnh sửa và quay lại thông tin cá nhân
document.getElementById("cancelButton").addEventListener("click", function () {
  document.getElementById("editForm").style.display = "none";
  document.querySelector(".user-info").style.display = "block";
});
