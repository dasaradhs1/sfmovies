<?php
class Locations {

	private static $_instance = null;
	private $_data;
	private $_memcache;
	private $_col;
	private $_mongo;
	private $_db;
	

	public function __construct()
	{
	$this->_mongo = new MongoClient();
	$this->_db=$this->_mongo->sfmovies;
	$this->_col = $this->_db->movies; 
	
	}

	
	public function getAllMoviesData()
	{
	$cursor=$this->_col->find(array("loc" => array('$ne' => null)));
		$b=0;
	        foreach ($cursor as $arr) {
				$this->_data[$b]['loc']=$arr['loc'];
                                $this->_data[$b]['title']=$arr['title'];
                                $this->_data[$b]['actors']=$arr['actors'];
                                $this->_data[$b]['director']=$arr['director'];
                                $this->_data[$b]['fun']=$arr['fun'];
                                $this->_data[$b]['writer']=$arr['writer'];
                                $this->_data[$b]['company']=$arr['company'];
                                $this->_data[$b]['location']=$arr['location'];
                                $this->_data[$b]['year']=$arr['year'];
                                $this->_data[$b]['video']=htmlspecialchars_decode($arr['video']);
				$b++;

}
	}

	public function getSingleMovieData($cat,$param)
	{
	//preg_replace('/[^A-Za-z0-9\-]/', '', $title);
	switch($cat) {
		case 'title':
			$key="title";
		break;
		case 'area':
			$key="neighborhood";
		break;
		case 'actor':
			$key="actors";
		break;
		case 'year':
			$key="year";
		break;


	}
	// some basic safetey
	$param=stripslashes($param);
	$cursor=$this->_col->find(array("$key" => $param));
		$b=0;
	        foreach ($cursor as $arr) {
				$this->_data[$b]['loc']=$arr['loc'];
                                $this->_data[$b]['title']=$arr['title'];
                                $this->_data[$b]['actors']=$arr['actors'];
                                $this->_data[$b]['director']=$arr['director'];
                                $this->_data[$b]['fun']=$arr['fun'];
                                $this->_data[$b]['writer']=$arr['writer'];
                                $this->_data[$b]['company']=$arr['company'];
                                $this->_data[$b]['location']=$arr['location'];
                                $this->_data[$b]['year']=$arr['year'];
                                $this->_data[$b]['video']=htmlspecialchars_decode($arr['video']);
				$b++;

}
	}

	public function getAllTitles()
	{
	$cursor=$this->_col->distinct("title",array("loc" => array('$ne' => null)));
	        foreach ($cursor as $arr) {
				//print_r($arr);
                                $this->_data[]="title:".$arr;	
				

		}
		$b=count($this->_data);
	$cursor=$this->_col->distinct("actors",array("loc" => array('$ne' => null)));
	        foreach ($cursor as $arr) {
				//print_r($arr);
                                $this->_data[$b]="actor:".$arr;	
			        $b++;	

		}
		$b=count($this->_data);
	$cursor=$this->_col->distinct("neighborhood",array("loc" => array('$ne' => null)));
	        foreach ($cursor as $arr) {
				//print_r($arr);
                                $this->_data[$b]="area:".$arr;	
			        $b++;	

		}
		$b=count($this->_data);
	$cursor=$this->_col->distinct("year",array("loc" => array('$ne' => null)));
	        foreach ($cursor as $arr) {
				//print_r($arr);
                                $this->_data[$b]="year:".$arr;	
			        $b++;	

		}
	}

        public function clearData() {
                unset($this->_data);

        }

	public function retData() {
		return $this->_data;

	}













}
?>
