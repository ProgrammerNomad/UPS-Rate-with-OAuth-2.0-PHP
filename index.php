<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$client_id = 'nNAt5ufvAECWIearajci4wYjwGvSRRhPw8yb6mbHfCMpWzze';
$client_secret = 'vU2OgaQdPM2NdNts8zQwcsdwRLRxws9oo8bU7DVlyPy2E7GcetKPBGAK7qcfHGhY';
$ups_account_number = 'K7W528';


// ***** SHIPPING SERVICE AVAILABLE OPTIONS *****
// Domestic     
// 14 = UPS Next Day Air Early
// 01 = UPS Next Day Air
// 13 = UPS Next Day Air Saver
// 59 = UPS 2nd Day Air A.M.
// 02 = UPS 2nd Day Air
// 12 = UPS 3 Day Select
// 03 = UPS Ground
// International    
// 11 = UPS Standard
// 07 = UPS Worldwide Express
// 54 = UPS Worldwide Express Plus
// 08 = UPS Worldwide Expedited
// 65 = UPS Worldwide Saver
// 96 = UPS Worldwide Express Freight
// 82 = UPS Today Standard
// 83 = UPS Today Dedicated Courier
// 84 = UPS Today Intercity
// 85 = UPS Today Express
// 86 = UPS Today Express Saver
// 70 = UPS Access Point Economy

// ***** PACKAGE TYPE AVAILABLE OPTIONS *****
// 01 = Bag, 
// 02 = Box, 
// 03 = Carton/Piece, 
// 04 = Crate, 
// 05 = Drum, 
// 06 = Pallet/Skid, 
// 07 = Roll, 
// 08 = Tube, 

// PACKAGE
$package_info = array(
    'service' => '02',
    'package_type' => '02',
    'Weight' => '1',
    'length' => '7',
    'width' => '4',
    'height' => '2',
);

// SHIPPER
$shipper_info = array(
    'account_number' => $ups_account_number,
    'name' => 'Mr. President',
    'address1' => '1600 Pennsylvania Avenue NW',
    'address2' => '',
    'address3' => '',
    'city' => 'Washington',
    'state' => 'DC',
    'zip' => '20500',
    'country' => 'us',
);

// FROM ADDRESS
$from_address_info = array(
    'name' => 'Mr. President',
    'address1' => '1600 Pennsylvania Avenue NW',
    'address2' => '',
    'address3' => '',
    'city' => 'Washington',
    'state' => 'DC',
    'zip' => '20500',
    'country' => 'us',
);

// TO ADDRESS
$to_address_info = array(
    'name' => 'Thomas Jefferson',
    'address1' => '931 Thomas Jefferson Parkway',
    'address2' => '',
    'address3' => '',
    'city' => 'Charlottesville',
    'state' => 'VA',
    'zip' => '22902',
    'country' => 'US',
);



function getToken($client_id, $client_secret) {
    $client = new Client();

    try {
        $response = $client->post('https://onlinetools.ups.com/security/v1/oauth/token', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'x-merchant-id' => 'string', // You might need to adjust this
                'Authorization' => 'Basic ' . base64_encode($client_id . ':' . $client_secret)
            ],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['access_token'];

    } catch (\GuzzleHttp\Exception\RequestException $e) {
        echo "Error getting access token: " . $e->getMessage() . "\n";
        // Add more robust error handling here (e.g., logging)
        exit;
    }
}

function getShippingCost($accessToken, $shipper_info, $to_address_info, $from_address_info, $package_info) {
    $client = new Client();

    $payload = array(
        "RateRequest" => array(
            "Request" => array(
                "TransactionReference" => array(
                    "CustomerContext" => "CustomerContext",
                    "TransactionIdentifier" => "TransactionIdentifier"
                )
            ),
            "Shipment" => array(
                "Shipper" => array(
                    "Name" => $shipper_info['name'],
                    "ShipperNumber" => $shipper_info['account_number'],
                    "Address" => array(
                        "AddressLine" => array(
                            $shipper_info['address1'],
                            $shipper_info['address2'],
                            $shipper_info['address3']
                        ),
                        "City" => $shipper_info['city'],
                        "StateProvinceCode" => $shipper_info['state'],
                        "PostalCode" => $shipper_info['zip'],
                        "CountryCode" => $shipper_info['country']
                    )
                ),
                "ShipTo" => array(
                    "Name" => $to_address_info['name'],
                    "Address" => array(
                        "AddressLine" => array(
                            $to_address_info['address1'],
                            $to_address_info['address1'],
                            $to_address_info['address1']
                        ),
                        "City" => $to_address_info['city'],
                        "StateProvinceCode" => $to_address_info['state'],
                        "PostalCode" => $to_address_info['zip'],
                        "CountryCode" => $to_address_info['country']
                    )
                ),
                "ShipFrom" => array(
                    "Name" => $from_address_info['name'],
                    "Address" => array(
                        "AddressLine" => array(
                            $from_address_info['address1'],
                            $from_address_info['address2'],
                            $from_address_info['address3']
                        ),
                        "City" => $from_address_info['city'],
                        "StateProvinceCode" => $from_address_info['state'],
                        "PostalCode" => $from_address_info['zip'],
                        "CountryCode" => $from_address_info['country']
                    )
                ),
                "PaymentDetails" => array(
                    "ShipmentCharge" => array(
                        "Type" => "01",
                        "BillShipper" => array(
                            "AccountNumber" => $shipper_info['account_number']
                        )
                    )
                ),
                "Service" => array(
                    "Code" => $package_info['service'],
                    "Description" => "ground"
                ),
                "NumOfPieces" => "1",
                "Package" => array(
                    "PackagingType" => array(
                        "Code" => $package_info['package_type'],
                        "Description" => "Packaging"
                    ),
                    "Dimensions" => array(
                        "UnitOfMeasurement" => array(
                            "Code" => "IN",
                            "Description" => "Inches"
                        ),
                        "Length" => "7",
                        "Width" => "4",
                        "Height" => "2"
                    ),
                    "PackageWeight" => array(
                        "UnitOfMeasurement" => array(
                            "Code" => "LBS",
                            "Description" => "Pounds"
                        ),
                        "Weight" => "1"
                    )
                )
            )
        )
    );


    try {
        $response = $client->post('https://onlinetools.ups.com/api/rating/v1/Rate', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
                'transId' => 'string', // Replace with your transaction ID
                'transactionSrc' => 'testing' 
            ],
            'json' => $payload 
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['RateResponse']['RatedShipment']['TotalCharges']['MonetaryValue'];
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        echo "Error getting shipping cost: " . $e->getMessage() . "\n";
        // Add more robust error handling here (e.g., logging)
        exit;
    }
}

// EXAMPLE OF HOW TO USE
// Get Token
$accessToken = getToken($client_id, $client_secret);
// Use API to get price
$totalCharges = getShippingCost($accessToken, $shipper_info, $to_address_info, $from_address_info, $package_info);
// Show Price
echo 'Total Charges: $' . $totalCharges; 

?>