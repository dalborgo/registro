/*
 * Editor client script for DB table registro
 * Created by http://editor.datatables.net/generator
 */

(function ($) {

    $(document).ready(function () {
        var editor = new $.fn.dataTable.Editor({
            ajax: 'php/table.registro.php',
            table: '#registro',
            i18n: {
                "create": {
                    "button": "Nuovo",
                    "title":  "Crea nuova riga",
                    "submit": "Crea"
                },
                "edit": {
                    "button": "Modifica",
                    "title":  "Modifica riga",
                    "submit": "Aggiorna"
                },
                "remove": {
                    "button": "Elimina",
                    "title":  "Elimina riga",
                    "submit": "Elimina",
                    "confirm": {
                        "_": "Sei sicura di voler eliminare %d righe?",
                        "1": "Sei sicura di voler eliminare la riga?"
                    }
                },
                "error": {
                    "system": "Si è verificato un errore (Maggiori informazioni)"
                },
                "multi": {
                    "title": "Multiple values",
                    "info": "The selected items contain different values for this input. To edit and set all items for this input to the same value, click or tap here, otherwise they will retain their individual values.",
                    "restore": "Undo changes",
                    "noMulti": "This input can be edited individually, but not part of a group."
                },
                "datetime": {
                    "previous": 'Precedente',
                    "next":     'Successivo',
                    "months":   [ 'Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre' ],
                    "weekdays": [ 'Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab' ],
                    "amPm":     [ 'am', 'pm' ],
                    "unknown":  '-'
                }
            },
            fields: [
                {
                    "label": "Data:",
                    "name": "data",
                    "type": "datetime",
                    "format": "ddd, DD MMM YYYY HH:mm:ss"
                },
                {
                    "label": "Descrizione:",
                    "name": "descrizione"
                },
                {
                    "label": "Entrata:",
                    "name": "entrata"
                },
                {
                    "label": "Uscita:",
                    "name": "uscita"
                }
            ]
        });
        $('#registro').on( 'click', 'tbody td', function (e) {
            editor.inline( this );
        } );

        var table = $('#registro').DataTable({
            dom: 'Bfrtip',
            ajax: {
                url: "php/table.registro.php",
                type: "POST"
            },
            columns: [
                {
                    "data": "data",
                    "render": function(data, type, full) {
                        return (data) ? moment(data).format('ddd, DD MMM YYYY  HH:mm:ss') : '';
                    }
                },
                {
                    "data": "descrizione"
                },
                {
                    "data": "entrata", render: $.fn.dataTable.render.number('.', ',', 2, '€ ')
                },
                {
                    "data": "uscita", render: $.fn.dataTable.render.number('.', ',', 2, '€ ')
                }
            ],
            "columnDefs": [
                {className: "aright", "targets": [2, 3]},
                {className: "acenter", "targets": [0]},
                {className: "aleft", "targets": [1]}
                // {"orderable": false, "targets": [1, 2, 3, 4]}
            ],
            serverSide: true,
            lengthChange: false,
            language: {
                "decimal": ",",
                "thousands": ".",
                "url": "dataTables.italian.lang",
                select: {
                    rows: {
                        _: "%d righe selezionate",
                        0:'',
                        1: "<i>1 riga selezionata</i>"
                    }
                }
            },
            select: 'single',
            order: [[0, "desc"]],
            pageLength: 15,
            sortable: false,
            buttons: [
                {extend: 'create', editor: editor},
                {extend: 'edit', editor: editor},
                {extend: 'remove', editor: editor},
                {
                    extend: "create",
                    text: "Apri Totali",
                    action: function (e, dt, node, config) {
                        window.open("report2.php", "_blank")
                    }
                }
            ],
            "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;
            $.ajax({
                url: "totale.php",
                success: function (data, stato) {
                    $(api.column(3).footer()).html(data);
                },
                error: function (richiesta, stato, errori) {}
            })
        }
        });
    });

}(jQuery));

