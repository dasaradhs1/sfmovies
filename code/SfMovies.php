<?php
include('Locations.php');
//type of request
//1 is to get autocomplete data
//2 is is to get data for all movies
//3 is to get from a filtered search
$var=$_POST['var'];

//explode param by : key 0 is type key 1 is the search param
$paramPre=trim($_POST['param']);
$paramArr=explode(':',$paramPre);

//Memcache to save objects
//data save for a day
$m = new Memcache();
$m->addServer('localhost', 11211);

// md5 of concat of request type, param type, paramn for memcache key 
$md5=md5($var.$paramArr[0].$paramArr[1]);

if(!$m->get($md5)) {
	$a= new Locations();
} else {
	$mem=true;
	$a=$m->get($md5);
}

switch($var) {
	case 1:
		if(!$mem) {
			$a->getAllTitles();
			$m->set($md5, $a,0, time() + 86400);
		}
		$b=$a->retData();
		header("Content-Type: application/json");
	break;

	case 2:

		if(!$mem) {
			$a->getAllMoviesData();
			$m->set($md5, $a,0, time() + 86400);
		}
		$b=$a->retData();
		break;
	case 3:

		if(!$mem) {
			$a->getSingleMovieData($paramArr[0],$paramArr[1]);
			$m->set($md5, $a,0, time() + 86400);
		}
		$b=$a->retData();

	break;

}
//return data to frontend via json 
echo json_encode($b);







?>
