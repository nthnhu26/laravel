function initDataTable(tableId) {
    $(document).ready(function() {
        $('#' + tableId).DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json"
            },
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "columnDefs": [{
                "orderable": false,
                "targets": [0, -1],
                "className": "no-sort"
            }],
            "drawCallback": function(settings) {
                $('#' + tableId + ' thead th:nth-child(1), #' + tableId + ' thead th:last-child')
                    .removeClass('sorting sorting_asc sorting_desc')
                    .addClass('sorting_disabled');
            }
        });
    });
}