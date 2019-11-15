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
-- --------------------------------------------------------

--
-- Structure de la table `crawls`
--

CREATE TABLE `crawls` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `url_count` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `crawl_logs`
--

CREATE TABLE `crawl_logs` (
  `id` int(11) NOT NULL,
  `crawl_id` int(11) NOT NULL,
  `status_code` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `found_on` text,
  `crawled_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `crawls`
--
ALTER TABLE `crawls`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `crawl_logs`
--
ALTER TABLE `crawl_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `crawl_id` (`crawl_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `crawls`
--
ALTER TABLE `crawls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `crawl_logs`
--
ALTER TABLE `crawl_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
```
