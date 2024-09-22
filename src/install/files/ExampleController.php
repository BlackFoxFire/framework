<?php

/*
*
* ExampleController.php
*
*/

namespace {{ appName }}\App\Frontend\Modules\Example;

use \Mamba\BackController;
use \Mamba\HTTPRequest;

class ExampleController extends BackController
{

    /*
		Les méthodes
		------------
	*/

    protected function executeIndex(HTTPRequest $request): void
    {
        $this->view->setData('hello', "Hello World !");
    }

}
