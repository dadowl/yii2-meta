<?php

namespace app\commands;

use Yii;
use yii\db\Command;
use yii\console\Controller;

class UpdateController extends Controller{

    public function actionReload() {

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
    	echo "Update the DB..\n";
        foreach ($out as $val){
			$post = Yii::$app->db->createCommand('SELECT * FROM traffic WHERE ts_name=:ts_name AND domain_name=:domain_name')
	           ->bindValue(':ts_name', $val['ts_name'])
	           ->bindValue(':domain_name', $val['domain_name'])
	        ->queryOne();
			if (!$post){
				$db->createCommand('INSERT INTO traffic (ts_name,domain_name) VALUES (:ts_name,:domain_name)', [
				    ':ts_name' => $val['ts_name'],
				    ':domain_name' => $val['domain_name'],
				])->execute();
			}

			$post = Yii::$app->db->createCommand('SELECT * FROM traffic WHERE ts_name=:ts_name AND domain_name=:domain_name')
	           ->bindValue(':ts_name', $val['ts_name'])
	           ->bindValue(':domain_name', $val['domain_name'])
	        ->queryOne();

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
			    ':trafic_id' => $post['id'],
			])->execute();

    	}

    	echo "New data loaded..\n";
        return 1;
        

        
    }
}
