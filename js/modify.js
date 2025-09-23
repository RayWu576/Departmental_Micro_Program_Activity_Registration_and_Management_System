function validateForm_modify() {
  const ncyuEmailPattern = /@g\.ncyu\.edu\.tw$/;

  var passwrod_modify = document.forms["modify"]["input-password"].value;
  var email_modify = document.forms["modify"]["input-userEmail"].value;

  console.log(passwrod_modify);
  console.log(email_modify);

  if (passwrod_modify.length < 6) {
    alert("密碼長度不足");
    return false;
  }

  if (!ncyuEmailPattern.test(email_modify)) {
    alert("請使用 g.ncyu.edu.tw 的email");
    return false;
  }
  return true;
}
