<?php
	require 'vendor/autoload.php';
function autoloader() {
	require_once ('vietqr-generator-main/src/CRCHelper.php');
	require_once ('vietqr-generator-main/src/Constants.php');
	require_once ('vietqr-generator-main/src/Generator.php');
	require_once ('vietqr-generator-main/src/Helper.php');
	require_once ('vietqr-generator-main/src/InvalidBankIdException.php');
	require_once ('vietqr-generator-main/src/Response.php');
	require_once ('vietqr-generator-main/src/TransferInfo.php');
	require_once ('vietqr-generator-main/src/VietQRField.php');
}

spl_autoload_register('autoloader');


use tttran\viet_qr_generator\Generator;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
// endpoints start with /api/qr-generator
// else results in a 404 Not Found
if ($uri[1] !== 'qr-generator') {
    header("HTTP/1.1 404 Not Found");
    exit();
}


$genObj = new Generator();

// request method is Get
if ($_SERVER["REQUEST_METHOD"] == 'GET' && $uri[1] == 'qr-generator') {
	$gen = $genObj->create()
            ->bankId("TCB") // BankId, bankname
            ->accountNo("9704078889360132")// Account number
            ->amount(10000)// Money
            ->info("toto") // Ref
			->isCard(true)
            ->returnText(false); // if true, return text. If false, return image in base64
        $result = json_decode($gen->generate()); // Print text to generate QR Code
        echo $result->data;
	echo nl2br(" \n Test API");
}

if ($_SERVER["REQUEST_METHOD"] == 'GET' && $uri[1] == 'qr-generator') {
	$gen = $genObj->create()
            ->bankId("VCB") // BankId, bankname
            ->accountNo("111111")// Account number
            ->generate();
        echo $gen;
	echo nl2br(" \n Test API");
}

// request method is POST
if ($_SERVER["REQUEST_METHOD"] == 'POST' && $uri[1] == 'qr-generator') {
	// Get params input
	$input = (array) json_decode(file_get_contents('php://input'), TRUE);

	$bankId = $input['bankId'];
	$accountNo = $input['accountNo'];
	$amount = $input['amount'];
	$info = $input['memo'];
	$returnText = $input['returnText'];
	$isCard = $input['isCard'];

	// Generator
	$gen = $genObj->create()
				->bankId($bankId) // BankId, bankname
				->accountNo($accountNo)// Account number
				->amount($amount)// Money
				->info($info) // Ref
				->isCard($isCard) // isCard
				->returnText($returnText); // if true, return text. If false, return image in base64
			// $result = json_decode($gen->generate()); // Print text to generate QR Code
			// $response_data =  $result->data; // image in base64

	echo $gen->generate();		
	// $response['status_code_header'] = 'HTTP/1.1 200 OK';
	// $response['body'] = json_encode($response_data);

	// return $response;
}

