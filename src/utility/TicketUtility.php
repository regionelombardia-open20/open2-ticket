<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\ticket\utility
 * @category   CategoryName
 */

namespace open20\amos\ticket\utility;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\OrganizationsModuleInterface;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\user\User;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloSedi;
use open20\amos\ticket\models\Ticket;
use open20\amos\ticket\models\TicketCategorie;
use open20\amos\ticket\models\TicketCategorieUsersMm;
use Yii;
use yii\base\BaseObject;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class TicketUtility
 * @package open20\amos\ticket\utility
 */
class TicketUtility extends BaseObject
{
    /**
     * @param TicketCategorie|null $excludeCategory
     * @param bool $onlyTicketEnabled
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getTicketCategories($excludeCategory = null, $onlyTicketEnabled = false)
    {
        $excludeCategoryIdList = TicketUtility::getIdDiscendenti($excludeCategory);

        // @var ActiveQuery $query 
        $query = TicketCategorie::find();
        $query->andFilterWhere(['not in',
            'id', $excludeCategoryIdList
        ]);
        if ($onlyTicketEnabled) {
            $query->andWhere(['abilita_ticket' => true]);
        }

        $abilita_per_community = false;

        // If scope set, filter categories for cwh
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (!is_null($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
            if (!empty($scope) && isset($scope['community'])) {
                $abilita_per_community = true;
                $query->andFilterWhere([
                    'community_id' => $scope['community'],
                ]);
            }
        }

        $query->andFilterWhere([
            'abilita_per_community' => $abilita_per_community,
        ]);

        return $query;
    }

    /**
     * @param TicketCategorie|null $category
     * @return array
     */
    private static function getIdDiscendenti($category = null)
    {
        $arrayIdDiscendenti = [];
        if (!is_null($category) && $category->id) {
            $arrayIdDiscendenti[] = $category->id;
            foreach ($category->categorieFiglie as $catFiglia) {
                $arrayIdDiscendenti = array_merge($arrayIdDiscendenti, TicketUtility::getIdDiscendenti($catFiglia));
            }
        }
        return $arrayIdDiscendenti;
    }

    /**
     * @param int $ticket_categoria_id
     * @param bool $alsoAdmin
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getReferenti($ticket_categoria_id, $alsoAdmin = true/*, $print = false*/)
    {
        $ticketCatUserMmTable = TicketCategorieUsersMm::tableName();

        /** @var UserProfile $userProfileModel */
        $userProfileModel = AmosAdmin::instance()->createModel('UserProfile');

        //cerco gli user_profile referenti di una categoria
        /** @var ActiveQuery $q */
        $q = $userProfileModel::find()
            ->innerJoin($ticketCatUserMmTable, $ticketCatUserMmTable . '.user_profile_id = ' . $userProfileModel::tableName() . '.id')
            ->andWhere([$ticketCatUserMmTable . '.deleted_at' => null])
            ->andWhere([$ticketCatUserMmTable . '.ticket_categoria_id' => $ticket_categoria_id]);
        $q->andWhere([UserProfile::tableName() . '.attivo' => UserProfile::STATUS_ACTIVE]);
        $q->andWhere(['!=', UserProfile::tableName() . '.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);

        $referentiUserProfile = $q->all();
        $adminTicketUser = ($alsoAdmin) ? self::getAllAdminTicketUsers() : [];

        $referenti = ArrayHelper::merge($referentiUserProfile, $adminTicketUser);

        // TODO - togliere i duplicati di questo metodo. Mettere tutto in un'unica utility e sistemare anche le relazioni dei model.

        return $referenti;
    }

    /**
     * @param int $ticket_categoria_id
     * @param bool $alsoAdmin
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getEmailReferentiCategoria($ticket_categoria_id, $alsoAdmin = true)
    {
        $emails = [];
        //cerco gli user_profile referenti di una categoria
        /** @var ActiveQuery $q */
        $q = UserProfile::find()
            ->innerJoin('ticket_categorie_users_mm', 'ticket_categorie_users_mm.user_profile_id = user_profile.id')
            ->andWhere(['ticket_categorie_users_mm.deleted_by' => null])
            ->andWhere(['ticket_categorie_users_mm.ticket_categoria_id' => $ticket_categoria_id]);
        $q->andWhere([UserProfile::tableName() . '.attivo' => UserProfile::STATUS_ACTIVE]);
        $q->andWhere(['!=', UserProfile::tableName() . '.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);
        $referentiUserProfile = $q->all();

        //ritorno un array con le email degli user referenti
        if (!is_null($referentiUserProfile)) {
            foreach ($referentiUserProfile as $userRecord) {
                $emails[] = $userRecord->user->email;
            }
        }
        if ($alsoAdmin) {
            $emails = ArrayHelper::merge($emails, self::getAllAdminTicketUsersEmail());
        }
        if (count($emails) > 1) {
            $emails = array_unique($emails);
        }

        return $emails;
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getAllAdminTicketUsers()
    {
        $adminTicketUserIds = \Yii::$app->getAuthManager()->getUserIdsByRole('AMMINISTRATORE_TICKET');

        $userProfile = AmosAdmin::instance()->createModel('UserProfile');
        /** @var ActiveQuery $query */
        $query = $userProfile::find()
            ->andWhere(['user_id' => $adminTicketUserIds])
            ->orderBy(['cognome' => SORT_ASC, 'nome' => SORT_ASC]);
        $query->andWhere([UserProfile::tableName() . '.attivo' => UserProfile::STATUS_ACTIVE]);
        $query->andWhere(['!=', UserProfile::tableName() . '.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);

        $adminTicketUser = $query->all();

        return $adminTicketUser;
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getAllAdminTicketUsersEmail()
    {
        $adminTicketUser = self::getAllAdminTicketUsers();
        $emails = [];
        if (!is_null($adminTicketUser)) {
            foreach ($adminTicketUser as $userRecord) {
                $emails[] = $userRecord->user->email;
            }
        }
        return $emails;
    }

    /**
     * @return bool
     */
    public static function hasPartnership()
    {
        /** @var AmosAdmin $adminModule */
        $adminModule = AmosAdmin::instance();
        $organizationModuleName = $adminModule->getOrganizationModuleName();
        return (!is_null(Yii::$app->getModule($organizationModuleName)));
    }

    /**
     * @param User|null $user
     * @return \open20\amos\admin\interfaces\OrganizationsModuleInterface|\open20\amos\organizzazioni\models\Profilo|\openinnovation\organizations\models\Organizations|null
     * @throws \yii\base\InvalidConfigException
     */
    public static function getUserPrevalentPartnership($user = null)
    {
        if (self::hasPartnership()) {
            if (is_null($user)) {
                $user = Yii::$app->getUser();
            }
            $userProfile = UserProfile::find()->andWhere(['user_id' => $user->id])->one();
            $partnership = $userProfile->prevalentPartnership;
            return $partnership;
        } else {
            return null;
        }
    }

    /**
     * Make the value for organizations in new ticket select
     * @param Profilo $organization
     * @return string
     */
    public static function makeOrganizationIndexForTicketSelect($organization)
    {
        return Ticket::PARTNERSHIP_TYPE_ORGANIZATION . '-' . $organization->id;
    }

    /**
     * Make the value for headquarters in new ticket select
     * @param ProfiloSedi $headquarter
     * @return string
     */
    public static function makeHeadquarterIndexForTicketSelect($headquarter)
    {
        return Ticket::PARTNERSHIP_TYPE_HEADQUARTER . '-' . $headquarter->id;
    }

    /**
     * @param int $userId
     * @return array
     */
    public static function getOrganizationsAndHeadquartersByUserId($userId)
    {
        $organizationsAndHeadquarters = [];
        /** @var AmosAdmin $adminModule */
        $adminModule = AmosAdmin::instance();
        if (!is_null($adminModule)) {
            $organizationsModuleName = $adminModule->getOrganizationModuleName();
            $organizationsModule = Yii::$app->getModule($organizationsModuleName);
            if (!is_null($organizationsModule) && ($organizationsModule instanceof OrganizationsModuleInterface)) {
                $userOrganizations = $organizationsModule->getUserOrganizations($userId);
                foreach ($userOrganizations as $userOrganization) {
                    /** @var Profilo $userOrganization */
                    $organizationsAndHeadquarters[static::makeOrganizationIndexForTicketSelect($userOrganization)] = $userOrganization->getNameField();
                }
                $userHeadquarters = $organizationsModule->getUserHeadquarters($userId);
                foreach ($userHeadquarters as $userHeadquarter) {
                    /** @var ProfiloSedi $userHeadquarter */
                    $organizationsAndHeadquarters[static::makeHeadquarterIndexForTicketSelect($userHeadquarter)] = $userHeadquarter->getNameField() . ' (' . $userHeadquarter->profilo->getNameField() . ')';
                }
            }
        }
        return $organizationsAndHeadquarters;
    }

    /**
     * Return an array with the values used in boolean fields. If the param 'invertValues' is true the values are returned inverted.
     * @param bool $invertValues
     * @return array
     */
    public static function getBooleanFieldsValues($invertValues = false)
    {
        if ($invertValues) {
            return [
                Html::BOOLEAN_FIELDS_VALUE_YES => BaseAmosModule::t('amoscore', 'Yes'),
                Html::BOOLEAN_FIELDS_VALUE_NO => BaseAmosModule::t('amoscore', 'No')
            ];
        } else {
            return [
                Html::BOOLEAN_FIELDS_VALUE_NO => BaseAmosModule::t('amoscore', 'No'),
                Html::BOOLEAN_FIELDS_VALUE_YES => BaseAmosModule::t('amoscore', 'Yes')
            ];
        }
    }
}
