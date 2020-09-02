<?php

namespace app\commands;

use Yii;
use yii\db\Command;
use yii\console\Controller;
use app\models\Traffic;
use app\models\Campaigns;

class UpdateController extends Controller{

    public function actionReload() {
    	$start = microtime(true);

    	if ($db === null) {
            $db = Yii::$app->getDb();
        }

        Yii::$app->db->createCommand()->truncateTable('campaigns')->execute();
        Yii::$app->db->createCommand()->truncateTable('traffic')->execute();
        $db->createCommand('INSERT INTO update_log (logs) VALUES (NOW())', [])->execute();


        if( $curl = curl_init() ) {
        	echo "Read the API..\n";
            curl_setopt($curl, CURLOPT_URL, 'http://209.97.133.143/tz/api.php?key=20e62b46af322affca0d9bccb7a');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $out = json_decode(curl_exec($curl),true);
            curl_close($curl);
		} else {
        	echo "Error!";
        	return 1;
        }

        // name
        // clicks
        // leads
        // conversion_rate = 
        // 
        // domain_name
        // ts_name
        /*

        echo  "name = ".$val['name']
    		." clicks = ".$val['clicks']
    		." leads = ".$val['leads']
    		." domain_name = ".$val['domain_name']
    		." ts_name = ".$val['ts_name']
    		."\n";

    	*/

    	echo "Reading took: ".round(microtime(true) - $start, 4)." s\n";
    	echo "Update the DB..\n";
        foreach ($out as $val){
			/*
			old version of update script

			$traffic = Yii::$app->db->createCommand('SELECT * FROM traffic WHERE ts_name=:ts_name AND domain_name=:domain_name')
	           ->bindValue(':ts_name', $val['ts_name'])
	           ->bindValue(':domain_name', $val['domain_name'])
	        ->queryOne();
			if (!$traffic){
				$db->createCommand('INSERT INTO traffic (ts_name,domain_name) VALUES (:ts_name,:domain_name)', [
				    ':ts_name' => $val['ts_name'],
				    ':domain_name' => $val['domain_name'],
				])->execute();
				$traffic = Yii::$app->db->createCommand('SELECT * FROM traffic WHERE ts_name=:ts_name AND domain_name=:domain_name')
		           ->bindValue(':ts_name', $val['ts_name'])
		           ->bindValue(':domain_name', $val['domain_name'])
		        ->queryOne();
			}
	        if ($val['leads'] != 0){
				$rate = ($val['leads']/$val['clicks']) * 100;
	        } else {
	        	$rate = 0;
	        }
	        $db->createCommand('INSERT INTO campaigns (name,clicks,leads,conversion_rate,trafic_id) VALUES (:name,:clicks,:leads,:conversion_rate,:trafic_id)', [
			    ':name' => $val['name'],
			    ':clicks' => $val['clicks'],
			    ':leads' => $val['leads'],
			    ':conversion_rate' => ($rate),
			    ':trafic_id' => $traffic['id'],
			])->execute();*/


			// new version

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
