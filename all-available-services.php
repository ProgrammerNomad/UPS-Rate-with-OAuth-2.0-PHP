<?php

require 'vendor/autoload.php'; // Assuming you have Guzzle installed via Composer

use GuzzleHttp\Client;

$client_id = 'nNAt5ufvAECWIearajci4wYjwGvSRRhPw8yb6mbHfCMpWzze';
$client_secret = 'vU2OgaQdPM2NdNts8zQwcsdwRLRxws9oo8bU7DVlyPy2E7GcetKPBGAK7qcfHGhY';
$ups_account_number = 'K7W528';

// PACKAGE
$package_info = array(
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
    // ... (same as before)
}

function getShippingCost($accessToken, $shipper_info, $to_address_info, $from_address_info, $package_info) {
    $client = new Client();

    //  List of available service codes
    $serviceCodes = [
        '14', // UPS Next Day Air Early
        '01', // UPS Next Day Air
        '13', // UPS Next Day Air Saver
        '59', // UPS 2nd Day Air A.M.
        '02', // UPS 2nd Day Air
        '12', // UPS 3 Day Select
        '03', // UPS Ground
        '11', // UPS Standard
        '07', // UPS Worldwide Express
        '54', // UPS Worldwide Express Plus
        '08', // UPS Worldwide Expedited
        '65', // UPS Worldwide Saver
        '96', // UPS Worldwide Express Freight
        '82', // UPS Today Standard
        '83', // UPS Today Dedicated Courier
        '84', // UPS Today Intercity
        '85', // UPS Today Express
        '86', // UPS Today Express Saver
        '70'  // UPS Access Point Economy
    ];

    $rates = [];

    foreach ($serviceCodes as $serviceCode) {
        $payload = [
            "RateRequest" => [
                "Request" => [
                    "TransactionReference" => [
                        "CustomerContext" => "CustomerContext",
                        "TransactionIdentifier" => "TransactionIdentifier"
                    ]
                ],
                "Shipment" => [
                    "Shipper" => [
                        "Name" => $shipper_info['name'],
                        "ShipperNumber" => $shipper_info['account_number'],
                        "Address" => [ 
                            // ... (Shipper address remains the same)
                        ]
                    ],
                    "ShipTo" => [
                        "Name" => $to_address_info['name'],
                        "Address" => [
                            // ... (ShipTo address remains the same)
                        ]
                    ],
                    "ShipFrom" => [
                        "Name" => $from_address_info['name'],
                        "Address" => [
                            // ... (ShipFrom address remains the same)
                        ]
                    ],
                    "PaymentDetails" => [
                        // ... (Payment details remain the same)
                    ],
                    "Service" => [
                        "Code" => $serviceCode 
                    ],
                    "NumOfPieces" => "1",
                    "Package" => [
                        "PackagingType" => [
                            "Code" => $package_info['package_type']
                        ],
                        "Dimensions" => [
                            // ... (Dimensions remain the same)
                        ],
                        "PackageWeight" => [
                            // ... (Package weight remains the same)
                        ]
                    ]
                ]
            ]
        ];

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
            $serviceDescription = $data['RateResponse']['RatedShipment'][0]['Service']['Description']; // Get service description
            $totalCharges = $data['RateResponse']['RatedShipment'][0]['TotalCharges']['MonetaryValue'];
            $rates[$serviceDescription] = $totalCharges;

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            echo "Error getting rate for service code $serviceCode: " . $e->getMessage() . "\n";
            // Add more robust error handling here
        }
    }

    return $rates; 
}

// EXAMPLE OF HOW TO USE
$accessToken = getToken($client_id, $client_secret);
$shippingRates = getShippingCost($accessToken, $shipper_info, $to_address_info, $from_address_info, $package_info);

// Print the rates for each service
foreach ($shippingRates as $service => $rate) {
    echo "$service: $" . $rate . "\n";
}

?>