document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".tab-btn").forEach(button => {
        button.addEventListener("click", function () {
            const targetId = this.dataset.target;

            // Update URL without refresh
            const url = new URL(window.location);
            url.searchParams.set('tab', targetId);
            url.searchParams.set('page', '1'); // reset pagination on tab change
            window.location.href = url; // force reload with correct tab and page
        });
    });
});
