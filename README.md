# Base Theme

This simple theme has everything you need to run a WP site with [Bootstrap](http://getbootstrap.com/) and [Font Awesome](http://fortawesome.github.io/Font-Awesome/).

The idea is to keep everything simple and familiar so that it can be understood by any wp developer.

It is based on [Bones Theme](https://github.com/eddiemachado/bones) taking some functionality out of [Roots](https://roots.io/), all template files and contents are as they should on a standard wordpress theme.

## Classes

All the php files inside the `lib/classes` are auto loaded and each of them contains separate functionality of the theme: activation, bootstrap, cleanup, piklist and views. These files are classes similar to the [WordPress Plugin Boilerplate](https://github.com/theantichris/WordPress-Plugin-Boilerplate) so that makes them portable and also replaceable even by a child theme.

## LESS

The main less file is `lib/assets/less/jutzu.less` and its outputted in `lib/assets/dist/jutzu.css`.

## JavaScript

There is no much included by default, except for some function to add css classes to the tag cloud and of course bootstrap scripts. 

Main minifed file is `lib/assets/dist/jutzu.js`.

## Grunt

This part is not necessary to make the theme work properly, but it does save you some time.

Fist on terminal go to `lib/assets/` then:

    npm install
    grunt dev

This task will create a minified js of all the files inside `lib/assets/js` and compile the main less file if a change is present, it will also reload your browser window 0..o

You can also install extra dependencies with bower for example

    bower install wow

Then add the path to the script on the gruntfile, and run `grunt build`.

## Child Themes

This theme is prepared to work with a child theme, you only need to create the folder structure to get the same functionality, an example child theme will be uploaded soon.

## Translations

Most of the strings present where taken from Bones Theme other string are unique to this project and not translated, feel free to help. 

Current Languages: Spanish, French, Portuguese, Italian, Dutch, Swedish and German. 
