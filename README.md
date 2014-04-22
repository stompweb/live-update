Live Update
=====================

Adds the ability to live edit post & meta data.

![Overview](http://www.stomptheweb.co.uk/wp-content/uploads/2014/04/Screenshot-2014-04-22-10.46.05.png)

## Usage

* Activate Plugin
* Set up your fields
* Use it!


### Filters

In order to setup your fields you can use the lu_meta_box_array filter like this:

```php
function my_meta_box_array($meta_boxes) {

    $meta_boxes = array();

    $fields = array(
        array( 'type' => 'title', 'title' => 'Post Title', 'selector' => 'h1.entry-title'),
        array( 'type' => 'text', 'id' => 'my_post_meta', 'title' => 'Please update me', 'selector' => '.my-post_meta')
    );

    $meta_boxes[] = array(
        'post_types' => array('post'),
        'fields' => $fields
    );

    return $meta_boxes;

}

add_filter('lu_meta_box_array', 'my_meta_box_array');
```

## About

Adds the ability to manage content on the front end. You can pick a built in field, or a add your own. You can then update items using AJAX and if you choose a CSS selector then the page will live update.

The aim of the plugin is to be able to quickly update simple data in the front end. Currently complex data handling is out of scope until this has been achieved. Complex data handling should probably be done in the backend using a plugin such as [Custom Meta Boxes](https://github.com/humanmade/Custom-Meta-Boxes/)

## Contribute

If you find any bugs or have a suggestion for improving the plugin, please raise these in the [issues](https://github.com/stompweb/live-update/issues). If you can contribute to the code to make it more secure or efficient then please open a pull request.
