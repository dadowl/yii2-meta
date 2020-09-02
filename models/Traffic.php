<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "traffic".
 *
 * @property int $id
 * @property string|null $ts_name
 * @property string|null $domain_name
 *
 * @property Campaigns[] $campaigns
 */
class Traffic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'traffic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ts_name', 'domain_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ts_name' => 'Traffic Source Name',
            'domain_name' => 'Domain Name',
        ];
    }

    /**
     * Gets query for [[Campaigns]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCampaigns()
    {
        return $this->hasMany(Campaigns::className(), ['trafic_id' => 'id']);
    }
}
