[![Latest Stable Version](https://img.shields.io/packagist/v/deanblackborough/php-quill-renderer.svg?style=flat-square)](https://packagist.org/packages/deanblackborough/php-quill-renderer)
![Packagist](https://img.shields.io/packagist/dt/deanblackborough/php-quill-renderer.svg)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE)
[![Minimum PHP Version](https://img.shields.io/badge/php->=7.1-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/deanblackborough/php-quill-renderer.svg?branch=master)](https://travis-ci.org/deanblackborough/php-quill-renderer)
[![Coverage Status](https://coveralls.io/repos/github/deanblackborough/php-quill-renderer/badge.svg?branch=master)](https://coveralls.io/github/deanblackborough/php-quill-renderer?branch=master)

# PHP Quill Renderer

Render quill insert deltas to HTML, Markdown and GitHub flavoured Markdown.

## Description

[Quill](https://github.com/quilljs/quill)  deltas renderer, converts deltas to HTML and Markdown, the [Quill](https://github.com/quilljs/quill) attributes 
supported are listed in the table below, the goal is to eventually support every Quill feature.

[Quill](https://github.com/quilljs/quill) is a modern WYSIWYG editor built for compatibility and extensibility.

## Planned features

Over the next few weeks/months I want to continue adding support for additional 
[Quill](https://github.com/quilljs/quill) features and add, flesh out additional 
output formats, after Markdown I'm planning on plain txt, GitHub flavoured Markdown 
and possibly RTF/PDF. 

## Installation
 
The easiest way to use the `PHP Quill Renderer` is via composer. 
```composer require deanblackborough/php-quill-renderer```, 
alternatively you can include the classes in my src/ directory directly in your library or app.

## Usage

### Via API, single $quill_json
```
try {
    $quill = new \DBlackborough\Quill\Render($quill_json);
    $result = $quill->render();
} catch (\Exception $e) {
    echo $e->getMessage();
}

echo $result;
```

### Via API, multiple $quill_json, passed in via array

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

### Direct, parse and then render, single $quill_json - updated in v3.10.0

```
$parser = new \DBlackborough\Quill\Parser\Html();
$renderer = new \DBlackborough\Quill\Renderer\Html();

$parser->load($quill_json)->parse();

echo $renderer->load($parser->deltas())->render();
```

### Direct, parse and then render, multiple $quill_json - updated in v3.10.0

```
$parser = new \DBlackborough\Quill\Parser\Html();
$renderer = new \DBlackborough\Quill\Renderer\Html();

$parser->loadMultiple(['one'=> $quill_json_1, 'two' => $quill_json_2)->parseMultiple();

echo $renderer->load($parser->deltasByIndex('one'))->render();
echo $renderer->load($parser->deltasByIndex('two'))->render();
```

## Quill attributes and text flow support

| Attribute | v1+ | v2+ | v3 HTML | v3 Markdown
| :---: | :---: | :---: | :---: | :---:
| Bold | Yes | Yes | Yes | Yes
| Italic | Yes | Yes | Yes | Yes
| Link | Yes | Yes | Yes | Yes
| Strike | Yes | Yes | Yes | N/A
| Script:Sub | Yes | Yes | Yes | N/A
| Script:Super | Yes | Yes | Yes | N/A
| Underline | Yes | Yes | Yes | N/A
| Header | Yes | Yes | Yes | Yes
| Image | Yes | Yes | Yes | Yes
| Video | No | No | Yes | Yes
| List | Yes | Yes | Yes | Yes
| Child lists | No | No | In Development | In Development
| Indent/Outdent | No| No | Planned | Planned
| Text direction | No | No | Planned | N/A
| Color | No | No | Planned | N/K
| Font | No | No | Planned | N/K
| Text align | No | No | Planned | N/A
| Block quote | No | No | Planned | Planned
| Code block | No | No | Planned | Planned
| Custom attributes | No | No | Yes | N/A
| Line breaks | No | No | Yes | Yes
| Paragraphs | Yes | Yes | Yes | Yes

| Attribute | HTML Tag | Markdown Token
| :---: | :---: | :---:
| Bold | `<strong>` | `**`
| Italic | `<em>` | `*`
| Link | `<a>` | `[Text](Link)`
| Strike | `<s>` |
| Script:Sub | `<sub>` |
| Script:Super | `<sup>` |
| Underline | `<u>` |
| Header | `<h[n]>` | `#[n]`
| Image | `<img>` | `![Image](\path\to\image)`
| Video | `<iframe>` | `![Video](\path\to\video)`
| List | `<ul>` `<ol>` | `* ` & `[n]`

## Copyright and license
The [deanblackborough/php-quill-renderer](https://github.com/deanblackborough/php-quill-renderer) library is copyright 
© Dean Blackborough and [licensed](https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE) 
for use under the MIT License (MIT). 

## Credits

* [carlos](https://github.com/sald19) [Bugfix] v1.01.0.
* [pdiveris](https://github.com/pdiveris) [Issue #43] - Null inserts.
* [Mark Davison](https://github.com/markdavison) - Pushed me in the right direction for v3.00.0.
* [tominventisbe](https://github.com/tominventisbe) [Issue #54] - Parser::load() does not reset the deltas array.
* [tominventisbe](https://github.com/tominventisbe) [Issue #55] - Image deltas with multiple attributes incorrectly being passed to Compound delta.
* [bcorcoran](https://github.com/bcorcoran) [Issue #81] - Suggested reverting requirements to necessary requirements.
* [kingga](https://github.com/kingga) [Issue #86] - Video support.
* [Jonathanm10](https://github.com/Jonathanm10) [Issue #87] - Newlines proceeding inserts ignored, bug report.
* [raphaelsaunier](https://github.com/raphaelsaunier) [Issue #87] - Newlines proceeding inserts ignored, bug location.
* [Basil](https://github.com/nadar) [Issue #101] - Newline only inserts being ignored by parser.
* [Lee Hesselden](https://github.com/on2) [PR #104] - Color delta to allowing spans with a style:color="#xxx" definition. (Feature will be extended by [Issue #106]) 
