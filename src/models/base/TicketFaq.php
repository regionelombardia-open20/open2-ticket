<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos\ticket\models\base
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

namespace open2\amos\ticket\models\base;

use open2\amos\ticket\AmosTicket;

/**
 * This is the base-model class for table "ticket_faq".
 *
 * @property integer $id
 * @property string $domanda
 * @property string $risposta
 * @property integer $ticket_categoria_id
 * @property integer $version
 * @property integer $total_rating
 * @property integer $total_votes
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open2\amos\ticket\models\TicketCategorie $ticketCategoria
 */
class TicketFaq extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ticket_faq';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domanda', 'risposta'], 'string'],
            [['ticket_categoria_id', 'domanda', 'risposta'], 'required'],
            [['ticket_categoria_id', 'version', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['ticket_categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => TicketCategorie::className(), 'targetAttribute' => ['ticket_categoria_id' => 'id']],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosTicket::t('amosticket', 'Id'),
            'domanda' => AmosTicket::t('amosticket', 'Domanda'),
            'risposta' => AmosTicket::t('amosticket', 'Risposta'),
            'ticket_categoria_id' => AmosTicket::t('amosticket', 'Categoria'),
            'created_at' => AmosTicket::t('amosticket', 'Creato il'),
            'updated_at' => AmosTicket::t('amosticket', 'Aggiornato il'),
            'deleted_at' => AmosTicket::t('amosticket', 'Cancellato il'),
            'created_by' => AmosTicket::t('amosticket', 'Creato da'),
            'updated_by' => AmosTicket::t('amosticket', 'Aggiornato da'),
            'deleted_by' => AmosTicket::t('amosticket', 'Cancellato da'),
            'version' => AmosTicket::t('amosticket', 'Versione numero'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketCategoria()
    {
        return $this->hasOne(\open2\amos\ticket\models\TicketCategorie::className(), ['id' => 'ticket_categoria_id']);
    }
}
