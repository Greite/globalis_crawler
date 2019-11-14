<?php
$cli = [
    'http://globalis.local/assistance-technique/',
    'http://globalis.local/carriere/',
    'http://globalis.local/references-client/fpspp-e-certif/',
    'http://globalis.local/media/2017/12/Conference-Scrum870X300.jpg',
    'http://globalis.local/offres-d-emploi/business-manager-ingenieur-daffaires/',
    'http://globalis.local/media/2017/10/Weekend-ski-S.-Bouchet.jpg',
    'http://globalis.local/media/2017/08/Vignette-itw-Jihane.jpg',
    'http://globalis.local/media/2017/09/Olivier-Huet-1.jpg',
    'http://globalis.local/media/2017/09/Olivier-Huet-2.jpg',
    'http://globalis.local/carriere-freelance-independant/',
    'http://globalis.local/references/fpspp-e-certif/',
    'http://globalis.local/offres/solutions/regie-php/',
    'http://globalis.local/offres/agence-digitale/',
    'http://globalis.local/offres/applications-web-et-mobile/',
    'http://globalis.local/offres/methodologies/agile/',
    'http://globalis.local/offres/technologies/codeigniter/',
    'http://globalis.local/offres/technologies/carbone/',
    'http://globalis.local/offres/technologies/wordpress/',
    'http://globalis.local/offres/technologies/php/',
    'http://globalis.local/media/2015/11/Capture-d%E2__%C3%A9cran-2015-04-02-%C3%A0-10.59.17.png',
    'http://globalis.local/media/2015/11/Capture-d%E2__%C3%A9cran-2015-04-02-%C3%A0-11.26.27-375x355.png',
    'http://globalis.local/media/2015/11/Capture-d%E2__%C3%A9cran-2015-04-02-%C3%A0-12.04.11.png',
    'http://globalis.local/offres/tierce-maintenance-applicative/',
    'http://globalis.local/references/maintenance-applications-cnrs/',
    'http://globalis.local/media/2017/09/Photo-Julien.jpg',
    'http://globalis.local/media/2017/10/Devops-Rex-png.png',
    'http://globalis.local/media/2017/10/Devops-Rex-4-e1507130461971.jpeg',
    'http://globalis.local/media/2017/10/classement-ESN-2017.png',
    'http://globalis.local/media/2017/09/logoFPHP2017-420x207.png',
    'http://globalis.local/media/2015/11/PSA_Peugeot_Citro%C3%ABn.svg_.png',
    'http://globalis.local/media/2015/11/info_globalis_finale.jpg',
    'http://globalis.local/offres/solutions/audit-migation-php7/',
    'http://globalis.local/societe/',
    'http://globalis.local/wp-content/uploads/2015/05/PSA_Peugeot_Citro%C3%ABn.svg_.png',
    'http://globalis.local/media/2015/11/Capture-d%E2__%C3%A9cran-2015-05-05-%C3%A0-11.44.36-346x355.png',
    'http://globalis.local/media/2015/02/Capture-d%E2__%C3%A9cran-2015-02-17-%C3%A0-14.31.18-800x480.png',
    'http://globalis.local/media/2015/11/Capture-d%E2__%C3%A9cran-2015-02-17-%C3%A0-14.28.53.png',
    'http://globalis.local/media/2015/11/Capture-d%E2__%C3%A9cran-2015-02-17-%C3%A0-15.06.29.png',
];

$http = [
    'http://globalis.local/assistance-technique/',
    'http://globalis.local/carriere/',
    'http://globalis.local/references-client/fpspp-e-certif/',
    'http://globalis.local/media/2017/12/Conference-Scrum870X300.jpg',
    'http://globalis.local/offres-d-emploi/business-manager-ingenieur-daffaires/',
    'http://globalis.local/media/2017/10/Weekend-ski-S.-Bouchet.jpg',
    'http://globalis.local/media/2017/09/Olivier-Huet-1.jpg',
    'http://globalis.local/media/2017/09/Olivier-Huet-2.jpg',
    'http://globalis.local/media/2017/08/Vignette-itw-Jihane.jpg',
    'http://globalis.local/references/fpspp-e-certif/',
    'http://globalis.local/carriere-freelance-independant/',
    'http://globalis.local/offres/solutions/regie-php/',
    'http://globalis.local/offres/agence-digitale/',
    'http://globalis.local/offres/applications-web-et-mobile/',
    'http://globalis.local/offres/methodologies/agile/',
    'http://globalis.local/offres/technologies/php/',
    'http://globalis.local/offres/technologies/wordpress/',
    'http://globalis.local/offres/technologies/carbone/',
    'http://globalis.local/offres/technologies/codeigniter/',
    'http://globalis.local/offres/tierce-maintenance-applicative/',
    'http://globalis.local/media/2017/10/Devops-Rex-png.png',
    'http://globalis.local/media/2017/10/Devops-Rex-4-e1507130461971.jpeg',
    'http://globalis.local/references/maintenance-applications-cnrs/',
    'http://globalis.local/media/2017/09/Photo-Julien.jpg',
    'http://globalis.local/societe/',
    'http://globalis.local/media/2017/10/classement-ESN-2017.png',
    'http://globalis.local/media/2017/09/logoFPHP2017-420x207.png',
    'http://globalis.local/offres/solutions/audit-migation-php7/',
    'http://globalis.local/media/2015/11/PSA_Peugeot_Citro%C3%ABn.svg_.png',
    'http://globalis.local/wp-content/uploads/2015/05/PSA_Peugeot_Citro%C3%ABn.svg_.png',
    'http://globalis.local/media/2015/11/info_globalis_finale.jpg',
];

foreach ($http as $test) {
    if (!in_array($test, $cli)) {
        echo $test . PHP_EOL;
    }
}
echo 'Fin http' . PHP_EOL;

foreach ($cli as $test) {
    if (!in_array($test, $http)) {
        echo $test . PHP_EOL;
    }
}
