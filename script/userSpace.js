document.querySelectorAll(".tabButton").forEach(button => {
    button.addEventListener("click", function () {
        document.querySelectorAll(".tabButton").forEach(btn => btn.classList.remove("active"));
        document.querySelectorAll(".tab-content").forEach(content => content.classList.remove("active"));

        this.classList.add("active");
        document.getElementById(this.dataset.target).classList.add("active");
    });
});
