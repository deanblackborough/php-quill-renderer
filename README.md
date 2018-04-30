[![Latest Stable Version](https://img.shields.io/packagist/v/deanblackborough/php-quill-renderer.svg?style=flat-square)](https://packagist.org/packages/deanblackborough/php-quill-renderer)
![Packagist](https://img.shields.io/packagist/dt/deanblackborough/php-quill-renderer.svg)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE)
[![Minimum PHP Version](https://img.shields.io/badge/php->=7.2-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/deanblackborough/php-quill-renderer.svg?branch=master)](https://travis-ci.org/deanblackborough/php-quill-renderer)

# PHP Quill Renderer

*Render quill insert deltas to HTML, additional output formats will be added after the v3.00.0 release*

## Description

Quill deltas renderer, converts deltas to HTML, the Quill attributes supported are listed in a table below, the goal is 
to support every Quill feature.

# Stability of master

I'm periodically merging the v3.00.0-alpha branch into master, master should be considered unstable until the 
first alpha release of v3.00.0, from that point stability will be maintained.

## PHP < 7.2

Please use version v1.01.1 or v2.03.1 if you are using an earlier version of PHP, versions 1 and 2 are not feature 
complete with v3, v3 is a almost complete rewrite.

## Installation
 
The easiest way to use the renderer is with composer. ```composer require deanblackborough/php-quill-renderer```, 
alternatively include the classes in src/ in your library or app.
 
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

* carlos https://github.com/sald19 [Bugfix] v1.01.0
* pdiveris https://github.com/pdiveris [Issue #43] v2.03.1 - Null inserts
