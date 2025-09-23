document.addEventListener("DOMContentLoaded", function () {
  var navbar = document.querySelector(".navbar");

  // 立即檢查滾動位置
  checkScrollPosition();

  window.onscroll = function () {
    // 在滾動時檢查滾動位置
    checkScrollPosition();
  };

  function checkScrollPosition() {
    if (
      document.body.scrollTop > 50 ||
      document.documentElement.scrollTop > 50
    ) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  }
});
