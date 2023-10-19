<div class="row">
    <div class="col-md-12">
    <div class="x_panel">
        <div class="x_title">
        <h2>Inboxs User</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
        </div>
        <div class="x_content">
        <div class="row">
            <div class="col-sm-3 mail_list_column"></div>
            <!-- /MAIL LIST -->

            <!-- CONTENT MAIL -->
            <div class="col-sm-9 mail_view"></div>
            <!-- /CONTENT MAIL -->
        </div>
        </div>
    </div>
    </div>
</div>

<script>
    var $MailList = $(".mail_list_column"),
    $MailDetail = $(".mail_view");

    let id = urlParams.get('mail');

    async function getListMail() {
        const req = await fetch(`${_uri}/app/inbox/list`);
        const res = await req.json();
        return res;
    }

    async function getMailById(id) {
        const req = await fetch(`${_uri}/app/inbox/mailById/${id}`);
        const res = await req.json();
        return res;
    }

    getListMail().then((e) => $MailList.html(e.html));
    getMailById(id).then((res) => $MailDetail.html(res));

    function MailDetail(id) {
        NProgress.start();
        const url = new URL(window.location.href);
        url.searchParams.set('mail', id);
        history.pushState({}, "", url);
        getMailById(id).then((res) => { $MailDetail.html(res); NProgress.done() });
    }

</script>