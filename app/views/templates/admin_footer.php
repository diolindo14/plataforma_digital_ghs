        </main>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTables automatically on any .datatable class
        if($('.datatable').length) {
            $('.datatable').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-PT.json' },
                responsive: true
            });
        }
    });

    // Subir ao Topo Global
    $('<button id="backToTop" class="btn btn-primary" style="position:fixed; bottom:20px; right:20px; border-radius:50%; width:50px; height:50px; display:none; z-index:999;"><ion-icon name="arrow-up-outline"></ion-icon></button>').appendTo('body');
    $(window).scroll(function() {
        if ($(this).scrollTop() > 200) { $('#backToTop').fadeIn(); } else { $('#backToTop').fadeOut(); }
    });
    $('#backToTop').click(function() { $('html, body').animate({scrollTop: 0}, 600); return false; });
</script>
</body>
</html>
