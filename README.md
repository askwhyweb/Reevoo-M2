
# Magento2 Module for Reevoo Reviews by OrviSoft Inc.

    `orvisoft/module-reevoo`

 - [Main Functionalities](#functionalities)
 - [Installation](#installation)
 - [Configuration](#configuration)
 - [Specifications](#specifications)


## Main Functionalities

This module serves the easy to use integration for Reevoo in Magento 2. Though this is not an official integration provided by Reevoo, but you can get its fully integration based on this module on fly.

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/OrviSoft/Reevoo`
 - Enable the module by running `php bin/magento module:enable OrviSoft_Reevoo`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### ~~Type 2: Composer~~ *(Temporarily not available)*

 - ~~Make the module available in a composer repository for example:~~
    - ~~private repository `repo.magento.com`~~
    - ~~public repository `packagist.org`~~
    - ~~public github repository as vcs~~
 - ~~Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`~~
 - ~~Install the module composer by running `composer require orvisoft/module-reevoo`~~
 - ~~enable the module by running `php bin/magento module:enable OrviSoft_Reevoo`~~
 - ~~apply database updates by running `php bin/magento setup:upgrade`\*~~
 - ~~Flush the cache by running `php bin/magento cache:flush`~~


## Configuration

 - Enable (revoo/option/enable)

 - Select Attribute (revoo/option/attribute)

 - Page Page Reviews (revoo/option/per_page)

 - Partner ID (revoo/option/partner_id)

 - Enable (revoo/feed_settings/enable)

 - Feed Path (revoo/feed_settings/feed_path)

 - FTP Host (revoo/feed_settings/ftp_host)

 - FTP User (revoo/feed_settings/ftp_user)

 - FTP Password (revoo/feed_settings/ftp_pass)

 - Freequency (revoo/cronjob/frequency)

 - Start Time (revoo/cronjob/time)


## Specifications

 - Cronjob
	- revoo-feed-job

 - Helper
	- OrviSoft\Reevoo\Helper\Productreviews

 - Block
	- Product\View\Reviews > product/view/reviews.phtml

 - Block
	- Product\Productlist\Reviews > product/list/reviews.phtml

 - Block
	- Js > javascript.phtml


## Adjustment in theme

This module generally comes up with little or no configuration requirements, however this module is very flexible to made necessory changes per your theme.

- For product view page, you can modify the file `layout/catalog_product_view.xml`

- For product list view page, you need to modify your `app/design/frontend/{YOUR-PACKAGE}/{YOUR_THEME}/Magento_Catalog/templates/product/list.phtml` and made necessory changes as below.

```php
// add helper somewhere on top of the file.
<?php $_reevoo_helper = $this->helper('OrviSoft\Reevoo\Helper\Productreviews'); ?>

// This will help you call the reviews within your product list loop.
<?php echo $_reevoo_helper->getReviewPerProduct($_product); ?>
```
