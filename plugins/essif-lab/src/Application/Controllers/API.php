<?php

namespace TNO\EssifLab\Application\Controllers;

use Exception;
use TNO\EssifLab\Application\Workflows\ManageHooks;
use TNO\EssifLab\Contracts\Abstracts\Core;

defined('ABSPATH') or die();

class API extends Core {
    private $requestMethod;

    private $manageHooks;

    public function __construct($requestMethod)
    {
        parent::__construct();
        $this->requestMethod = $requestMethod;

//        var_dump("pluginData", $this->getPluginData());

        $this->manageHooks = new ManageHooks($this->getPluginData(), get_post(52));
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                var_dump("get", $_POST);
//                if ($this->userId) {
//                    $response = $this->getUser($this->userId);
//                } else {
//                    $response = $this->getAllUsers();
//                };
                break;
            case 'POST':
                var_dump("post", $_POST);
                if (isset($_POST['name'])) {
                    switch ($_POST['name']) {
                        case 'essif-lab_hook':
                            if (isset($_POST['action'])) {
                                switch ($_POST['action']) {
                                    case 'delete':
                                        var_dump("_POST at top of page (if delete)", $_POST);
                                        $this->manageHooks->delete($_POST);
                                        break;
                                    default:
                                        throw new Exception("A valid action was not supplied");
                                }
                            }
                            break;
                        default:
                            throw new Exception("A valid name was not supplied");
                    }
                }
//                $response = $this->createUserFromRequest();
//                break;
            case 'PUT':
//                $response = $this->updateUserFromRequest($this->userId);
//                break;
            case 'DELETE':
//                $response = $this->deleteUser($this->userId);
//                break;
            default:
//                $response = $this->notFoundResponse();
//                break;
        }
//        header($response['status_code_header']);
//        if ($response['body']) {
//            echo $response['body'];
//        }
    }
}