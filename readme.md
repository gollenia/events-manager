# Events

This Wordpress plugin manages events. It supports booking (including online and offline payments) and recurring events. It's originally based on the [WP Events Manager](https://wp-events-plugin.com/), but changes slowly.

### Changes from the original plugin

As the WP Events Manager seems to stagnate in development, we started to modernize it. This process ist yet not finished. So far, we made these Changes (which are still on progress):

-   Block support for Gutenberg, which makes the old Page templates obsolete
-   React based booking system (also includable as block)
-   Removed a lot of stuff (multisite support, google maps, buddypress, event submission)
-   cleaning up code (greetings to you, uncle Bob!)
-   offline payment now supports QR-Codes
-   generate a PDF with a monthly event list

Also, we plan these features:

-   Rearrange the classes into namespaces
-   remove jQuery completely
-   recode the form editor (with Gutenberg blocks)

### Installation

> :warning: **The plugins ist not listed in Wordpress' plugin archive** since there are some extra steps to make it work. This may or may not change in the future, depending on further development and usage.

To make the plugin work, you need composer. You can either clone the plugin into your plugin directory, cd into it and run `composer install`. Or use GitHub's actions (see examples in this project's `.github` folder)

If you start developping, you need npm.

-   `npm install` Install the required packages
-   `npm run build` Compile a compressed version for production
-   `npm start` Compile an uncompressed version and watch for changes
    With these commands, ALL Javascript and CSS files are generated and put into `./includes`

### Versions

#### 6.7

-   Bookings are now processed through a REST API
-   Bookings can be edited in the backend
-   Ticket Editor is included in the Block Editor

#### 6.6

-   Details-Block added
-   Updated all Blocks to API 2.0

#### 6.5

-   Form Editor completely recoded into Gutenberg
-   Refactoring and redesigning of the booking frontend
-   More cleanup work

#### 6.4

-   removed all old layouting options
-   booking app rewritten for better user experience and performance

#### 6.3

-   PDF Generator added
-   New List view for events
-   Whole event is now displayed with block, no layout used anymore

#### 6.2

-   Over 200 unused options removed

For previous versions see the original authors blog: https://wp-events-plugin.com/blog/
