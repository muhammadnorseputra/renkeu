$(function() {
    function loadContent(url, title=null, target='renderContent') {
        $.ajax({
            method: 'POST',
            url: url,
            dataType: 'html',
            async: 'async',
            cache: false,
            error: function(XMLHttpRequest) {
                alert(XMLHttpRequest.status + ' - ' + XMLHttpRequest.statusText);
            },
            success: function(data) {
                $('#' + target).html(data);
                document.title = " " + (title ?? '-');
            }
        })
    }

    $('.loadContent').click(function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var title = $(this).attr('title');
        loadContent(href, title);
    });

    //DEFAULT CONTENT
    // loadContent(`${_uri}/app/dashboard`, 'Dashboard');
})