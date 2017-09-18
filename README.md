[![Latest Stable Version](https://img.shields.io/packagist/v/deanblackborough/php-quill-renderer.svg?style=flat-square)](https://packagist.org/packages/deanblackborough/php-quill-renderer)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/Dlayer/dlayer/blob/master/LICENSE)
[![Minimum PHP Version](https://img.shields.io/badge/php-7.1-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/deanblackborough/php-quill-renderer.svg?branch=master)](https://travis-ci.org/deanblackborough/php-quill-renderer)

# PHP Quill Renderer

*Render quill insert deltas to HTML and soon Markdown.*

## Description

Quill deltas renderer, converts deltas to HTML, the Quill attributes supported are listed in a table below.
I'm working on support for the remaining attributes and additional parsers (markdown etc.)

## PHP 5.6

Use version 1.01.1 if you need PHP 5.6 support.

## Installation
 
The easiest way to use the renderer is with composer. ```composer require deanblackborough/php-quill-renderer```, 
alternatively include the classes in src/ in your library.
 
## Usage
```
try {
    $quill = new \DBlackborough\Quill\Render($deltas, 'HTML');
    echo $quill->render();
} catch (\Exception $e) {
    echo $e->getMessage();
}
```

## Usage, direct, parse and then render
```
$parser = new \DBlackborough\Quill\Parser\Html();
$parser->load($deltas);
$parser->parse();

$renderer = new \DBlackborough\Quill\Renderer\Html($parser->content());
echo $renderer->render();
```

## Quill attributes

Attribute | Support
--- | --- 
Bold | Yes
Italic | Yes
Link | Yes
Strike | Yes
Script:Sub | Yes
Script:Super | Yes
Underline | Yes
Header | Yes
Image | Yes
List | Yes
Indent/Outdent | Not yet
Text direction | Not yet
Color | Not yet
Font | Not yet
Text align | Not yet
Block quote | Not yet
Code block | Not yet

Attribute | HTML Tag
--- | --- 
Bold | `<strong>`
Italic | `<em>`
Link | `<a>`
Strike | `<s>`
Script:Sub | `<sub>`
Script:Super | `<sup>`
Underline | `<u>`
Header | `<h[n]>`
Image | `<img>`
List | `<ul>` `<ol>`

## Credits

carlos https://github.com/sald19 [Bugfix] v1.01.0

## Warnings

### Image support

The image support is rudimentary; it isn't production ready, some work needs to be done to support 
images. I can think of two solutions, pre-save of deltas, post the base64 and return a URI to replace the 
base64, or, at render time, fetch/cache an image/URI by posting the base64, later down the line I may 
explore one of these options.

Why? 

I'm using this package within Dlayer, my app has its own image handling and I will not be exposing 
the image functionality of Quill.
