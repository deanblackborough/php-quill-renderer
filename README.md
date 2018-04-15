[![Latest Stable Version](https://img.shields.io/packagist/v/deanblackborough/php-quill-renderer.svg?style=flat-square)](https://packagist.org/packages/deanblackborough/php-quill-renderer)
![Packagist](https://img.shields.io/packagist/dt/deanblackborough/php-quill-renderer.svg)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE)
[![Minimum PHP Version](https://img.shields.io/badge/php->=7.1-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/deanblackborough/php-quill-renderer.svg?branch=master)](https://travis-ci.org/deanblackborough/php-quill-renderer)

# PHP Quill Renderer

*Render quill insert deltas to HTML and soon Markdown.*

## Description

Quill deltas renderer, converts deltas to HTML, the Quill attributes supported are listed in a table below.
I'm working on support for the remaining attributes and additional parsers (markdown etc.)

## Version 3.0 in the works

I've had trouble getting the testMultipleParagraphsWithAttributes test to pass, I've tried a few times over the last 
couple of weeks to get the test to pass without adding anymore hacks, I failed. 

The core code isn't flexible enough, I've mapped out a more flexible design for version 3.0.

## Version 3.0 timeline (Added 15/04/2018)

I'm hoping to have the first release for 3.0 out within the next two to three weeks, as with any timeline, it is a guide, life can and does find a way to get in the way :)

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

## Quill attributes support

Attribute | v1+ | v2+ | v3+
--- | --- | --- | ---
Bold | Yes | Yes | In Development
Italic | Yes | Yes | In Development
Link | Yes | Yes | In Development
Strike | Yes | Yes | In Development
Script:Sub | Yes | Yes | In Development
Script:Super | Yes | Yes | In Development
Underline | Yes | Yes | In Development
Header | Yes | Yes | In Development
Image | Yes | Yes | In Development
List | Yes | Yes | In Development
Indent/Outdent | No| No | In Development
Text direction | No | No | In Development
Color | No | No | In Development
Font | No | No | In Development
Text align | No | No | In Development
Block quote | No | No | In Development
Code block | No | No | In Development

Development on v3 has begun, I'm unlikely to add new features to v1/v2, just bug fixes as bugs 
are discovered.

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
pdiveris https://github.com/pdiveris [Issue #43] - Null inserts

## Warnings

### Image support

The image support is rudimentary; it isn't production ready, some work needs to be done to support 
images. I can think of two solutions, pre-save of deltas, post the base64 and return a URI to replace the 
base64, or, at render time, fetch/cache an image/URI by posting the base64, later down the line I may 
explore one of these options.

Why? 

I'm using this package within Dlayer, my app has its own image handling and I will not be exposing 
the image functionality of Quill.
