# WolfSellers Referral Module for Magento 2

## Description

The `WolfSellers_Referral` module is a referral program implementation for Magento 2. It allows customers to refer other customers by providing their email address and tracking the referral's status (pending or registered). This module provides the ability to create, manage, and delete referrals.

## Features

- **Referral Creation**: Customers can create referrals by providing their email, first name, last name, and phone number.
- **Referral List**: View all the referrals created by a customer, including their status and actions to edit or delete.
- **Referral Status**: Referrals can have two statuses: *pending* or *registered*. The status is determined based on whether the referred email belongs to a registered customer.
- **Referral Management**: Admin can manage, edit, and delete referrals via the backend.

## Requirements

- Magento 2.4.7 or later
- PHP 8.1 or higher

## Installation

### 1. Via Composer (Preferred Method)

1. Add the module's repository to your Magento project's `composer.json` file:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/hakumanamsaba/module-referral"
    }
]
```
2. Install the module via Composer:
```
composer require wolf-sellers/module-referral
```

### 2. Manual Installation
If you prefer to install manually, follow these steps:

1. Clone the repository or download the module.
2. Place the module-referral folder inside the modules/WF/ directory of your Magento 2 installation.
3. Run the following commands to register and enable the module:

```
php bin/magento setup:upgrade
php bin/magento module:enable WolfSellers_Referral
php bin/magento setup:di:compile
php bin/magento cache:flush
```

### Usage
1. Frontend
   Once installed and enabled, customers can create referrals via the "Add Referral" link in the header. The form will allow them to submit the required details for a new referral.

2. Admin Panel
   You can view, edit, or delete referrals directly from the Admin Panel:
   3. Go to Customers > Referrals. 
   4. View the referral details, including email, status, and actions to edit or delete.
   
### Configuration
There is no specific configuration needed for this module. However, ensure that the module is enabled and functioning by checking the Stores > Configuration section in the Magento Admin.

### License
This module is released under the MIT License.

### Contact
For further inquiries, please contact the module developer:
```
Abraham Ruiz: abrahamruiz.hakum@gmail.com
```
