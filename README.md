# Phil, a Wordpress Boilerplate

This is a theme boilerplate loaded with some interesting tools.

## Setup:
1. Download to themes directory.
2. `cd wherever/it/is`
3. `npm install`
4. `grunt`
5. Activate theme.
6. Start building on it.

## src/
Most of the sources are processed with grunt and dropped in a dist/ directory.
It is based on phil--grunt (minus pugjs) and uses its src/ file hierarchy. 

## Templating/Views

class phiTemplate deals with the view.

The index.php and the page custom template files (the ones starting with this one liner relic `/* Template Name: Contact */`) are the only ones who should need the `new phiTemplate()` in it.
For all the others, index.php's phiTemplate will do the guessing as long as you follow the views/ file hierarchy:

```
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

If you use a prefix in your custom post type handle (as you should) it should be the one defined as _THEME_SHORTNAME_ so we can safely ommit it in its dirname)

Your custom page template files should be stored in /page-templates and bear the same name as their view files. 
If you **don't** want to bother with this page template directory and naming logic you can still create them wherever and pass the name of the view file as parameter :

```php
<?php 
/* Template Name: Contact */ 
new phiTemplate('contact');
```

## Includes and partials
Wordpress' get_template_part() is great to safely include pieces of your theme without risking a fatal error (typo in name or path) but it's a function in itself and prevent you from using variables declared before the get_template_part() call.
This makes us use a lot of global variable declaration before an include which we have to remember to reset afterward.

Phil uses a function called get_template_include() which allows to pass variables as parameter to be used from the included part.
Inside your partial file you can then call those values by using `$this->say('whatever')` for echo, or `$this->get('whatever')` for retrieving.

This is from views/page/templates/home.php
```php
<?php get_template_include('well', ['class'=>'phi-Well--alt']) ?>
```

This is from views/includes/well.php
```html
<div class="phi-Well <?php $this->say('class'); ?>">
	Well, well, well...
</div>
```

## Bundles

Phil has 3 optionnal bundles.
Simply look at the functions.php defined constants to choose your needed bundles

### Bundle::API
If you just need a few endpoints to output something for your javascript or tier service, this is a great time saver. If you need a deep REST API to output your posts, you turn to [WP REST API](http://v2.wp-api.org/)
By activating this bundle it adds an API page in your dashboard which will allow you to play with your methods and see their output.

To add methods > bundles/api/Api.class.php
To add methods to the WP API admin page > bundles/api/Page.class.php (see constructor)

__This is a lightweight API__, use it if you're not fighting Fancy Bear or any famous hackers.
Its only security wall is a unique key you must define as API_KEY either in your wp-config.php or in bundles/api/Api.class.php (preferable)
The key must then be inserted in endpoint url

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
