# Simple OpenShift PHP Client

This client intends to provide a simplified wrapper for the rather verbose [universityofadelaide/openshift-restclient-php](https://github.com/universityofadelaide/openshift-restclient-php) generated by [Swagger codegen](https://github.com/swagger-api/swagger-codegen).

## Getting started

Require the client using composer:

```
composer require universityofadelaide/openshift-client dev-master
```

## Usage

Create an instance of the `Client` class by providing an OpenShift API URL and authentication token:

```php
use UniversityOfAdelaide\OpenShift\Client;

...

$client = new Client('https://192.168.64.2:8443/api/v1/', 'big_secret_token_hash', 'project');
```

## How to Test

Create a `test.php` or similar file in the root of the project. 

Add the following to this file:

```php
require_once __DIR__ . './../../autoload.php';

use UniversityOfAdelaide\OpenShift\Client;

$host = 'https://pathToOpenshift.host';
$token = 'yourOpenShiftToken';
$namespace = 'project';

// Get the arguments required
$client = new Client($host, $token, $namespace, TRUE);

// Attempt to create a secret.
$response = $client->createSecret('superSecret', ['username' => 'pied_piper', 'pass', 'middleout']);

```

## How to test with phpunit

```bash
 # From the /vendor/universityofadelaide/openshift-client directory
../vendor/bin/phpunit tests/ClientTest.php https://192.168.99.100:8443 $(oc whoami -t) myproject
```

## Todo

Implement everything.
