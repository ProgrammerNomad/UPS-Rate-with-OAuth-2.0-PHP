# UPS Rate Calculator with Guzzle

This PHP code demonstrates how to calculate UPS shipping rates using the UPS API and the Guzzle HTTP client. 

## Features

- Uses OAuth2 to authenticate with the UPS API.
- Retrieves an access token for API authorization.
- Constructs a rate request with package and address details.
- Fetches shipping rates from the UPS Rating API.
- Provides example usage with sample data.

## Requirements

- PHP 7.4 or higher
- Guzzle HTTP client (install via Composer: `composer require guzzlehttp/guzzle`)
- UPS API credentials (Client ID and Client Secret)

## Installation

1. Clone the repository: `git clone https://github.com/your-username/ups-rate-calculator.git`
2. Install dependencies: `composer install`
3. Configure your UPS API credentials:
   - **Securely store your Client ID and Client Secret.**  Consider using environment variables or a secure configuration file.
   - Update the `$client_id` and `$client_secret` variables in the code.

## Usage

1. **Update shipment details:**
   - Replace the example data in `$package_info`, `$shipper_info`, `$from_address_info`, and `$to_address_info` with your actual shipment information.
2. **Run the script:** `php ups_rate_calculator.php`
3. **View the output:** The script will print the total shipping charges to the console.

## Code Overview

- **`getToken()` function:** Retrieves an access token from the UPS OAuth2 server.
- **`getShippingCost()` function:** Constructs the rate request payload and fetches shipping rates from the UPS Rating API.
- **Example usage:** Demonstrates how to use the functions to get shipping rates.

## Important Notes

- **API Credentials:** Never hardcode your API credentials directly in the code. Use environment variables or a secure configuration mechanism.
- **Error Handling:** The code includes basic error handling. For a production environment, implement more robust error handling, such as logging and retry mechanisms.
- **UPS API Documentation:** Refer to the official UPS API documentation for detailed information about request parameters, response formats, and error codes: [https://developer.ups.com/](https://developer.ups.com/)

## Contributing

Contributions are welcome! Feel free to open issues or submit pull requests.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
