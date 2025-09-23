function clearFormFields() {
  document.getElementById("floatingID").value = "";
  document.getElementById("floatingPassword").value = "";
  document.getElementById("floatingeEail").value = "";
  document.getElementById("floatingDepartment").value = "";
  document.getElementById("floatingPhoneNumber").value = "";
}

function show_hide() {
  var signin = document.getElementById("signin-page");
  var signup = document.getElementById("signup-page");
  var light = document.getElementById("light");

  // if (signin.style.display === "none") {
  //     signin.style.display = "block";  //signin出現
  //     signup.style.display = "none";  //signup消失
  // } else {
  //     signin.style.display = "none";   //signin消失
  //     signup.style.display = "block"; //signup出現
  //     signup.style.visibility = "visible";
  // }
  if (signin.style.left === "50%") {
    light.style.visibility = "hidden";

    signin.style.left = "70%"; // 移動到左邊，使其消失
    signup.style.left = "50%"; // 移動到中間，使其出現
    signup.style.visibility = "visible"; // 顯示註冊頁面
    signin.style.visibility = "hidden"; // 隱藏登入頁面

    light.style.left = "50%";
    light.style.top = "50%";
    //light.style.visibility = "visible";
  } else {
    light.style.visibility = "hidden";

    signup.style.left = "70%"; // 移動到左邊，使其消失
    signin.style.left = "50%"; // 移動到中間，使其出現
    signin.style.visibility = "visible"; // 顯示登入頁面
    signup.style.visibility = "hidden"; // 隱藏註冊頁面

    light.style.left = "50%";
    light.style.top = "50%";
    //light.style.visibility = "visible";
  }

  clearFormFields();
}

function validateForm() {
  const ncyuEmailPattern = /@g\.ncyu\.edu\.tw$/;

  var passwrod = document.forms["registerForm"]["password"].value;
  // var y = document.forms["registerForm"]["password_check"].value;
  var email = document.forms["registerForm"]["mail"].value;

  if (passwrod.length < 6) {
    alert("密碼長度不足");
    return false;
  }
  // if (x != y) {
  //     alert("請確認密碼是否輸入正確");
  //     return false;
  // }
  if (ncyuEmailPattern.test(email) == 0) {
    alert("請使用 g.ncyu.edu.tw 的email");
    return false;
  }
}

document.addEventListener("DOMContentLoaded", function () {
  // 獲取div元素
  var light = document.getElementById("light");
  var card1 = document.getElementById("signin-page");
  var card2 = document.getElementById("signup-page");

  // 定義一個函數，用來更新div元素的位置
  function updateLightPosition(event) {
    // 獲取滑鼠的位置
    var mouseX = event.clientX;
    var mouseY = event.clientY;

    if (card1.style.visibility == "visible") {
      var cardRect = card1.getBoundingClientRect();
    } else if (card2.style.visibility == "visible") {
      var cardRect = card2.getBoundingClientRect();
    }

    var offset = 150;

    if (
      mouseX >= cardRect.left - offset &&
      mouseX <= cardRect.right + offset &&
      mouseY >= cardRect.top - offset &&
      mouseY <= cardRect.bottom + offset
    ) {
      if (light.style.visibility == "hidden") {
        light.style.top = "50%";
        light.style.left = "50%";
        light.style.visibility = "visible";
      }
      light.style.top = mouseY + "px";
      light.style.left = mouseX + "px";
    } else {
      var centerX = cardRect.left + cardRect.width / 2;
      var centerY = cardRect.top + cardRect.height / 2;

      light.style.top = centerY + "px";
      light.style.left = centerX + "px";
    }

    // 獲取signin-page的位置和尺寸

    var lightRect = light.getBoundingClientRect();
    // 確保光暈不超出signin-page的邊界

    if (lightRect.top < cardRect.top) {
      light.style.top = cardRect.top + lightRect.height / 2 + "px";
    }

    if (lightRect.left < cardRect.left) {
      light.style.left = cardRect.left + lightRect.height / 2 + "px";
    }

    if (lightRect.bottom > cardRect.bottom) {
      light.style.top = cardRect.bottom - lightRect.height / 2 + "px";
    }

    if (lightRect.right > cardRect.right) {
      light.style.left = cardRect.right - lightRect.width / 2 + "px";
    }
  }
  // 監聽滑鼠移動事件，並執行函數
  document.addEventListener("mousemove", updateLightPosition);
});
