function overviewChange() {
    var subject = document.getElementById("overview").value;
    location.href = "index.php?subject=" + subject;
}