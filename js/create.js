function clearFormFields() {
  document.getElementById("input-activity-name").value = "";
  document.getElementById("start-date").value = "";
  document.getElementById("end-date").value = "";
  document.getElementById("location").value = "";
  document.getElementById("organizer").value = "";
  document.getElementById("capacity").value = "";
  document.getElementById("cost").value = "";
  document.getElementById("register-deadline").value = "";
  document.getElementById("description").value = "";
  document.getElementById("year").value = "";
  document.getElementById("semester").value = "";
  document.getElementById("additional-info").value = "";
  document.getElementById("hours").value = "";
}

var activity = document.getElementById("create-activity");
var course = document.getElementById("create-course");

function show_activity() {
  if (activity.style.display === "none") {
    activity.style.display = "block"; //activity出現
    course.style.display = "none";

    clearFormFields();
  }
}

function show_course() {
  if (course.style.display === "none") {
    course.style.display = "block"; //course出現
    activity.style.display = "none";

    clearFormFields();
  }
}
