# Next Gen MyWork

[![Build Status](https://travis-ci.com/pfizer/next-gen-mywork.svg?token=hHoL4mb4zze8HRfycwpV&branch=mainline)](https://travis-ci.com/pfizer/next-gen-mywork)

-   [Overview](#overview)
-   [Setup Local](#setup)
-   [Pusher (must read)](pusher)
-   [Deploy](#deploy)
-   [Testing](#testing)

## Overview

@stackbuilders
Passing test will go to https://testbuild-staging.digitalpfizer.com/

You can see more about it here https://platform-cat.digitalpfizer.com/home?stack_id=76

### API Token

You can see API example in `tests/Feature/BaseInstallTest.php` `testApiAndToken`

This will make a token and you can see how Vue can use this in `resources/assets/js/components/AuthExample.vue`

### Loading Component

see `resources/views/testing.blade.php` for the `loading` component un-comment it out and see it in action
use it as needed to set loading message

### Testing Page

`/testing` work on your components outside of the app here. Just fix the route as needed in web.php and
load what ever the heck you need.

Try the one wrapping in `testing` component you will see how I set vuex as well

### Vuex

You can see docs [here](https://vuex.vuejs.org/guide/state.html) and training videos [here](https://laracasts.com/series/whatcha-working-on/episodes/21)

See an example of it being set in Testing.vue and uncomment it in the testing.blade.php to watch it work

### Bootstrap and Vue

We have tons of options [https://bootstrap-vue.js.org/](https://bootstrap-vue.js.org/)
Tables, Forms buttons etc. This will reduce your code quite a bit

### Notifications

### Pagination and Parameters

<a name="setup"></a>

## Setup Local

Copy the .env.example file to .env

Make sure you have a database with the name `APP_NAME`

The local dev needs to run on https

The local dev url will be `https://APP_NAME.test`

See docs [here](https://laravel.com/docs/5.6/valet)
This is a great way to get going

For js just run `npm install` and while working run `npm run watch`

## Pusher

If pusher is not setup then you will get a silent error on the deployed site when trying to load VueJS
To setup pusher you need to get credentials from Alfred Nutile.
Then those are added to CAT Platform as ENV settings

```
PUSHER_APP_ID="593697"
PUSHER_APP_KEY="fff"
PUSHER_APP_SECRET="fff"
PUSHER_APP_CLUSTER="us2"
MIX_PUSHER_APP_KEY="fff"
MIX_PUSHER_APP_CLUSTER="us2"
```

The MIX stuff does not work really since our build happens in a way that gets in the way of that.
You can get a set to run locally as well.

## Deploy

Push to github and it will deploy once the tests pass

### StackBuilder note

quick setup of the app so it has all the needed files `php artisan cat:setup_deployment`
this will get the git repo setup travis files but will not build the stack etc

## Testing

`/vendor/bin/phpunit`

## Coding Styles

`composer fix-styles`

and

`composer check-styles`

....
