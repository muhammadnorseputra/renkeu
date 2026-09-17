$(function() {
    // Splash screen: tampilkan saat navigasi dimulai, sembunyikan saat konten selesai dimuat
    function showSplash() {
        $('#splash-screen').removeClass('hidden');
    }
    function hideSplash() {
        $('#splash-screen').addClass('hidden');
    }

    function loadContent(url, title=null, target='renderContent') {
        showSplash();
        $.ajax({
            method: 'POST',
            url: url,
            dataType: 'html',
            async: 'async',
            cache: false,
            error: function(XMLHttpRequest) {
                hideSplash();
                alert(XMLHttpRequest.status + ' - ' + XMLHttpRequest.statusText);
            },
            success: function(data) {
                $('#' + target).html(data);
                document.title = " " + (title ?? '-');
                hideSplash();
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
    loadContent(`${_uri}/app/dashboard/view`, 'Dashboard');
})