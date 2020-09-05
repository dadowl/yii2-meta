<?php

namespace app\commands;

use Yii;
use yii\db\Command;
use yii\console\Controller;
use app\models\Traffic;
use app\models\Campaigns;
use app\models\UpdateLog;

class UpdateController extends Controller{

    public function actionReload() {
    	$start = microtime(true);

        if ($db === null) {
            $db = Yii::$app->getDb();
        }

        Campaigns::deleteAll();
        Traffic::deleteAll();

         $db->createCommand('INSERT INTO update_log (logs) VALUES (NOW())', [])->execute();


        if( $curl = curl_init() ) {
        	echo "Read the API..\n";
            curl_setopt($curl, CURLOPT_URL, 'http://209.97.133.143/tz/api.php?key=20e62b46af322affca0d9bccb7a');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $out = json_decode(curl_exec($curl),true);
            curl_close($curl);
		} else {
        	echo "Error!";
        	return 1;
        }

    	echo "Reading took: ".round(microtime(true) - $start, 4)." s\n";
    	echo "Update the DB..\n";
        foreach ($out as $val){

			$traffic = Traffic::find()->where(['ts_name' => $val['ts_name'],'domain_name' => $val['domain_name']])->one();
			if ($traffic == NULL){
				$model = new Traffic();
				$model->ts_name = $val['ts_name'];
				$model->domain_name = $val['domain_name'];
				$model->save();
				$traffic["id"] = $model->id;
			} 
			$model = new Campaigns();
			$model->name = $val['name'];
			$model->clicks = $val['clicks'];
			$model->leads = $val['leads'];
			$model->conversion_rate = ($val['leads']!=0 ? ($val['leads']/$val['clicks']) * 100 : 0);
			$model->trafic_id = $traffic["id"];
			$model->save();
    	}

    	echo "New data loaded..\n";
    	echo "Lead time: ".round(microtime(true) - $start, 2)." s\n";
        return 1;
        

        
    }
}
