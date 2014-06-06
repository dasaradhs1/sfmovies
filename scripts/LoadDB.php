<?php
class LoadDB {

	private static $_instance = null;
	private $_data;
	private $_memcache;
	private $_col;
	private $_mongo;
	

	public function __construct()
	{
	echo "in construct \n";	
	$this->_mongo = new MongoClient();
	$db=$this->_mongo->sfmovies;
	$this->_col = $db->movies; 
	$this->_getTitles();
	
	}


	private function _getTitles() 
	{
		$url='http://data.sfgov.org/resource/yitu-d5am.json';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$head = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		echo $httpCode;
		if($httpCode == 200) {
			$this->_processData($head);
		}
		curl_close($ch);

	}
	
	private function _processData($data)
	{
		if(!empty($data)) {
			$temp=json_decode($data,true);
			foreach($temp as $arr) {
				sleep(2);
				$a['video']=htmlspecialchars($this->_getVideo($arr['title']));
				$aa=$a['video'];
				echo "$aa \n";
				//print_r($arr);
				$a['loc']=$this->_getCoords(preg_replace('/\&/', 'and', $arr['locations']));
				if(!empty($a['loc']['lat'])) {
				$a['neighborhood']=$this->_getHood($a['loc']['lat'],$a['loc']['lng']);
				} else {
				$a['neighborhood']='San Francisco';
				}
				$a['location']=$arr['locations'];
				$a['title']=$arr['title'];
				$a['actors']=array($arr['actor_1'],$arr['actor_2'],$arr['actor_3']);
				$a['director']=$arr['director'];
				$a['fun']=$arr['fun_facts'];
				$a['writer']=$arr['writer'];
				$a['company']=$arr['production_company'];
				$a['year']=$arr['release_year'];
				$a['hash']=md5($arr['title'].$arr['locations']);
				$a['_id']=new MongoId();
				try {
				$this->_col->insert($a);
				} catch (Exception $e) {
					echo $e->getMessage()."\n";
					echo json_encode($a)."\n";
				}
				unset($this->_mongo->_id);
				//echo json_encode($a);
				
			}

		}
	
	}
	private function _getVideo($title) {

$upcoming = simplexml_load_file("http://api.traileraddict.com/?film=".urlencode($title)."&count=1");
foreach($upcoming->trailer as $x => $updates)
{
   
   //embed trailer
   $aa=$updates->embed;
	if(!empty($aa)) {
		//echo " $title \n$aa \n";
		return $aa;
	} else {
		return '';
	}
    
    } 


	}

	private function _getCoords($text) {
		echo $text."\n";
		$url='https://maps.googleapis.com/maps/api/place/textsearch/json?query='.urlencode($text.' san francisco').'&key=AIzaSyCQz-oLKYTxaVJsvuFSlPbYWjMcwtZugoc';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$head = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$aa=json_decode($head,true);
		print_r($aa);
		$ret=$aa['results'][0]['geometry']['location'];
	 	return $ret;	

	}
	private function _getHood($lat,$lng) {
		echo $text."\n";
		$url='https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&result_type=neighborhood&key=AIzaSyCQz-oLKYTxaVJsvuFSlPbYWjMcwtZugoc';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$head = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$aa=json_decode($head,true);
		print_r($aa);
		$ret=$aa['results'][0]['address_components'][0]['long_name'];	
		echo $ret."\n";
	 	return $ret;	

	}

	public function retData() {
		return $this->_data;

	}













}
?>
