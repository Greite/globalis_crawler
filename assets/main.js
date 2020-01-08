;(function($) {

    $(document).ready(function () {
        if ($('#result').length !== 0) {
            if ($('#result_status').data('status') !== 'completed') {
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        }

        $('#submit_crawl').click(function () {
            let crawl_url = $('#crawled_site').val();

            if (crawl_url === '') {
                alert('Veuillez saisir une url');
                return;
            }

            $.post('create_crawl.php', { crawled_site: crawl_url})
            .done(function (data) {
                console.log(data.id);
                $.post('crawl.php', {id: data.id}).done(function(){console.log('Run !')})
                setTimeout(function () {
                    window.location.href = "./result.php/?id=" + data.id;
                }, 100)
            });
        });
    });

})(jQuery);
