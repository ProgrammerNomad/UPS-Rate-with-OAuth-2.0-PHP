<?php
/**
 * Requires libcurl
 */

//  Used to define the request type. 
// Valid Values:
// Rate = The server rates (The default Request option is Rate if a Request Option is not provided). 
// Shop = The server validates the shipment, and returns rates for all UPS products from the ShipFrom to the ShipTo addresses. 
// Ratetimeintransit = The server rates with transit time 
// informationShoptimeintransit = The server validates the shipment, and returns rates and transit times for all UPS products from the ShipFrom to the ShipTo addresses.
// Rate is the only valid request option for UPS Ground Freight Pricing requests.

const version = "v2205";
const requestoption = "Rate";
$query = array();

$curl = curl_init();

$payload = array(
  "RateRequest" => array(
    "Request" => array(
      "TransactionReference" => array(
        "CustomerContext" => "CustomerContext"
      )
    ),
    "Shipment" => array(
      "Shipper" => array(
        "Name" => "John",
        "ShipperNumber" => "K7W528",
        "Address" => array(
          "AddressLine" => array(
            "John Christensen",
            "1927 Lost Creek Drive",
            "",
          ),
          "City" => "Arlington",
          "StateProvinceCode" => "TX",
          "PostalCode" => "76006",
          "CountryCode" => "US"
        )
      ),
      "ShipTo" => array(
        "Name" => "ShipToName",
        "Address" => array(
          "AddressLine" => array(
            "John Christensen",
            "1927 Lost Creek Drive",
            "",
          ),
          "City" => "Arlington",
          "StateProvinceCode" => "TX",
          "PostalCode" => "76006",
          "CountryCode" => "US"
        )
      ),
      "ShipFrom" => array(
        "Name" => "ShipFromName",
        "Address" => array(
          "AddressLine" => array(
            "Jenny",
            "10986 Deer Valley Rd",
            "",
          ),
          "City" => "Yucaipa",
          "StateProvinceCode" => "CA",
          "PostalCode" => "92399",
          "CountryCode" => "US"
        )
      ),
      "PaymentDetails" => array(
        "ShipmentCharge" => array(
          "Type" => "01",
          "BillShipper" => array(
            "AccountNumber" => "K7W528"
          )
        )
      ),
      "Service" => array(
        "Code" => "03",
        "Description" => "Ground"
      ),
      "NumOfPieces" => "1",
      "Package" => array(
        "SimpleRate" => array(
          "Description" => "SimpleRateDescription",
          "Code" => "XS"
        ),
        "PackagingType" => array(
          "Code" => "02",
          "Description" => "Packaging"
        ),
        "Dimensions" => array(
          "UnitOfMeasurement" => array(
            "Code" => "IN",
            "Description" => "Inches"
          ),
          "Length" => "5",
          "Width" => "5",
          "Height" => "5"
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

curl_setopt_array($curl, [
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer eyJraWQiOiI2NGM0YjYyMC0yZmFhLTQzNTYtYjA0MS1mM2EwZjM2Y2MxZmEiLCJ0eXAiOiJKV1QiLCJhbGciOiJSUzM4NCJ9.eyJzdWIiOiJzYWxlc0BmYXN0bGlnaHRpbmdzdXBwbHkuY29tIiwiY2xpZW50aWQiOiJuTkF0NXVmdkFFQ1dJZWFyYWpjaTR3WWp3R3ZTUlJoUHc4eWI2bWJIZkNNcFd6emUiLCJpc3MiOiJodHRwczovL2FwaXMudXBzLmNvbSIsInV1aWQiOiIzQTNFNTAwMi1BRUQ2LTFGMTAtQjhERi03QzA0NTdGQUY4MDkiLCJzaWQiOiI2NGM0YjYyMC0yZmFhLTQzNTYtYjA0MS1mM2EwZjM2Y2MxZmEiLCJhdWQiOiJGTFMgVVBTIEFQSSIsImF0IjoiWkh2TDJkUjBZbUJQSTR1eWhoczN0SzRuVHB4aCIsIm5iZiI6MTczMDg4ODIwNiwic2NvcGUiOiJMb2NhdG9yV2lkZ2V0IiwiRGlzcGxheU5hbWUiOiJGTFMgVVBTIEFQSSIsImV4cCI6MTczMDkwMjYwNiwiaWF0IjoxNzMwODg4MjA2LCJqdGkiOiJhZDNmZDgyNC03YjkwLTRiM2YtOTJlYy1jZjY0MDc2MDEwYzYifQ.PWzu4s2XSlniWVKnU6QWjYt9nLGKNjus7nlzcUL8UdPBdydqkPb-0a-Y_eUX9d2R8BIunjDcnX7PAt_YmbNzGUZadXb-WbG_BNsTz-oeu9bNEvaPqFl-p4dY3kXZeCFXKzUGl8_8uGvyrJpFX6MIArYD1vtAilmxyF19nHY5fwnMhRnlizN-5ewaxUHgnxosCgiATm3zUEeq-EuNOXJKWlK0dV7Al1NWDQjwsAiBsXHPJsBhlDl5JnJhplUpnpITKEJ8mzWpKouZrUtqESeqSOryiJJ84OgpGWCELEO6RMFaKbgd0AHXcvLorfgoP82q-e4nC6cP9TFGVCkTV8ho5QW8SVo55djbm1cT2EZnrW_UKppNvZ_LnQRt0NbedDZRiOK8Zyb7dtFbh8CqbM2wM_n_ThDVO9acBFtQ2W-ufqlJUwMICiUltxTbTxElfHBnn7A9x-KIFiXdSV0BdV9f0rqRb8cs1Vr3LWBRyhfRgrF_j6p95mf5HPfPYJLn5IdWqeZpzxfEaW4fpIpRdT5OZx0esm-Od8DzrKx3RSdEQTyUjKfT-Ujmd0lj9Bo0RvnT2aRHH6-a86DQ-AlwpShmPXvlu-nB9vVVrCaRfVSDVI9oigmxuyX2HaNUbB2W2BaR2_zTitfKM9w1GDJKxH5zj-ERJ73_fBs3D6BGpfs4QGg",
    "Content-Type: application/json",
    "transId: string",
    "transactionSrc: testing"
  ],
  CURLOPT_POSTFIELDS => json_encode($payload),
  CURLOPT_URL => "https://wwwcie.ups.com/api/rating/" . version . "/" . requestoption . "?" . http_build_query($query),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
]);

$response = curl_exec($curl);

// print_r($response);

// die();

$error = curl_error($curl);

curl_close($curl);

if ($error) {
  echo "cURL Error #:" . $error;
} else {
  header('Content-Type: application/json');
  $phpResponseObject = json_decode($response);
  echo json_encode($phpResponseObject->RateResponse->RatedShipment->TotalCharges->MonetaryValue);
}
?>