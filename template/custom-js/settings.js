$(function() {
    $("input[type='checkbox']").on("change", function(e) {
        let _ = $(this);
        if(_[0].checked === true) {
            _.val('Y')
        } else {
            _.val('N')
        }
        console.log(_[0])
    });
    // Tabs
    $(document).on("click", "#myTab a[href='#global']", function (e) {
        let _ = $(this),
		href = _.attr("href");

        const url = new URL(window.location.href);
		url.searchParams.set("tab", `${href}`);
		history.pushState({}, "", decodeURI(url));
		// NProgress.start();
    })

    $(document).on("click", "#myTab a[href='#periode']", function (e) {
        let _ = $(this),
		href = _.attr("href");

        const url = new URL(window.location.href);
		url.searchParams.set("tab", `${href}`);
		history.pushState({}, "", decodeURI(url));
		// NProgress.start();
    })
})