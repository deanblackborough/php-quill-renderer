[![Latest Stable Version](https://img.shields.io/packagist/v/deanblackborough/php-quill-renderer.svg?style=flat-square)](https://packagist.org/packages/deanblackborough/php-quill-renderer)
![Packagist](https://img.shields.io/packagist/dt/deanblackborough/php-quill-renderer.svg)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE)
[![Minimum PHP Version](https://img.shields.io/badge/php->=7.2-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/deanblackborough/php-quill-renderer.svg?branch=master)](https://travis-ci.org/deanblackborough/php-quill-renderer)
[![Coverage Status](https://coveralls.io/repos/github/deanblackborough/php-quill-renderer/badge.svg?branch=coverage)](https://coveralls.io/github/deanblackborough/php-quill-renderer?branch=coverage)

# PHP Quill Renderer

*Render quill insert deltas to HTML, additional output formats will be added after the v3.00.0 release*

## Description

Quill deltas renderer, converts deltas to HTML, the Quill attributes supported are listed in the table below, 
the goal is to eventually support every Quill feature.

## Planned features

Over the next few weeks/months I want to continue adding support for additional Quill features and add additional 
 parsers and renderers, I expect Markdown will be next. 

## PHP < 7.2

Please use version v1.01.1 or v2.03.1 if you are using a version of PHP below 7.2, versions 1 and 2 are not feature 
with version 3 and are unlikely to ever be updated, the v3 is so much more flexible.

## Installation
 
The easiest way to use the renderer is via composer. ```composer require deanblackborough/php-quill-renderer```, 
alternatively you can include the classes in my src/ directory in your library or app.
 
## Usage
```
try {
    $quill = new \DBlackborough\Quill\Render($quill_json, 'HTML');
    echo $quill->render();
} catch (\Exception $e) {
    echo $e->getMessage();
}
```

## Usage, direct, parse and then render
```
$parser = new \DBlackborough\Quill\Parser\Html();
$parser->load($quill_json);
$parser->parse();

$renderer = new \DBlackborough\Quill\Renderer\Html($parser->deltas());
echo $renderer->render();
```

## Quill attributes support

Attribute | v1+ | v2+ | v3+
--- | --- | --- | ---
Bold | Yes | Yes | Yes
Italic | Yes | Yes | Yes
Link | Yes | Yes | Yes
Strike | Yes | Yes | Yes
Script:Sub | Yes | Yes | Yes
Script:Super | Yes | Yes | Yes
Underline | Yes | Yes | Yes
Header | Yes | Yes | Yes
Image | Yes | Yes | Yes
List | Yes | Yes | Yes
Indent/Outdent | No| No | Planned
Text direction | No | No | Planned
Color | No | No | Planned
Font | No | No | Planned
Text align | No | No | Planned
Block quote | No | No | Planned
Code block | No | No | Planned

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

* [carlos](https://github.com/sald19) [Bugfix] v1.01.0
* [pdiveris](https://github.com/pdiveris) [Issue #43] v2.03.1 - Null inserts
* [Mark Davison](https://github.com/markdavison) - Pushed me in the right direction for v3.00.0
* [tominventisbe](https://github.com/tominventisbe) [Issue #54] v3.01.0 - Parser::load() does not reset the deltas array
* [tominventisbe](https://github.com/tominventisbe) [Issue #55] v3.01.0 - Image deltas with multiple attributes incorrectly being passed to Compound delta
