# Press Loft Affiliates - Magento 1.8 / 1.9 Extension

This project contains source code for the Magento 1 extension.  Please note that this extension assumes that you have a standard installation of Magento 1.8 or 1.9.

## Installation guide
1. Back up your store and database.
2. Download the extension files.
3. Copy the extension files to your Magento installation whilst maintaining the file structure of the extension.
4. If you are using a custom theme, it is recommended to copy the design files to your current theme's folders.
5. Flush the Magento cache and cache storage.
6. Ensure the new module (called `PressLoft_Affiliate`) is enabled.
7. Navigate to `System > Configuration` and click `Affiliate` in the left-hand menu (under the heading `PRESS LOFT`).
8. On the Affilate page ensure that the module is enabled and input your `Affilate ID`.
9. Save the configuration.

## Using your own theme
If you have your own custom theme install then we recommend copying all files in `app/design/frontend/base/default/` to the
`app/design/frontend/[your_package]/[your_theme]/` folder in your installation.

## Flushing the Magento cache
You can flush the cache by clicking the two buttons in the top right of the admin console under `System` > `Cache Maganement`.  Alternatively you can flush the cache from the command line by running the following command.
 > `sudo rm -Rf var/{cache,session}/*`
