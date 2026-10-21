/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
    $BODY = $('body'),
    $MENU_TOGGLE = $('#menu_toggle'),
    $SIDEBAR_MENU = $('#sidebar-menu'),
    $SIDEBAR_FOOTER = $('.sidebar-footer'),
    $LEFT_COL = $('.left_col'),
    $RIGHT_COL = $('.right_col'),
    $NAV_MENU = $('.nav_menu'),
    $FOOTER = $('footer');

// Sidebar
function init_sidebar() {
    // TODO: This is some kind of easy fix, maybe we can improve this
    var setContentHeight = function () {
        // reset height
        $RIGHT_COL.css('min-height', $(window).height());

        var bodyHeight = $BODY.outerHeight(),
            footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
            leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
            contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

        // normalize content
        contentHeight -= $NAV_MENU.height() + footerHeight;

        $RIGHT_COL.css('min-height', contentHeight);
    };

    var openUpMenu = function () {
        $SIDEBAR_MENU.find('li').removeClass('active active-sm');
        $SIDEBAR_MENU.find('li ul').slideUp();
    }

    var $menuSearch = $('#menu-quick-search-input');
    var $menuSearchResults = $('#menu-quick-search-results');
    var $menuSearchContainer = $menuSearch.closest('.menu-quick-search');
    var menuSearchTimer;

    var openMenuSearch = function () {
        if (!$('#menu-quick-search-backdrop').length) {
            $('<div>', {
                id: 'menu-quick-search-backdrop',
                'aria-hidden': 'true'
            }).appendTo('body');
        }

        $menuSearchContainer.addClass('is-active').closest('.top_nav').addClass('menu-quick-search-open');
    };

    var closeMenuSearch = function () {
        clearTimeout(menuSearchTimer);
        $('#menu-quick-search-backdrop').remove();
        $menuSearchContainer.removeClass('is-active');
        $menuSearchContainer.closest('.top_nav').removeClass('menu-quick-search-open');
        $menuSearch.removeClass('is-focused');
        $menuSearchResults.prop('hidden', true).removeClass('is-loading').removeAttr('aria-busy');
        $menuSearch.attr('aria-expanded', 'false');
    };

    var getMenuSearchEntries = function () {
        return Array.isArray(window.menuSearchCatalog) ? window.menuSearchCatalog : [];
    };

    var showMenuSearchHint = function () {
        $menuSearchResults
            .empty()
            .append(
                '<div class="menu-quick-search__hint" role="note">' +
                    '<span class="menu-quick-search__hint-icon"><i class="fa fa-compass" aria-hidden="true"></i></span>' +
                    '<span>Silakan ketik nama menu atau submenu</span>' +
                '</div>'
            )
            .removeClass('is-loading')
            .removeAttr('aria-busy')
            .prop('hidden', false);
        $menuSearch.attr('aria-expanded', 'true');
    };

    var renderMenuSearchResults = function (query) {
        query = (query || '').toLowerCase().trim();
        $menuSearchResults.empty();

        if (!query) {
            showMenuSearchHint();
            return;
        }

        var matches = getMenuSearchEntries().filter(function (entry) {
            return (entry.label + ' ' + entry.parentLabel).toLowerCase().indexOf(query) !== -1;
        }).slice(0, 8);

        if (!matches.length) {
            $('<div class="menu-quick-search__empty">Menu tidak ditemukan</div>').appendTo($menuSearchResults);
        } else {
            matches.forEach(function (entry) {
                var $icon = $('<i aria-hidden="true"></i>').addClass(entry.iconClass);
                var $label = $('<span></span>').text(entry.parentLabel ? entry.parentLabel + ' — ' + entry.label : entry.label);
                var $result = $('<a class="menu-quick-search__result" role="option"></a>')
                    .attr('href', entry.url)
                    .append($icon, $label);
                $menuSearchResults.append($result);
            });
        }

        $menuSearchResults.removeClass('is-loading').removeAttr('aria-busy');
        $menuSearch.attr('aria-expanded', 'true');
    };

    var showMenuSearchLoading = function () {
        var query = $menuSearch.val().toLowerCase().trim();
        clearTimeout(menuSearchTimer);

        if (!query) {
            showMenuSearchHint();
            return;
        }

        $menuSearchResults
            .empty()
            .append('<div class="menu-quick-search__skeleton"></div><div class="menu-quick-search__skeleton"></div><div class="menu-quick-search__skeleton"></div>')
            .addClass('is-loading')
            .attr('aria-busy', 'true')
            .prop('hidden', false);
        $menuSearch.attr('aria-expanded', 'true');

        menuSearchTimer = setTimeout(function () {
            renderMenuSearchResults(query);
        }, 140);
    };

    $menuSearch.on('focus', function () {
        openMenuSearch();
        showMenuSearchLoading();
    });
    $menuSearch.on('click', openMenuSearch);
    $menuSearch.on('input', function () {
        openMenuSearch();
        $(this).addClass('is-focused');
        showMenuSearchLoading();
    });
    $menuSearchContainer.on('focusout', function () {
        var searchContainer = this;
        setTimeout(function () {
            if (!searchContainer.contains(document.activeElement)) {
                closeMenuSearch();
            }
        }, 0);
    });
    $menuSearch.on('blur', function () {
        $(this).removeClass('is-focused');
    });
    $menuSearch.on('keydown', function (ev) {
        if (ev.key === 'Escape') {
            $(this).val('');
            closeMenuSearch();
        } else if (ev.key === 'Enter') {
            var $firstResult = $menuSearchResults.find('a').first();
            if ($firstResult.length) {
                window.location.href = $firstResult.attr('href');
            }
        }
    });

    $(document).on('keydown', function (ev) {
        if (!(ev.ctrlKey || ev.metaKey) || ev.altKey || ev.key.toLowerCase() !== 'k') {
            return;
        }

        var target = ev.target;
        var isEditing = target.isContentEditable || /^(INPUT|TEXTAREA|SELECT)$/.test(target.tagName);
        if (isEditing && target !== $menuSearch[0]) {
            return;
        }

        ev.preventDefault();
        $menuSearch.trigger('focus').trigger('select');
    });

    $(document).on('click', function (ev) {
        if (!$(ev.target).closest('.menu-quick-search').length) {
            closeMenuSearch();
        }
    });

    $SIDEBAR_MENU.find('a').on('click', function (ev) {
        var $li = $(this).parent();

        if ($li.is('.active')) {
            $li.removeClass('active active-sm');
            $('ul:first', $li).slideUp(function () {
                setContentHeight();
            });
        } else {
            // prevent closing menu if we are on child menu
            if (!$li.parent().is('.child_menu')) {
                openUpMenu();
            } else {
                if ($BODY.is('nav-sm')) {
                    if (!$li.parent().is('child_menu')) {
                        openUpMenu();
                    }
                }
            }

            $li.addClass('active');

            $('ul:first', $li).slideDown(function () {
                setContentHeight();
            });
        }
    });

    // toggle small or large menu
    $MENU_TOGGLE.on('click', function () {
        
        if ($BODY.hasClass('nav-md')) {
            $SIDEBAR_MENU.find('li.active ul').hide();
            $SIDEBAR_MENU.find('li.active').addClass('active-sm').removeClass('active');
        } else {
            localStorage.setItem("nav", "nav-md");
            $SIDEBAR_MENU.find('li.active-sm ul').show();
            $SIDEBAR_MENU.find('li.active-sm').addClass('active').removeClass('active-sm');
        }

        $BODY.toggleClass('nav-md nav-sm');

        setContentHeight();

        $('.dataTable').each(function () { $(this).dataTable().fnDraw(); });
    });

    // check active menu
    $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').parent('li').addClass('current-page');

    $SIDEBAR_MENU.find('a').filter(function () {
        return this.href == CURRENT_URL;
    }).parent('li').addClass('current-page').parents('ul').slideDown(function () {
        setContentHeight();
    }).parent().addClass('active');

    // recompute content when resizing
    $(window).smartresize(function () {
        setContentHeight();
    });

    setContentHeight();

    // fixed sidebar
    if ($.fn.mCustomScrollbar) {
        $('.menu_fixed').mCustomScrollbar({
            autoHideScrollbar: true,
            theme: 'minimal',
            mouseWheel: { preventDefault: true }
        });
    }
}
// /Sidebar

// Panel toolbox
$(document).ready(function () {
    var PANEL_STATE_KEY = 'x_panel_states';
    var panelStates = JSON.parse(localStorage.getItem(PANEL_STATE_KEY) || '{}');
    var pageKey = window.location.pathname;
    var $panels = $('.x_panel');

    // Key stabil per halaman: id panel bila ada, selain itu index panel.
    function panelKey($panel) {
        var id = $panel.attr('id');
        return pageKey + '|' + (id || ('idx' + $panels.index($panel)));
    }

    // Terapkan state tersimpan saat load
    $panels.each(function () {
        var $panel = $(this);
        if (panelStates[panelKey($panel)] === 'collapsed') {
            $panel.find('.x_content').hide();
            $panel.find('.collapse-link i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        }
    });

    $('.collapse-link').on('click', function () {
        var $BOX_PANEL = $(this).closest('.x_panel'),
            $ICON = $(this).find('i'),
            $BOX_CONTENT = $BOX_PANEL.find('.x_content');

        // Simpan state SESUDAH toggle: sekarang visible = akan jadi collapsed.
        var willCollapse = !$BOX_CONTENT.is(':hidden');
        panelStates[panelKey($BOX_PANEL)] = willCollapse ? 'collapsed' : 'expanded';
        localStorage.setItem(PANEL_STATE_KEY, JSON.stringify(panelStates));

        // fix for some div with hardcoded fix class
        if ($BOX_PANEL.attr('style')) {
            $BOX_CONTENT.slideToggle(200, function () {
                $BOX_PANEL.removeAttr('style');
                refreshCharts($BOX_PANEL);
            });
        } else {
            $BOX_CONTENT.slideToggle(200, function () {
                refreshCharts($BOX_PANEL);
            });
            $BOX_PANEL.css('height', 'auto');
        }

        $ICON.toggleClass('fa-chevron-up fa-chevron-down');
    });

    // Chart.js 2.x & Flot butuh di-resize setelah panel dibuka (canvas 0px saat hidden)
    function refreshCharts($panel) {
        $panel.find('canvas').each(function () {
            if (this.chart) {
                try { this.chart.resize(); } catch (e) { /* noop */ }
            }
        });
        $(window).trigger('resize');
    }

    $('.close-link').click(function () {
        var $BOX_PANEL = $(this).closest('.x_panel');

        $BOX_PANEL.remove();
    });
});
// /Panel toolbox

// Tooltip
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });
});
// /Tooltip

// Progressbar
$(document).ready(function () {
    if ($(".progress .progress-bar")[0]) {
        $('.progress .progress-bar').progressbar();
    }
});
// /Progressbar

// Switchery
$(document).ready(function () {
    if ($(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#26B99A'
            });
        });
    }
});
// /Switchery

// iCheck
$(document).ready(function () {
    if ($("input.flat")[0]) {
        $(document).ready(function () {
            $('input.flat').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    }
});
// /iCheck

// Table
$('table input').on('ifChecked', function () {
    checkState = '';
    $(this).parent().parent().parent().addClass('selected');
    countChecked();
});
$('table input').on('ifUnchecked', function () {
    checkState = '';
    $(this).parent().parent().parent().removeClass('selected');
    countChecked();
});

var checkState = '';

$('.bulk_action input').on('ifChecked', function () {
    checkState = '';
    $(this).parent().parent().parent().addClass('selected');
    countChecked();
});
$('.bulk_action input').on('ifUnchecked', function () {
    checkState = '';
    $(this).parent().parent().parent().removeClass('selected');
    countChecked();
});
$('.bulk_action input#check-all').on('ifChecked', function () {
    checkState = 'all';
    countChecked();
});
$('.bulk_action input#check-all').on('ifUnchecked', function () {
    checkState = 'none';
    countChecked();
});

function countChecked() {
    if (checkState === 'all') {
        $(".bulk_action input[name='table_records']").iCheck('check');
    }
    if (checkState === 'none') {
        $(".bulk_action input[name='table_records']").iCheck('uncheck');
    }

    var checkCount = $(".bulk_action input[name='table_records']:checked").length;

    if (checkCount) {
        $('.column-title').hide();
        $('.bulk-actions').show();
        $('.action-cnt').html(checkCount + ' Records Selected');
    } else {
        $('.column-title').show();
        $('.bulk-actions').hide();
    }
}

// Accordion
$(document).ready(function () {
    $(".expand").on("click", function () {
        $(this).next().slideToggle(200);
        $expand = $(this).find(">:first-child");

        if ($expand.text() == "+") {
            $expand.text("-");
        } else {
            $expand.text("+");
        }
    });
});

// NProgress
if (typeof NProgress != 'undefined') {
    $(document).ready(function () {
        NProgress.start();
    });

    $(window).on('load', function () {
        NProgress.done();
    });
}
