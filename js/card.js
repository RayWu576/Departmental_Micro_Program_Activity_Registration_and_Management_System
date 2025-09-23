document.addEventListener("DOMContentLoaded", function () {
  var activity = document.getElementById("checkbox-activity");
  var course = document.getElementById("checkbox-course");
  var manager = document.getElementById("checkbox-manager");

  var checkboxAvailable = document.getElementById("checkbox-available");
  var checkboxDeadline = document.getElementById("checkbox-deadline");
  var checkboxEnd = document.getElementById("checkbox-ended");

  // checkboxAvailable.checked = JSON.parse(localStorage.getItem('checkboxAvailable')) || false;
  // checkboxDeadline.checked = JSON.parse(localStorage.getItem('checkboxDeadline')) || false;
  // checkboxEnd.checked = JSON.parse(localStorage.getItem('checkboxEnd')) || false;

  function handleCheckboxChange() {
    // localStorage.setItem('checkboxAvailable', checkboxAvailable.checked);
    // localStorage.setItem('checkboxDeadline', checkboxDeadline.checked);
    // localStorage.setItem('checkboxEnd', checkboxEnd.checked);
    if (activity) {
      window.location.href =
        "activity.php?available=" +
        checkboxAvailable.checked +
        "&deadline=" +
        checkboxDeadline.checked +
        "&ended=" +
        checkboxEnd.checked;
    } else if (course) {
      window.location.href =
        "microcredential.php?available=" +
        checkboxAvailable.checked +
        "&deadline=" +
        checkboxDeadline.checked +
        "&ended=" +
        checkboxEnd.checked;
    } else if (manager) {
      window.location.href =
        "my_manager.php?available=" +
        checkboxAvailable.checked +
        "&deadline=" +
        checkboxDeadline.checked +
        "&ended=" +
        checkboxEnd.checked;
    }
  }

  checkboxAvailable.addEventListener("change", handleCheckboxChange);
  checkboxDeadline.addEventListener("change", handleCheckboxChange);
  checkboxEnd.addEventListener("change", handleCheckboxChange);
});
