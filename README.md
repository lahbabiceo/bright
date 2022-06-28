<p align="center">
    <img src="https://github.com/tariqsocial/brighty/blob/main/Logo.png?raw=true" height="100px">
</p>

<h1 align="center">Brighty - Web Hosting Automation, Billing and Provisioning Platform for the Sane Hosts</h1>


:wave: Hi There! We are creating a production-level "WHMCS Alternative" with integrated billing, automation and provisioning Platform for web hosters. This project aims to give you a usable and extendable platform with minimal but all required features to run a web design agency or a hosting company.


<div align="center">

 ![PULL REQUEST](https://img.shields.io/badge/contributions-welcome-green)  ![status](https://img.shields.io/badge/Status-Not%20Usable-red)   ![PULL REQUEST](https://img.shields.io/badge/license-MIT-blue) ![PHP Version Require](http://poser.pugx.org/phpunit/phpunit/require/php)  ![Laravel](https://img.shields.io/badge/framework-Laravel5-orange)   ![BoxBilling Size](https://img.shields.io/github/repo-size/iqltechnologies/brighty.svg?style=popout) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/iqltechnologies/brighty/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/iqltechnologies/brighty/?branch=main)    [![CodeFactor](https://www.codefactor.io/repository/github/iqltechnologies/brighty/badge)](https://www.codefactor.io/repository/github/iqltechnologies/brighty)

</div> 

## Every one is fed up of paid web hosting automation software!

So What do we do? The answer is to create something together. In our experience, current alternatives are not only outdated (and in code debt) but also needs to be built keeping in view the current needs of the industry. We want to create something which we will use ourselves in production.  

Not Limited to Hosting, it can be used for any kind of automated billing and provisioning business. 


## :date: Release Date for V1.0 (Tentative)

We are targeting to release it by 20 July 2022.

## Status: Not Usable. Just Started. We are in early development/ideation stage. 

Looking for Core group. 

### :handshake: Join Our Community

[Github Project View](https://github.com/users/tariq-abdullah/projects/1/views/1])  |   [Join Our Discord Server](https://discord.gg/dUCmJcs5xv)    |    [Join Telegram Group](https://t.me/+PyUnIpTv9i42ODJl)


## :bicyclist: Current Goals

- Integrate a login script - allows you to create a login popup and logged-in user bar on any site by interting a few lines of js code
- User Registration | Supported: Social Media, Mobile, Email, Email Verification, SMS Verification etc.
- User Dashboard / Client Panel


**Domain Name** :globe_with_meridians: :
  -   Automated Registration | Supported : Logicboxes
  -   Name Server Update
  -   Domain Contact Update
  -   Domain Name Purchase 

**Hosting** :desktop_computer:
  - CWP automated Provisioning & Suspension (Auto and Manual)
  - Hosting Package Purchase
  
**Invoice** :page_with_curl:
  - Create Invoice
  - Invoice Payment 
  - Automated Invoice Generation

**Payment Gateways** :credit_card:
  - Paypal
  - RazorPay
  - Skrill
  - Stripe

## :electric_plug: Pluggable Architecture

Using WordPress-Like Plugin Architecture allows you to develop plugins, search and activate them from the marketplace

## :trophy: Developing 

Fork and Use docker compose in this repo. Database and Code is included together. Pull Request are welcome

## Development Stacks:

- Laravel
- MySQL
- VueJs

# Requirements
  ## Frontend
    The frontend has no special requirement as it is static HTML 
  ## Backend
    PHP version 7.3 or newer with following extensions intalled: *intl*, *mbstring* php-json, php-mysqlnd, php-xml. A database is also required.

# Installation Instruction

  You can install frontend and backend separately (recommended) as well as together(easy). Follow these steps carefully

  ## Option 1 : One Click Installer

  - Download Brighty.zip and Upload level above public directory(generally public_html on cpanel and /var/www/html elsewhere; do not upload zip to public_html or html instead one level up eg. www). 
  - In your browser open your-url.tld/install and follow on-screen instruction

  ## Option 2 : Manual Install 

  - For latest development version download project as zip or download a stable release(Recommended)
  - Go to public directory of your hosting (generally public_html on cpanel and /var/www/html elsewhere) and upload the contents of public folder there.
  - edit brighty-config.json and place your brighty backend url there (with protocol and trailing slash) Frontend installation is complete.
  - to install backend upload everything including public directory one level above public. make sure contents of public directory are public and nothing else otherwise your system will be open to everyone for playing aka hacking and expliting.
  - if you want to keep your backend on the same domain/directory, move to next step otherwise if your backend is separated then remove everything other than index.php and .htaccess files from project public folder.
  - IMPORTANT : remove .htaccess and index.php from project root (/brighty folder) regardless of your previous step as it is there for helping us during development only
  - Login to your frontend with  

## Installation Note: 

  - there is no specific admin url. To access admin area you need to login as a normal urser with admin credentials

## :green_book: License

![MIT License](https://img.shields.io/badge/license-MIT-blue)

MIT License : Feel free to use it for as long as you want and any manner you want.
