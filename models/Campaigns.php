<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "campaigns".
 *
 * @property int $id
 * @property string $name
 * @property int|null $clicks
 * @property int|null $leads
 * @property float|null $conversion_rate
 * @property int|null $trafic_id
 *
 * @property Traffic $trafic
 */
class Campaigns extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaigns';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['clicks', 'leads', 'trafic_id'], 'integer'],
            [['conversion_rate'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['trafic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Traffic::className(), 'targetAttribute' => ['trafic_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'clicks' => 'Clicks',
            'leads' => 'Leads',
            'conversion_rate' => 'Conversion Rate',
            'trafic_id' => 'Trafic ID',
        ];
    }

    /**
     * Gets query for [[Trafic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrafic()
    {
        return $this->hasOne(Traffic::className(), ['id' => 'trafic_id']);
    }
}
