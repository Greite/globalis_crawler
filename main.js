;(function($) {

    $(document).ready(function () {
        $('#submit_crawl').click(function () {
            let loader = $('#crawl_loader');
            let crawl_url = $('#crawled_site').val();

            loader.show();

            if (crawl_url === '') {
                alert('Veuillez saisir une url');
                loader.hide();
                return;
            }

            $.post('create_crawl.php', { crawled_site: crawl_url})
            .done(function (data) {
                $.post('crawl.php', {id: data.id})
                .done(function () {
                    loader.hide();
                    window.location.href = "./result.php/?id=" + data.id;
                });
            });
        });
    });

})(jQuery);
