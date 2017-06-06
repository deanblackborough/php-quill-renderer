[![Latest Stable Version](https://img.shields.io/packagist/v/deanblackborough/php-quill-renderer.svg?style=flat-square)](https://packagist.org/packages/deanblackborough/php-quill-renderer)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/Dlayer/dlayer/blob/master/LICENSE)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/deanblackborough/php-quill-renderer.svg?branch=master)](https://travis-ci.org/deanblackborough/php-quill-renderer)

# PHP Quill Renderer

*Render quill insert deltas to HTML.*

## Description

Simple Quill delta insert renderer, currently it only supports the attributes listed below, I will add support for additional attributes as I need them in Dlayer.

Created for use in [Dlayer](https://github.com/Dlayer/dlayer) but works as a stand-alone tool.

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

## Options
The HTML tag to use for Quill attributes can be set along with the HTML tags for the container.
 
### Default options

Separator | HTML Tag
--- | --- 
Container | `<p>`

#### Default attribute options

Quill Attribute | HTML Tag
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

## Planned features

* Markdown support
* Lists (Bullets and Ordered)
* Formatting options (justification etc.)
* Remaining toolbar options
* Missing tests (options)

## Warnings

### Image support

The image support is rudimentary; it isn't production ready, some work needs to be done to support 
images. I can think of two solutions, pre-save of deltas, post the base64 and return a URI to replace the 
base64, or, at render time, fetch/cache an image/URI by posting the base64, later down the line I may 
explore one of these options.

Why? 

I'm using this package within Dlayer, my app has its own image handling and I will not be exposing 
the image functionality of Quill.
