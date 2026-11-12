<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\core\views\layouts
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use backend\assets\ModuleFrontendAsset;

/* @var $this \yii\web\View */
/* @var $content string */

//\bedezign\yii2\audit\web\JSLoggingAsset::register($this);
AppAsset::register($this);
//ModuleFrontendAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>

<html lang="<?= Yii::$app->language ?>">

    <head>          
        <?php $this->head() ?>        
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <meta charset="UTF-8">
        <title>Pane e Internet</title>
        <link rel="stylesheet" href="/frontend/css/bootstrap.min.css" />
        <link rel="stylesheet" href="/frontend/css/flexslider.css" />
        <link rel="stylesheet" href="/frontend/css/bootstrap-responsive-tabs.css" />
        <link rel="stylesheet" href="/frontend/css/style.css" />
        <link rel="stylesheet" href="/frontend/css/fonts.css" />
        <script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="/frontend/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/frontend/js/modernizr.custom.62816.js"></script> 
        <script type="text/javascript" src="/frontend/js/jquery.flexslider-min.js"></script> 
        <script type="text/javascript" src="/frontend/js/jquery.bootstrap-responsive-tabs.min.js"></script>                   <script type="text/javascript">
    window.cookieconsent_options = {"message":"Per offrire informazioni e servizi nel miglior modo possibile, questo sito utilizza cookie tecnici e cookie di terze parti.","dismiss":"Ok!","learnMore":"Per maggiori informazioni","link":"//www.paneeinternet.it/public/policy-privacy","theme":"dark-bottom"};
        </script>  


        <!--[if lt IE 9]>
        <script src="/frontend/js/html5shiv.js"></script>
        <script src="/frontend/js/respond.js"></script> 
        <![endif]-->
        <title><?= Html::encode(Yii::$app->name) ?></title>

    </head>
    <body>
        <?php $this->beginBody() ?>

        <a href="#mainContent" class="sr-only sr-only-focusable">Skip to main content</a> 
        
        <?= $content ?>              
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

