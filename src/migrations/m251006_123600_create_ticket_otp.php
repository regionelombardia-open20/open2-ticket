<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\rules\TicketManagerInCommunityRoleRule;

/**
 * Class m251006_123600_create_ticket_otp
 */
class m251006_123600_create_ticket_otp extends \open20\amos\core\migration\AmosMigrationTableCreation
{

    protected function setTableName()
    {
        $this->tableName = '{{%ticket_otp}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'email' => $this->string(255)->notNull()->comment('Key Value'),
            'otp_code' => $this->string(255)->notNull()->comment('Key Value'),
            'request_count' => $this->integer(11)->comment('Number of times the OTP code has been requested in a time interval'),
            'total_request_count' => $this->integer(11)->comment('TOTAL Number of times the OTP code has been requested for the given email address'),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeTableCreation()
    {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }

}
