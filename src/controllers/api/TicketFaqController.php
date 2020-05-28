<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

namespace open20\amos\ticket\controllers\api;

/**
* This is the class for REST controller "TicketFaqController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class TicketFaqController extends \yii\rest\ActiveController
{
public $modelClass = 'open20\amos\ticket\models\TicketFaq';
}
