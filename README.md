# Ambientia_CollectorStrongAuthentication
## TL;DR
This module adds Strong authentication to Customweb_CollectorCw module.

More information about the authentication service is available on GitHub: https://github.com/collector-bank/security-authentication-samples

## Using saved SSN
The module is able to use a saved SSN, if the field is configured.
If you wish to save the SSN, please create a custom attribute for customer with attibute code "ssn".

## Installation
```
$ cd {magento_base_dir}
$ composer config repositories.collector-strong-authentication vcs git@github.com:ambientiaoy/magento2-collector-strong-authentication.git
$ composer require ambientia/collector-strong-authentication
$ bin/magento setup:upgrade
```
