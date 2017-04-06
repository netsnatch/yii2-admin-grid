(function ( $ ) {
    $.fn.adminGridView = function( options ) {

        var settings = $.extend({
            id: '',
            tableName: '',
            modalElId: '',
            menuElId: '',
            columns: []
        }, options );

        var saved = xStore('admin-grid-' + settings.tableName);
        if (saved) {
            settings = $.extend(settings, saved);
        }

        rebuildTable(settings);

        var $menu = $('#' + settings.menuElId);
        var $modal = $('#' + settings.modalElId);
        var initialized = false;
        var fixColsHeight;

        $menu.on('click', function() {
            if (!initialized) {
                initColumns(settings.columns);
                initialized = true;
            }

            $modal.modal('show');

            fixColsHeight();
        });

        function initColumns(columns) {
            var connectedListClass = settings.id + '-connected-sortable';
            var $listShow = $('<div/>', {class: connectedListClass + ' list-group admin-grid__list', 'data-show': true});
            var $listHide = $('<div/>', {class: connectedListClass + ' list-group admin-grid__list', 'data-show': false});

            for (var i = 0; i < columns.length; i++) {
                var $li = $('<div/>', {class: 'list-group-item admin-grid__list__item', style: 'cursor: move;'})
                    .data('column-data', columns[i])
                    .html(columns[i].label)
                    .append($('<span/>', {
                        class: 'glyphicon glyphicon-option-vertical pull-right text-muted',
                    }));

                if (columns[i].show) {
                    $listShow.append($li);
                } else {
                    $listHide.append($li);
                }
            }
            $modal.find('.admin-grid__left-col').html($listShow);
            $modal.find('.admin-grid__right-col').html($listHide);

            $([$listShow, $listHide]).sortable({
                connectWith: '.' + connectedListClass,
                update: function( event, ui ) {
                    var columns = [];
                    $([$listShow, $listHide]).each(function() {
                        var $list = $(this);
                        $list.find('.admin-grid__list__item').each(function(index) {
                            var data = $(this).data('column-data');
                            data['show'] = $list.data('show');
                            data['position'] = index;
                            columns.push(data);
                        });
                    });
                    settings.columns = columns;
                    rebuildTable(settings);
                    updSettings(settings);
                }
            }).disableSelection();

            fixColsHeight = function() {
                $([$listShow, $listHide]).each(function() {
                    $(this).height(Math.max($listShow.outerHeight(), $listHide.outerHeight()))
                });
            }
        }

        function updSettings(settings) {
            xStore('admin-grid-' + settings.tableName, settings);
        }

        function rebuildTable(settings) {
            var table;

            settings.columns.forEach(function(column) {
                var th = $('#' + column.id);
                var index = th.index();
                table = table || th.closest('table');

                table.find('tr').each(function() {
                    $(this).children().eq(index).toggle(!!column.show);
                    if (typeof column.position !== "undefined") {
                        $(this).children(":eq(" + column.position + ")").before($(this).children(":eq(" + index +")"));
                    }
                });
                table.removeClass('hidden');
            });
        }
    };

    /**
     * Simple localStorage with Cookie Fallback
     * v.1.0.0
     *
     * USAGE:
     * ----------------------------------------
     * Set New / Modify:
     *   xStore('my_key', 'some_value');
     *
     * Retrieve:
     *   xStore('my_key');
     *
     * Delete / Remove:
     *   xStore('my_key', null);
     */
    var xStore = function(t,e){function a(t,e,a){var n=new Date;n.setTime(n.getTime()+24*a*60*60*1e3);var r="; expires="+n.toGMTString();document.cookie=t+"="+e+r+"; path=/"}function n(t){for(var e=t+"=",a=document.cookie.split(";"),n=0,r=a.length;r>n;n++){for(var o=a[n];" "===o.charAt(0);)o=o.substring(1,o.length);if(0===o.indexOf(e))return o.substring(e.length,o.length)}return null}var r=!1;if(localStorage&&(r=!0),"undefined"!=typeof e&&null!==e&&("object"==typeof e&&(e=JSON.stringify(e)),r?localStorage.setItem(t,e):a(t,e,30)),"undefined"==typeof e){r?data=localStorage.getItem(t):data=n(t);try{data=JSON.parse(data)}catch(o){data=data}return data}null===e&&(r?localStorage.removeItem(t):a(t,"",-1))};
}( jQuery ));
