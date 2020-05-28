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
* This is the class for REST controller "TicketController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class TicketController extends \yii\rest\ActiveController
{
public $modelClass = 'open20\amos\ticket\models\Ticket';
}
