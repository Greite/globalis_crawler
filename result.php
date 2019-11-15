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

$filters = '';

foreach ($_GET as $key => $value) {
    if (strpos($key, 'status_') === false) {
        continue;
    }

    if ($filters === '') {
        $filters .= ' AND (status_code = ' . $value;
    } else {
        $filters .= ' OR status_code = ' . $value;
    }
}
if ($filters !== '') {
    $filters .= ')';
}

$orderby = '';

if (isset($_GET['orderby']) && isset($_GET['order'])) {
    $orderby = ' ORDER BY ' . $_GET['orderby'] . ' ' . $_GET['order'];
}

$sql = $db->prepare("SELECT status_code, reason, url, found_on, crawled_at FROM crawl_logs WHERE crawl_id=:id" . $filters . $orderby);
$sql->bindParam(':id', $_GET['id']);

$sql->execute();
$datas = $sql->fetchAll(PDO::FETCH_ASSOC);

$sql = $db->prepare("SELECT count(*) as nb, status_code FROM crawl_logs WHERE crawl_id=:id GROUP BY status_code");
$sql->bindParam(':id', $_GET['id']);

$sql->execute();
$status_codes = $sql->fetchAll(PDO::FETCH_ASSOC);

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
    <form action="<?= WEB_HOME . '/result.php' ?>" method="get">
        <input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
        <?php if (isset($_GET['orderby'])) : ?>
            <input type="hidden" name="orderby" value="<?= $_GET['orderby'] ?>" />
        <?php endif; ?>
        <?php if (isset($_GET['order'])) : ?>
            <input type="hidden" name="order" value="<?= $_GET['order'] ?>" />
        <?php endif; ?>
        <?php foreach ($status_codes as $status_code) : ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="status<?= $status_code['status_code'] ?>" name="status_<?= $status_code['status_code'] ?>" value="<?= $status_code['status_code'] ?>"<?= isset($_GET['status_' . $status_code['status_code']]) ? ' checked' : null ?>>
                <label class="form-check-label <?= getStatusColor($status_code['status_code']) ?>" for="status<?= $status_code['status_code'] ?>"><b><?= $status_code['status_code'] . ' (' . $status_code['nb'] . ')' ?></b></label>
            </div>
        <?php endforeach; ?>
        <input type="submit" class="btn btn-success" value="Apply filter">
    </form>
    <br>
</div>
<table class="table">
    <thead>
        <tr>
            <th scope="col">
                <form action="<?= WEB_HOME . '/result.php' ?>" method="get">
                    <input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
                    <?php foreach ($_GET as $key => $value) : ?>
                        <?php if (strpos($key, 'status_') === false) {
                            continue;
                        }?>
                        <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                    <?php endforeach; ?>
                    <input type="hidden" name="orderby" value="status_code"/>
                    <input type="hidden" name="order" value="<?= isset($_GET['order']) && $_GET['order'] === 'DESC' ? 'ASC' : 'DESC' ?>"/>
                    <button type="submit" class="btn btn-link font-weight-bold">Status code <i class="fas fa-sort<?= isset($_GET['order']) && isset($_GET['orderby']) && $_GET['orderby'] === 'status_code' ? $_GET['order'] === 'ASC' ? '-up' : '-down' : null ?>"></i></button>
                </form>
            </th>
            <th scope="col"><span class="btn font-weight-bold">Reason</span></th>
            <th scope="col"><span class="btn font-weight-bold">Url</span></th>
            <th scope="col">
                <form action="<?= WEB_HOME . '/result.php' ?>" method="get">
                    <input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
                    <?php foreach ($_GET as $key => $value) : ?>
                        <?php if (strpos($key, 'status_') === false) {
                            continue;
                        }?>
                        <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                    <?php endforeach; ?>
                    <input type="hidden" name="orderby" value="found_on"/>
                    <input type="hidden" name="order" value="<?= isset($_GET['order']) && $_GET['order'] === 'DESC' ? 'ASC' : 'DESC' ?>"/>
                    <button type="submit" class="btn btn-link font-weight-bold">Found on <i class="fas fa-sort<?= isset($_GET['order']) && isset($_GET['orderby']) && $_GET['orderby'] === 'found_on' ? $_GET['order'] === 'ASC' ? '-up' : '-down' : null ?>"></i></button>
                </form>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($datas as $data) : ?>
            <tr>
                <th scope="row" class="<?= getStatusColor($data['status_code']) ?>"><?= $data['status_code'] ?></th>
                <td><?= $data['reason'] ?></td>
                <td><a href="<?= $data['url'] ?>" target="_blank"><?= $data['url'] ?></a></td>
                <td><a href="<?= $data['found_on'] ?>" target="_blank"><?= $data['found_on'] ?></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'end_html.php' ?>
