<?php

App::uses('AppController','Controller');

class UiController extends AppController{



	public function ui($elements){

		if($elements == 'login')

			$this->layout = 'mems-login';



		if($elements == '404')

			//$this->layout = 'mems-404';	
		    $this->layout = 'mems-lost';



		if($elements == '500')

			$this->layout = 'mems-500';		


		$this->render("ui.".$elements);

	}

}