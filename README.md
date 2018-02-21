# Jumpstart is a Wordpress Theme Boilerplate with a view...

I created Jumpstart 6 years ago and have been using, updating, improving it ever since.

To me, it takes a better approach of the "view" in the line of modern MVC including a better `get_template_part()` which solves the scope issue. 
Plus it bundles a lightweight API, an SEO logic you can tailor to your needs, an easy way to register and enqueue your scripts and many helper functions.

## Setup:
1. Download to themes directory.
2. `cd wherever/it/is`
3. `npm install`
4. `grunt`
5. Modify style.css for Wordpress theme directory (don't forget to credit)
5. Activate theme.
6. Start building on it.

## A more modern aproach of the view

I got tired of having every single wordpress view/template files at the root of my folder. I wanted one layout and view files organized like a modern MVC.

The index.php and the page template files (the ones starting with this one liner relic `/* Template Name: Contact */`) are the only ones who should need the `new jsTemplate()` in it.
For all the others, index.php's jsTemplate will do the guessing as long as you follow the `views/` file hierarchy:
(Note that at the root of view directory sits your layouts, default is layout.php)

```
+-- layout.php
+-- modal.php
+-- includes (see Includes and partials below)
|  +-- socials.php
|  +-- well.php
+-- default
|  +-- 404.php (404 cannot depend on a post type)
|  +-- archive.php
|  +-- single.php
+-- post
|  +-- archive.php
|  +-- single.php
+-- page
|  +-- single.php
|  +-- templates
|     +-- contact.php
+-- recipe (custom post type)
|  +-- archive.php
|  +-- single.php
```

### Post types' views
If you use a prefix in your custom post type handle (as you should) it should be the one defined as _THEME_SHORTNAME_ so we can safely ommit it in its dirname detection)
A post type without a view directory will default to the `default/` directory and its views.

### Layouts
Default layout is at the root (layout.php). To use another layout, you need to change jsTemplate() second param as explained below.

### Custom template page
Your page template files should be stored in /page-templates and bear the same name as their view files to benefit from Jumpstart's view logic.

#### If 
1/ You **don't** want to bother with this page template directory and naming logic you can still create them wherever and pass the slug of the view file (whithin `views/`) as first parameter:

```php
<?php 
/* Template Name: Contact */ 
new jsTemplate('here/there/contact');
```

2/ You need to use another layout than the default one, for exemple for a modal view of a post or whatever. Layout is the second parameter.
```php
<?php 
new jsTemplate(false, 'modal');
```

3/ You need a different layout and a view not relevant to post type logic:
```php
<?php 
new jsTemplate('error_landing', 'modal');
```

### Includes and partials

#### The problem
Wordpress' `get_template_part()` is great to safely include pieces of your theme without risking a fatal error (typo in name or path) but it's a function with its own scope. 
It can use the main wordpress variables ($post, $wp_query etc...) but doesn't care for your own.
This makes us use a lot of global declaration before a our `get_template_part()`. Declaration we have to remember to reset afterward to avoid variable conflicts.

#### Jumpstart's `get_template_include()`
Jumpstart uses a function called get_template_include() which allows to pass variables as parameter to be used from the included part.

The function is in functions.php and uses the jsPartial class. It uses the same global declarations as WP core's `get_template_part()`: 
`global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;`

So feel free to use those the usual way.

For your own variables (passed as parameter, you call them by using `$this->say('whatever')` for echo, or `$this->get('whatever')` for retrieving.

#### Exemple
This is from views/page/templates/home.php, can see it on the homepage.
```php
<?php get_template_include('well', ['class'=>'phi-Well--alt']) ?>
```

This is from views/includes/well.php
```html
<div class="phi-Well <?php $this->say('class'); ?>">
	Well, well, well...
</div>
```

## src/
src files processing is using [phil--grunt](https://github.com/regisphilibert/phil--grunt).
It drops everything in a dist/ directory. You can check out the Gruntfile.js for more information.

### SCSS
It is built for scss so grunt-contrib-sass is included on package.json

### Less
Why not. The grunt code is commented in Gruntfile.js but ready to use. All you have to do is 
1. `npm install grunt-contrib-less --save-dev`
2. `npm uninstall grunt-contrib-sass --save-dev` would make sense.
2. Comment/Uncomment Gruntfiles scss/less lines.
3. Create `src/less` directory and add your less files in it!

## Helpers
Everything is block commented here: `inc/helpers.php`

## Localization
You can read more on [WordPress l18n practises](https://codex.wordpress.org/I18n_for_WordPress_Developers) but basically you need to:
1. Create a `.pot` file to use as a template for your alternate languages.
2. Create a `.po` file (from copying/pasting content fo the `.pot` ) for each languages.
3. Generate a `.mo` file for each .po file.

The default text domain will be your global THEME_SHORTNAME. 
Default language dir where `.pot`, `.po` and `.mo` are stored is /languages. 
Change to your linking in [functions.php](https://github.com/regisphilibert/wp-jumpstart/blob/master/functions.php#L11) and [Gruntfile.js](https://github.com/regisphilibert/wp-jumpstart/blob/master/Gruntfile.js#55).

Jumpstart only has one [localized chain](https://github.com/regisphilibert/wp-jumpstart/blob/master/views/includes/header.php#4). 

### Step 1
We use [grunt-wp-i18n](https://github.com/cedaro/grunt-wp-i18n)'s [makepot](https://github.com/cedaro/grunt-wp-i18n/blob/develop/docs/makepot.md) to generate the `.pot` file in the language directory:

```
$ grunt pot
```

### Step 2
Create the `.po` files for each languages and update their `msgstr` strings with appropriate translations. Name your files with the approriate local code (use [get_locale()](https://codex.wordpress.org/Function_Reference/get_locale) to make sure you're using the right code.

### Step 3
We compile the `.mo` file for each languages.

```
$ grunt mo
```

Changes should reflect.

## Bundles

Phil has 3 self dependent optionnal bundles.
Simply look at the functions.php defined constants to choose your needed bundles.
Or just copy the files of any bundle and drop them in your existing Wordpress theme! They don't need Jumpstart.

### Bundle::API
If you just need a few endpoints to output something for your javascript or tier service, this is a great time saver. If you need a deep REST API to output your posts you should turn to [WP REST API](http://v2.wp-api.org/)

This bundle adds an API page in your dashboard which will allow you to play with your methods and see their output.

To add methods > bundles/api/Api.class.php
To add methods to the WP API admin page > bundles/api/Page.class.php (see constructor)

__This is a lightweight API__, use it if you're not fighting Fancy Bear or any famous hackers.
Its only security wall is a unique key you must define as API_KEY either in your wp-config.php or in bundles/api/Api.class.php (preferable)
The key must then be inserted in the endpoints urls

`http://yoursite.wp/api/{your_unique_key}/that_function`

Params can be added with url parameters (`$_GET`)

__IMPORTANT__: The unique key is added as a data-attr in the WP API Admin page. Which means that the key is exposed to any "inspector aware" wordpress user with the manage_option ability.
Not trusting those? You can:
* Change the protected $user_ability in `bundles/api/Page.class.php`
* Disable the WP API page by setting API_PAGE to 0 in `bundles/api/_.php`

### Bundle::Options

This bundle allow easy creation of an option page with ACF Pro. It justs create the page, so you can add a field group to it with ACF. Nothing fancy.

### Bundle::SEO

This is not really a bundle, but a class base to be extended or not as explained below.

#### raw SEO
If all you need is basic SEO tags (og, twitter card, basic metas) which use wordpress logic to assign values: blog's description as description meta, post_title as title, post thumbnail as image, post_excerpt as description etc... You can use it raw.

You will just need to add this to your theme's functions.php :
```php
add_action('template_redirect', 'theme_start_seo', 50);

function theme_start_seo(){
    new SEO;
}
```
#### Tailored SEO 
But you will most likely need to enrich those basic metas and values with the particularities of your own theme. (custom post, custom fields as meta values etc...)
To do that, you will have to create a another class (to be stored wherever) which extends this one. You can check inc/custom-seo.php for a use case.

Inside your custom-seo.php, you'll be able to overide the values for existings metas, and add your own metas tags.
