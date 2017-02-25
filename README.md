[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/Dlayer/dlayer/blob/master/LICENSE)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/deanblackborough/php-quill-renderer.svg?branch=master)](https://travis-ci.org/deanblackborough/php-quill-renderer)

# PHP Quill Renderer

---

*Render quill insert deltas to HTML.*

## Description

Simple Quill delta insert renderer, currently it only supports the attributes I require, 'italic', 'bold', 'underline' 
and 'strike', I will add support for additional attributes as I need them in Dlayer.

Created for use in [Dlayer](https://github.com/Dlayer/dlayer) but works as a stand-alone tool.

## Installation
 
The easiest way to use the renderer is with composer. ```composer require deanblackborough/php-quill-renderer```, 
alternatively include the classes in src/ in your library.
 
## Usage
* Instantiate the render, either use default option or pass in custom options ```$renderer = new \DBlackborough\Quill\Renderer();```
* Load the json ```$renderer->load($json);```. Returns TRUE|FALSE
* Return html ```echo $renderer->toHtml();```

## Options
The html tag to use for the `bold`, `italic`, `strike` and `underline` attributes can be set along with the tags to 
use for the `container` and `newline`.
 
### Default options

* `container`: `p`
*  `newline`: `br`

#### Default attribute options

Quill Attribute | HTML Tag
--- | --- 
Bold | `<strong>`
Italic | `<em>`
Underline | `<u>`
Strike | `<s>`

## Planned features

* Links
* External images
