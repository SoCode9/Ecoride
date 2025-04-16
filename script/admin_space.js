document.querySelectorAll('.tab-btn').forEach(button => {
    button.addEventListener('click', () => {
        const target = button.dataset.target;

        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));

        document.getElementById(target).classList.add('active');

         // Update URL without refresh
         const url = new URL(window.location);
         url.searchParams.set('tab', target);
         window.history.pushState({}, '', url);
    });
});