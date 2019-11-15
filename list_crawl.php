<?php
include 'start_html.php';

$sql = $db->prepare("SELECT * FROM crawls ORDER BY created_at DESC");

$sql->execute();
$datas = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container">
    <h1>Sites crawled : </h1>
    <div class="text-right">
        <a href="<?= WEB_HOME ?>" class="btn btn-secondary"><i class="fas fa-home"></i></a>
        <a href="<?= WEB_HOME . '/list_crawl.php' ?>" class="btn btn-secondary"><i class="fas fa-sync-alt"></i></a>
    </div>
    <br>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Url</th>
                <th scope="col">Status</th>
                <th scope="col">Started at</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datas as $data) : ?>
                <tr>
                    <th scope="row"><a href="<?= WEB_HOME . '/result.php/?id=' . $data['id'] ?>"><?= $data['url'] ?></a></th>
                    <td><?= $data['status'] ?></td>
                    <td><?= $data['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'end_html.php' ?>
