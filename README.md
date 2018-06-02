[![Latest Stable Version](https://img.shields.io/packagist/v/deanblackborough/php-quill-renderer.svg?style=flat-square)](https://packagist.org/packages/deanblackborough/php-quill-renderer)
![Packagist](https://img.shields.io/packagist/dt/deanblackborough/php-quill-renderer.svg)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE)
[![Minimum PHP Version](https://img.shields.io/badge/php->=7.1-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/deanblackborough/php-quill-renderer.svg?branch=master)](https://travis-ci.org/deanblackborough/php-quill-renderer)
[![Coverage Status](https://coveralls.io/repos/github/deanblackborough/php-quill-renderer/badge.svg?branch=master)](https://coveralls.io/github/deanblackborough/php-quill-renderer?branch=master)

# PHP Quill Renderer

Render quill insert deltas to HTML and Markdown

## Description

Quill deltas renderer, converts deltas to HTML and Markdown, the Quill attributes 
supported are listed in the table below, the goal is to eventually support every Quill feature.

## Planned features

Over the next few weeks/months I want to continue adding support for additional 
[Quill](https://github.com/quilljs/quill) features and add, flesh out additional 
output formats, after Markdown I'm planning on plain txt, GitHUb flavoured Markdown 
and possible RTF/PDF. 

## PHP < 7.2

Please use version v1.01.1 or v2.03.1 if you are using a version of PHP below 7.2, 
versions 1 and 2 are not feature complete with version 3 and are unlikely to ever 
be updated, the v3 code is so much more flexible, consider 1 and 2 unsupported.

## Installation
 
The easiest way to use the `PHP Quill Renderer` is via composer. 
```composer require deanblackborough/php-quill-renderer```, 
alternatively you can include the classes in my src/ directory directly in 
your library or app.

### Legacy entry points

The `Render` and `RenderMultiple` classes are marked as deprecated, there is a 
now a single entry point, `Quill`. The `Render` and `RenderMultiple` classes 
will remain but should be considered legacy.
 
## Usage via API, single $quill_json
```
try {
    $quill = new \DBlackborough\Quill\Render($quill_json, 'HTML');
    $result = $quill->render();
} catch (\Exception $e) {
    echo $e->getMessage();
}

echo $result;
```

## Usage via API, multiple $quill_json, passed in via array

```
try {
    $quill = new RenderMultiple($quill_json, 'HTML');
    
    $result_one = $quill->render('one');
    $result_two = $quill->render('two');
} catch (\Exception $e) {
    echo $e->getMessage();
}

echo $result_one;
echo $result_two;
```

## Usage, direct, parse and then render, single $quill_json - updated in v3.10.0

```
$parser = new \DBlackborough\Quill\Parser\Html();
$renderer = new \DBlackborough\Quill\Renderer\Html();

$parser->load($quill_json)->parse();

echo $renderer->load($parser->deltas())->render();
```

## Usage, direct, parse and then render, multiple $quill_json - updated in v3.10.0

```
$parser = new \DBlackborough\Quill\Parser\Html();
$renderer = new \DBlackborough\Quill\Renderer\Html();

$parser->loadMultiple(['one'=> $quill_json_1, 'two' => $quill_json_2)->parseMultiple();

echo $renderer->load($parser->deltasByIndex('one'))->render();
echo $renderer->load($parser->deltasByIndex('two'))->render();
```

## Quill attributes and text flow support

Attribute | v1+ | v2+ | v3 HTML | v3 Markdown
--- | --- | --- | --- | ---
Bold | Yes | Yes | Yes | Yes
Italic | Yes | Yes | Yes | Yes
Link | Yes | Yes | Yes | Yes
Strike | Yes | Yes | Yes | N/K
Script:Sub | Yes | Yes | Yes | N/K
Script:Super | Yes | Yes | Yes | N/K
Underline | Yes | Yes | Yes | N/K
Header | Yes | Yes | Yes | Yes
Image | Yes | Yes | Yes | Yes
List | Yes | Yes | Yes | Planned
Child lists | No | No | Planned | Planned
Indent/Outdent | No| No | Planned | N/K
Text direction | No | No | Planned | N/K
Color | No | No | Planned | N/K
Font | No | No | Planned | N/K
Text align | No | No | Planned | N/K
Block quote | No | No | Planned | Planned
Code block | No | No | Planned | Planned
Custom attributes | No | No | Yes | N/K
Line breaks | No | No | Yes | N/A
Paragraphs | Yes | Yes | Yes | N/A

Attribute | HTML Tag | Markdown Token
--- | --- | ---
Bold | `<strong>` | `**`
Italic | `<em>` | `*`
Link | `<a>` | `[Text](Link)`
Strike | `<s>` |
Script:Sub | `<sub>` |
Script:Super | `<sup>` |
Underline | `<u>` |
Header | `<h[n]>` | `#[n]`
Image | `<img>` | `![Image](\path\to\image)`
List | `<ul>` `<ol>` | `* ` & `[n]`

## Credits

* [carlos](https://github.com/sald19) [Bugfix] v1.01.0
* [pdiveris](https://github.com/pdiveris) [Issue #43] v2.03.1 - Null inserts
* [Mark Davison](https://github.com/markdavison) - Pushed me in the right direction for v3.00.0
* [tominventisbe](https://github.com/tominventisbe) [Issue #54] v3.01.0 - Parser::load() does not reset the deltas array
* [tominventisbe](https://github.com/tominventisbe) [Issue #55] v3.01.0 - Image deltas with multiple attributes incorrectly being passed to Compound delta
