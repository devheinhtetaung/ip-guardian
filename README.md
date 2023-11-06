# IP Guardian API

IP Guardian API is a powerful backend service designed to check user IPs and filter allowed countries for redirection. Its main purpose is to assist individuals who use URL shorteners like OuO and similar services to maximize their revenue by selectively redirecting users based on their geographical location.

## Introduction

IP Guardian API allows you to integrate IP filtering and redirection capabilities into your web applications or services. This API is designed to be easily integrated and customized according to your requirements.

## Features

- **IP Whitelisting:** Define a list of allowed IP addresses that should not be subject to redirection.
- **Country Filtering:** Redirect users based on their country to maximize earnings from high ECPM (Earnings Per Thousand Impressions) countries.
- **Customizable:** Configure your own rules and conditions for redirection.
- **Secure and Reliable:** Built with security in mind and designed for high reliability.
- **RESTful API:** Easy integration via RESTful API endpoints.

## Getting Started

To get started with the IP Guardian API, follow these steps:

1. Deploy the IP Guardian API to your server or hosting environment.
2. Configure the API settings according to your preferences.
3. Integrate the API with your web application or service by making HTTP requests to the API endpoints.

## API Actions

Here are the main groups of API actions, along with the specific requirements for each action.

### User Group

- **signup**
  - **Description:** Register a new user.
  - **Required Parameters:** `email` and `password` in the POST data field.

- **loginWithEmail**
  - **Description:** Log in with an email and password.
  - **Required Parameters:** `email` and `password` in the POST data field.

- **loginWithToken**
  - **Description:** Log in with a token.
  - **Required Parameters:** `token` in the POST data field.

### Link Group

- **create**
  - **Description:** Create a new link.
  - **Required Parameters:** `destinationLink` and `whitelistCountry` in the POST data field.

- **delete**
  - **Description:** Delete a link.
  - **Required Parameters:** `linkId` in the POST data field.

- **findOrfail**
  - **Description:** Find a link or fail if not found.
  - **Required Parameters:** `linkId` in the POST data field.

- **all**
  - **Description:** Retrieve multiple links with a limit.
  - **Required Parameters:** `limit` (an integer) in the POST data field.

- **validateIP**
  - **Description:** Validate an IP address for a specific link.
  - **Required Parameters:** `linkId` and `user's IP` in the POST data field.

### App Group

- **checkUpdate**
  - **Description:** Check for update version of the application.
  - **No additional required parameters.**

- **checkMaintenance**
  - **Description:** Check the application's maintenance status.
  - **No additional required parameters.**

- **AdInfo**
  - **Description:** Retrieve information about advertisements.
  - **No additional required parameters.**


## License

This project is open source and available under the [MIT License](LICENSE). Feel free to modify and adapt it to your needs.

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
