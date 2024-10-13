<?php

/*
*
* ExampleController.php
*
*/

namespace {{ appName }}\App\Frontend\Modules\Example;

use \Blackfox\Mamba\BackController;
use \Blackfox\Mamba\HTTPRequest;

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
