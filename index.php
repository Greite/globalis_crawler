<?php
require 'config/env.php';
?>

<?php include 'start_html.php' ?>

<div class="container h-100">
    <div class="row align-items-center h-100">
        <div class="col-sm-4 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">POC Crawler</h5>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="crawled_site" name="crawled_site" placeholder="https://example.com/" aria-label="https://example.com/" aria-describedby="submit_crawl">
                        <div class="input-group-append">
                            <input type="button" class="btn btn-outline-secondary" id="submit_crawl" value="Crawl">
                        </div>
                    </div>
                    <div>
                        <a href="<?= WEB_HOME ?>/list_crawl.php" class="btn btn-secondary">List crawls</a>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status" id="crawl_loader" style="display:none;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'end_html.php' ?>
