## Synopsis

This is a basic PHP Analytics class wich can be use to send basic google analytics tracking from PHP.

## Code Example

Here is some examples how to use it :

```
<?php

$analytics = new Analytics();

// send basic pageview
$analytics->send();

// send event specifying that user sign up from email form
$analytics->send('event', [
    'category' => 'signup',
    'action'   => 'confirmation',
    'label'    => 'Signup with email',
]);
```

## Motivation

I needed to send specific events for a project and it was easier to do it from my PHP code. I know that it can get better and lot of parameters can be added, and it will!

## Installation

Just download the file, place it where you want in your project.

## Documentation

This class only expose one method : `send()`. This function can take two parameters :

- `type` (string) :  event hit type
- `params` (array) : parameters

You need to set constant `GA_PROPERTY` (containing your identifier, like `UA-XXXXXXXX-#`) in your code before calling this method, or add it with the parameter `trackingId`.

It throws `AnalyticsException` if tracking id is not set or if one parameter given doesn't exists.

Version of analytics can be set like this :

```
$analytics = new Analytics($version);
```

## Tests

There is no tests yet (I only tested it manually for now).

## Contributors

Feel free to contribute (PR, issues, comments, ...) as it still miss others analytics providers, lot of parameters and maybe features.
To see the list of all parameters for google analytics collect calls, you can [check this sheet cheat](https://www.cheatography.com/dmpg-tom/cheat-sheets/google-universal-analytics-url-collect-parameters/) from dmpg_tom.
