# Deprecated in favor of [Maketador](https://github.com/sigami/maketador)

# Base Theme

It is a combination of the best bootstrap starter themes, with some extra flavor.

Tested with WordPress Theme Unit Test everything works, galleries, comments, pingbacks, calendar widget, post formats, attachments, archive pages etc.

Supports hentry microformats and schema.org, tested with the google rich snippets tools.

Supports [Bootstrap](http://getbootstrap.com/) and [Font Awesome](http://fortawesome.github.io/Font-Awesome/).

The idea is to **keep everything simple and familiar** so that it can be understood by any wp developer.

It was originaly based on wp-boostrap by 320press and still have some of its functions.

## Mini Plugins

All the php files inside the `lib/classes` are autoloaded. 

Each of them contains separate functionality: activation, bootstrap, cleanup, piklist and views. 

These files are portable and repleaceable, think on them like mini plugins. 

If you delete one you only loose that file functionality, without having to make any other changes to the code. 

They can also be replaced on a child theme.

## LESS

The main less file 

        lib/assets/less/jutzu.less

Outputted in 

        lib/assets/dist/jutzu.css

## JavaScript

Source is in 

        lib/assets/js/scripts.js

Outputted in

        lib/assets/dist/jutzu.js

## Child Themes

This theme is prepared to work with a child theme, you only need to create the folder structure to get the same functionality. [Example Child Theme](https://github.com/sigami/base_child)

## Translations

Most of the strings present where taken from wp-bootstrap from 320press other string are unique to this project and not translated, feel free to help. 

Current Languages: Spanish, French, Portuguese, Italian, Dutch, Swedish and German. 

## Grunt

This part is not necessary to make the theme work properly, but it does save you a lot of time.

Fist on terminal go to `lib/assets/` then:

    npm install
    grunt dev

This task will create a minified js of all the files inside `lib/assets/js` and compile the main less file if a change is present, it will also reload your browser window 0..o

You can also install extra dependencies with bower for example

    bower install wow

Then add the script path on the .gruntfile, and run `grunt build`.


