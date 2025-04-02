document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".tab-btn").forEach(button => {
        button.addEventListener("click", function () {
            const targetId = this.dataset.target;
            const target = document.getElementById(targetId);
            console.log("Target:", target);


            document.querySelectorAll(".tab-btn").forEach(btn => btn.classList.remove("active"));
            document.querySelectorAll(".tab-content").forEach(content => content.classList.remove("active"));

            this.classList.add("active");
            document.getElementById(this.dataset.target).classList.add("active");
        });
    });
});
