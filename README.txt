=== Components ===
Contributors: Arnau Solsona
Donate link:
Tags: products, customize
Requires at least: 6.0.1
Tested up to: 6.0
Stable tag: 6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin to autogenerate a table listing all the components of a product inside a new tab called "Components".

== Description ==

This plugin is made for a specific page.

It creates a table into de database that saves the association between a product and it's components.

Then automatically generates a table listing all the components in a new tab called "Components" inside the product's front page.

There are 2 versions of the listing table:
    1) Every component in the list has an "Add to cart" button attatched to it to quickly add them into the cart without having to access the component's page.
    2) None of the components in the list has the "Add to cart" button.

If the client viewing the page has a role called "NoSale" it shows the listing table version 2).

Creates 5 new user roles.
New roles:
    1) Descuento10 -> 10% Discount
    2) Descuento15 -> 15% Discount
    3) Descuento20 -> 20% Discount
    4) Descuento25 -> 25% Discount
    5) Descuento30 -> 30% Discount

If the client making an order has any of the above roles the final price of the order gets its respective % discount.

== Installation ==

1. Upload `components` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= If i uninstall the plugin do i lose all the data inserted into the custom database table? =

Yes, uninstalling deletes the database table created by this plugin and all of it's entries.

= Do I have to insert every component as a product first? =

Yes, In order for this plugin to work you need to first create the product that then will be associated as a component to it's "parent" product.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* --

`<?php code(); // goes in backticks ?>`