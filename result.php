<?php
include 'start_html.php';

if (!isset($_GET['id'])) {
    header('Location: ' . WEB_HOME);
    exit();
}

$sql = $db->prepare("SELECT * FROM crawls WHERE id=:id");
$sql->bindParam(':id', $_GET['id']);

$sql->execute();
$crawl = $sql->fetch(PDO::FETCH_ASSOC);

$sql = $db->prepare("SELECT status_code, reason, url, found_on, crawled_at FROM crawl_logs WHERE crawl_id=:id");
$sql->bindParam(':id', $_GET['id']);

$sql->execute();
$datas = $sql->fetchAll(PDO::FETCH_ASSOC);

function getStatusColor($code)
{
    if ((int)$code < 300) {
        return 'text-success';
    } elseif ((int)$code < 400) {
        return 'text-warning';
    } else {
        return 'text-danger';
    }
}
?>

<div id="result" class="container">
    <h1>Site crawled : </h1>
    <h2><a href="<?= $crawl['url'] ?>" target="_blank"><?= $crawl['url'] ?></a></h2>
    <h4 id="result_status" data-status="<?= $crawl['status'] ?>">
        Status : <?= $crawl['status'] ?>
        <?php if ($crawl['status'] !== 'completed') : ?>
            <div class="spinner-border text-dark" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        <?php endif; ?>
    </h4>
    <?php if ($crawl['url_count'] !== null) : ?>
        <h4>Page crawled : <?= $crawl['url_count'] ?></h4>
    <?php endif; ?>
    <div class="text-right">
        <a href="<?= WEB_HOME ?>/" class="btn btn-secondary"><i class="fas fa-home"></i></a>
        <a href="<?= WEB_HOME . '/result.php/?id=' . $_GET['id'] ?>" class="btn btn-secondary"><i class="fas fa-sync-alt"></i></a>
        <a href="<?= WEB_HOME ?>/list_crawl.php" class="btn btn-secondary">List crawls</a>
    </div>
    <br>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Status code</th>
                <th scope="col">Reason</th>
                <th scope="col">Url</th>
                <th scope="col">Found on</th>
                <th scope="col">Crawled at</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datas as $data) : ?>
                <tr>
                    <th scope="row" class="<?= getStatusColor($data['status_code']) ?>"><?= $data['status_code'] ?></th>
                    <td><?= $data['reason'] ?></td>
                    <td><a href="<?= $data['url'] ?>" target="_blank"><?= $data['url'] ?></a></td>
                    <td><a href="<?= $data['found_on'] ?>" target="_blank"><?= $data['found_on'] ?></a></td>
                    <td><?= $data['crawled_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'end_html.php' ?>
