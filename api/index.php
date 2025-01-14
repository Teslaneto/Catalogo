<?php

// dependencies
require_once(dirname(__FILE__) . '/inc/ApiResponse.php');
require_once(dirname(__FILE__) . '/inc/ApiLogic.php');
require_once(dirname(__FILE__) . '/../app/Models/Database.php');

// --------------------------------------------------------
$api_response = new ApiResponse();
// --------------------------------------------------------
if(!$api_response->check_method($_SERVER['REQUEST_METHOD']))
{
    $api_response->api_request_error('Invalid request method.');

}

// --------------------------------------------------------
$api_response->set_method($_SERVER['REQUEST_METHOD']);
$params = null;

if($api_response->get_method() == 'GET'){

    $api_response->set_endpoint($_GET['endpoint']);
    $params = $_GET;

} elseif($api_response->get_method() == 'POST'){

    $api_response->set_endpoint($_POST['endpoint']);
    $params = $_POST;

}

// --------------------------------------------------------
$api_logic = new ApiLogic($api_response->get_endpoint(), $params);
// --------------------------------------------------------

if(!$api_logic->endpoint_exists()){

    $api_response->api_request_error('Inexistent endpoint: ' . $api_response->get_endpoint());

}

$result = $api_logic->{$api_response->get_endpoint()}();
$api_response->add_do_data('data', $result);

$api_response->send_response();
// --------------------------------------------------------





