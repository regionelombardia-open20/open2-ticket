<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos\ticket\migrations
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

use open20\amos\core\migration\AmosMigration;

/**
 * Class m190205_115611_alter_table_ticket_categorie
 */
class m190205_115611_alter_table_ticket_categorie extends AmosMigration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (\Yii::$app->db->getSchema()->getTableSchema('ticket_categorie')) {
            $this->addColumn('ticket_categorie', 'abilita_per_community', $this->boolean()->defaultValue(false)->notNull()->after('categoria_padre_id')->comment("Abilita visualizzazione per community"));
            $this->addColumn('ticket_categorie', 'community_id', $this->integer()->null()->defaultValue(null)->after('abilita_per_community')->comment("Categoria specifica per community"));
            $this->addForeignKey('ticket_categorie_community_id', 'ticket_categorie', 'community_id', 'community', 'id', 'NO ACTION', 'NO ACTION');
            echo "Column community_id and abilita_per_community and its foreign key added\n";
        } else {
            echo "Table ticket_categorie doesn't exist. Can't add column community_id and its foreign key and column abilita_per_community\n";
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if (\Yii::$app->db->getSchema()->getTableSchema('ticket_categorie')) {
            if (\Yii::$app->db->getSchema()->getTableSchema('ticket_categorie')->getColumn('community_id')) {
                $this->dropForeignKey('ticket_categorie_community_id', 'ticket_categorie');
                $this->dropColumn('ticket_categorie', 'community_id');
                echo "Column community_id and its foreign key deleted from table ticket_categorie\n";
            } else {
                echo "Can't find column community_id inside table ticket_categorie\n";
            }
            if (\Yii::$app->db->getSchema()->getTableSchema('ticket_categorie')->getColumn('abilita_per_community')) {
                $this->dropColumn('ticket_categorie', 'abilita_per_community');
                echo "Column abilita_per_community and its foreign key deleted from table ticket_categorie\n";
            } else {
                echo "Can't find column abilita_per_community inside table ticket_categorie\n";
            }
        } else {
            echo "Can't find table ticket_categorie\n";
        }
        return true;
    }
}
