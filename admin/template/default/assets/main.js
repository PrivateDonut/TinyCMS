document.addEventListener('DOMContentLoaded', function() {
    var sidebar = document.getElementById('sidebar');
    var sidebarCollapse = document.getElementById('sidebarCollapse');
    var sidebarToggle = document.getElementById('sidebarToggle');
    var content = document.getElementById('content');

    sidebarToggle.addEventListener('click', function () {
        sidebar.classList.toggle('active');
        if (window.innerWidth <= 575.98) {
            content.classList.toggle('col-12');
            content.classList.toggle('col-sm-8');
        }
    });
    
    sidebarCollapse.addEventListener('click', function () {
        sidebar.classList.toggle('active');
        if (window.innerWidth <= 575.98) {
            content.classList.toggle('col-12');
            content.classList.toggle('col-sm-8');
        }
    });
});