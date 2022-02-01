# jsksms Notifications Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ramanandapanda/jsksms.svg?style=flat-square)](https://packagist.org/packages/ramanandapanda/jsksms)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/ramanandapanda/jsksms/master.svg?style=flat-square)](https://travis-ci.org/ramanandapanda/jsksms)
[![StyleCI](https://styleci.io/repos/229822475/shield)](https://styleci.io/repos/:style_ci_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/ramanandapanda/jsksms.svg?style=flat-square)](https://scrutinizer-ci.com/g/ramanandapanda/jsksms)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/ramanandapanda/jsksms/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/ramanandapanda/jsksms/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/ramanandapanda/jsksms.svg?style=flat-square)](https://packagist.org/packages/ramanandapanda/jsksms)

This package makes it easy to send notifications using [jsksms](https://jsksms.com/) with Laravel 5.5+, 6.x, 7.x and 8.x

## Contents

- [Installation](#installation)
	- [Setting up the jsksms](#setting-up-the-jsksms-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
	- [ On-Demand Notifications](#on-demand-notifications)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install this package via composer:
``` bash
composer require ramanandapanda/jsksms
```

### Setting up the jsksms service

Add your jsksms token, default sender name (or phone number) to your config/services.php:

```php
// config/services.php
...
'jsksms' => [
    'endpoint' => env('jsksms_ENDPOINT', 'https://jsksms.com/api/v2/send'),
    'token' => env('jsksms_TOKEN', 'YOUR jsksms TOKEN HERE'),
    'sender' => env('jsksms_SENDER', 'YOUR jsksms SENDER HERE')
],
...
```

## Usage

You can use the channel in your via() method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\jsksms\JsksmsMessage;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return ["jsksms"];
    }

    public function tojsksms($notifiable)
    {
        return (new JsksmsMessage)->content("Your account was approved!");       
    }
}
```

In your notifiable model, make sure to include a routeNotificationForjsksms() method, which returns a phone number or an array of phone numbers.

```php
public function routeNotificationForjsksms()
{
    return $this->phone;
}
```
### On-Demand Notifications
Sometimes you may need to send a notification to someone who is not stored as a "user" of your application. Using the Notification::route method, you may specify ad-hoc notification routing information before sending the notification:

```php
Notification::route('jsksms', '5555555555')                      
            ->notify(new InvoicePaid($invoice));
```
### Available Message methods

`sender()`: Sets the sender's name. *Make sure to register the sender name at you jsksms dashboard.*

`content()`: Set a content of the notification message. This parameter should be no longer than 918 char(6 message parts),

`test()`: Send a test message to specific mobile number or not. This parameter should be boolean and default value is `true`.
## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please use the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Tint Naing Win](https://github.com/tintnaingwinn)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
