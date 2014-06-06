<?php
require('../code/Locations.php');
class LocationTest extends PHPUnit_Framework_TestCase {
	

  public function testAll() {
    $loc= new Locations();
    $loc->getAllMoviesData();
    $data=$loc->retData();
    $this->assertArrayHasKey('loc', $data[0]);
    $this->assertArrayHasKey('title', $data[0]);
    $this->assertArrayHasKey('actors', $data[0]);
    $this->assertArrayHasKey('director', $data[0]);
    $this->assertArrayHasKey('fun', $data[0]);
    $this->assertArrayHasKey('writer', $data[0]);
    $this->assertArrayHasKey('company', $data[0]);
    $this->assertArrayHasKey('location', $data[0]);
    $this->assertArrayHasKey('year', $data[0]);
    $this->assertArrayHasKey('video', $data[0]);


    $loc->clearData();
    $loc->getAllTitles();
    $data=$loc->retData();
    $this->assertNotEmpty($data);


    $loc->clearData();
    $loc->getSingleMovieData('title','The Internship');
    $data=$loc->retData();
    $this->assertArrayHasKey('loc', $data[0]);
    $this->assertArrayHasKey('title', $data[0]);
    $this->assertArrayHasKey('actors', $data[0]);
    $this->assertArrayHasKey('director', $data[0]);
    $this->assertArrayHasKey('fun', $data[0]);
    $this->assertArrayHasKey('writer', $data[0]);
    $this->assertArrayHasKey('company', $data[0]);
    $this->assertArrayHasKey('location', $data[0]);
    $this->assertArrayHasKey('year', $data[0]);
    $this->assertArrayHasKey('video', $data[0]);

  }


}

?>
