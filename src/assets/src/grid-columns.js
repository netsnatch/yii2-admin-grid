(function ($) {
    $.fn.yiiGridColumns = function (options) {

        var settings = $.extend({
            'id': '',
            'name': '',
            'targetWidgetId': '',
            'columns': {},
            'placeSelector': {}
        }, options);

        var widget = $('#' + settings.id);

        settings = $.extend(settings, {
            storeKey: 'yiiGridColumns-' + settings.name,
            menu: $('.grid-columns__button', widget),
            reset: $('.grid-columns__reset', widget),
            modal: $('.grid-columns__modal', widget),
            targetWidget: $('#' + settings.targetWidgetId)
        });

        var GridColumns = {
            initialized: false,

            init: function () {
                var self = this;

                self.rebuildTable();

                widget.appendTo(settings.placeSelector);

                settings.targetWidget.removeClass('grid-columns__hidden');

                settings.menu.on('click', function () {
                    settings.modal.modal('show');
                    if (!self.initialized) {
                        self.initColumnsList();
                        self.initialized = true;
                    }
                });

                settings.reset.on('click', self.reset.bind(self));
            },

            initColumnsList: function () {
                var self = this;

                var connectClass = 'connected-sortable';
                var $listShow = $('<div/>', {
                    'class': connectClass + ' list-group grid-columns__list',
                    'data-show': true
                });
                var $listHide = $('<div/>', {
                    'class': connectClass + ' list-group grid-columns__list',
                    'data-show': false
                });

                self.walkColumnsSettings(function (column, key) {
                    var $li = $('<div/>', {'class': 'list-group-item grid-columns__list__item'})
                        .data('position', column.position)
                        .data('column-data-key', key)
                        .html(column.label)
                        .append($('<span/>', {
                            'class': 'glyphicon glyphicon-option-vertical pull-right text-muted'
                        }));

                    if (column.show) {
                        $listShow.append($li);
                    } else {
                        $listHide.append($li);
                    }
                });

                settings.modal.find('.grid-columns__left-col').html($listShow);
                settings.modal.find('.grid-columns__right-col').html($listHide);

                self.sortChildren($listShow);
                self.sortChildren($listHide);

                var fixHeight = function () {
                    var $cols = $([$listShow, $listHide]);

                    $cols.each(function () {
                        $(this).height('auto');
                    });

                    var height = Math.max(
                        $listShow.height(),
                        $listHide.height()
                    );
                    $cols.each(function () {
                        $(this).height(height);
                    });
                };

                $([$listShow, $listHide]).sortable({
                    connectWith: $('.' + connectClass, widget),
                    update: function () {
                        var columnsSettings = {};

                        $([$listShow, $listHide]).each(function () {
                            var $list = $(this);

                            $list.find('.grid-columns__list__item').each(function (index) {
                                var dataKey = $(this).data('column-data-key');

                                columnsSettings[dataKey] = columnsSettings[dataKey] || {};
                                columnsSettings[dataKey]['show'] = $list.data('show');
                                columnsSettings[dataKey]['position'] = index;
                            });
                        });

                        self.saveColumnsSettings(columnsSettings);
                        self.rebuildTable();
                        fixHeight()
                    }
                })
                .disableSelection();

                fixHeight();
            },

            sortChildren: function($list) {
                $list.children().sort(function (a, b) {
                    var an = +$(b).data('position'),
                        bn = +$(a).data('position');

                    if (an > bn) return -1;
                    if (an < bn) return 1;

                    return $(a).index() - $(b).index();
                }).detach().appendTo($list)
            },

            rebuildTable: function (forceRebuild) {
                var table = $('.grid-columns__table', settings.targetWidget);
                var self = this;

                if (!self.restoreColumnsSettings() && !forceRebuild) {
                    return;
                }

                table.trigger('beforeBuild.gridColumns');

                self.walkColumnsSettings(function (column) {
                    var index = $('.' + column.class).index();

                    table.find('tr').each(function () {
                        var show = !!column.show;

                        var moveItem = $(this).children().eq(index).toggleClass('hidden', !show);

                        if (show && typeof column.position !== "undefined") {
                            $(this).children().eq(column.position).before(moveItem);
                        }
                    });
                }, true);

                table.trigger('afterBuild.gridColumns');
            },

            reset: function () {
                this.saveColumnsSettings({});
                this.rebuildTable(true);
                this.initColumnsList();
            },

            saveColumnsSettings: function (columnsSettings) {
                xStore(settings.storeKey, columnsSettings);
            },

            restoreColumnsSettings: function () {
                var savedColumns = xStore(settings.storeKey) || {};

                this.walkColumnsSettings(function (column, key) {
                    column = $.extend(column, {show: true, position: 0});

                    if (savedColumns.hasOwnProperty(column.key)) {
                        column = $.extend(column, savedColumns[column.key]);
                    }
                });

                return !!Object.keys(savedColumns).length;
            },

            walkColumnsSettings: function (callback, reverse) {
                if (reverse) {
                    for (var i = settings.columns.length - 1; i >= 0; i--) {
                        callback(settings.columns[i], settings.columns[i].key, settings.columns);
                    }
                } else {
                    for (var i = 0; i < settings.columns.length; i++) {
                        callback(settings.columns[i], settings.columns[i].key, settings.columns);
                    }
                }
            }
        };

        GridColumns.init();
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
