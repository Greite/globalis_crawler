# Crawler

## Installation

Use [composer](https://getcomposer.org/) to install the project.

```bash
composer install
```

Edit the samples in config directory and fill them

```bash
cp config/db.sample.php config/db.php
```

```bash
cp config/env.sample.php config/env.php
```
Database structure

```sql
CREATE TABLE `crawls` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `url_count` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `started_at` datetime DEFAULT NULL,
  `finished_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `crawl_logs` (
  `id` int(11) NOT NULL,
  `crawl_id` int(11) NOT NULL,
  `status_code` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `found_on` text,
  `redirect_to` text,
  `crawled_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `crawls`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `crawl_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `crawl_id` (`crawl_id`);

ALTER TABLE `crawls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `crawl_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
```
