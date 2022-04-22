<?php
//routing of the requests happens here
//maping urls to classes and methods

/*
multiline comment
*/
namespace app\core;

class App{

	private $controller = 'app\\controllers\\WebClient'; //set a default value for the controller
	private $method = 'login';
	private $params = [];

	public function __construct(){
		//TODO: implement the routing to map the URL to the actual controllers and methods
		//map urls such as localhost/controllername/methodname to the execution of method methodname from class controllername
		//eg. http://localhost/Main/index maps to the index method of the Main controller class
		//e.g. http://localhost/Animal/breed/param1/param2
		//maps to the breed method of the Animal controller class with parameters param1 and param2

		//parse incoming urls to an array containing the url components
		$url = $this->parseURL();

		//check and implement the controller
		if(isset($url[0])){ //are there contents in the $url[0] element
			if(file_exists('app/controllers/' . $url[0] . '.php')){
				$this->controller = 'app\\controllers\\' . $url[0];
			}

			unset($url[0]);//deleting $url[0] from memory. 
		}
		//$this->controller becomes an object of the requested type
		$this->controller = new $this->controller;

		//check and choose the method
		if(isset($url[1])){
			if(method_exists($this->controller, $url[1])){
				$this->method = $url[1];
			}
			unset($url[1]);
		}

		//take care of any parameter
		$this->params = $url ? array_values($url) : [];

		//apply access filtering
		//get the attributes
		$reflection = new \ReflectionObject($this->controller);
		$controllerAttributes = $reflection->getAttributes();
		$methodAttributes = $reflection->getMethod($this->method)->getAttributes();
		//merge
		$filters = array_values(array_filter(array_merge($controllerAttributes, $methodAttributes)));
		//invoke the filter methods
		foreach($filters as $filter){
			$filter = $filter->newInstance();
			if($filter->execute())
				return;//stop execution before the call_user_func_array method runs
		}

		//run this command below:
		call_user_func_array(array($this->controller, $this->method), $this->params);
	}

	//"Default/index"
	//["Default", "index"]
	public function parseURL(){
		//check that the url is passed as a GET parameter
		if(isset($_GET['url'])){ //assuming we have passed the url (http://localhost/index.php?url=/the/url/goes/here => ['the','url','goes','here']) 
			return explode('/', 
				filter_var(
					rtrim($_GET['url'], '/'),//remove he trailing /
					 FILTER_SANITIZE_URL)//filter out non-url compliant characters
			);
		}
	}



}