
# Changelog

Full changelog for PHP Quill Renderer

## 1.01.0 - 2017-09-04

* Attribute incorrect for bullet list (Credit: Carlos https://github.com/sald19) [Bugfix]
* Only testing against 7.1 going forward.
* Added credits section to README.

## 1.00.0 - 2017-08-31

* Added support for ordered and unordered lists.
* Updated README, notes on version 2.00.0

## v0.90 - 2017-06-06

* Added support for paragraph breaks, converts double newlines from quill insert into a new p tag, the
 feature is not yet bulletproof, I need to take another look at my parser.

## v0.80.1 - 2017-04-26

* Composer autoload definition updated.
* Added warnings to readme.

## v0.80.0 - 2017-04-25 

* I'm now only testing against PHP 7+. [Tests]
* Added tests for setting attributes. [Tests]
* Switched to PSR4
* Minor change to API if using Quill (Render) class, after the PSR4 change I didn't like Quill/Quill.
* Basic support for images (outputting the base64 directly via <img> src="")

## v0.70.0 - 2017-04-19

* Added the ability to set the HTML tag for the following Quill attributes, bold, italic, script, strike and underline. [Feature]
* Minor rework to Quill class to allow options to be set, parse() was being called before the new option value was being set. 
* Updated readme, now shows direct usage example.

## v0.60.1 - 2017-04-12

* HTML parser no longer checks against HTML tags directly (h1, h2 etc), uses tag type. [Bugfix]
* Added `assign` index to options array, no longer need to check HTML tag directly. [Bugfix]
* Removed redundant code.

## v0.60.0 - 2017-03-27

* I simplified the usage of renderer ready for additional output formats, instantiate Quill class to use renderer and then simply call render().
* I have finally moved to PHPUnit 6.

**Note:** _The Quill class does not expose the ability to override options, that will be added in the future._ 

## v0.50.0 - 2017-03-23 

* I added support for headings. [Feature]
* I have had to remove all newline support, I need to rework my logic. [Regression]

## v0.40.0 - 2017-03-10 

* I have added support for sub and super script. [Feature]

## v0.30.0 - 2017-03-06

* I have added support for links. [Feature]
* I have been busy refactoring, I have simplified how attributes are replaced.
* I have added additional tests.

## v0.20.1 - 2017-03-02

* I have updated the README, example incorrect.
* I have updated all method documentation.

### v0.20 - 2017-02-26

I got most of the way through adding basic support for lists and then stumbled on a problem, I need to rework 
 how newlines are handled and tidy up the code, the code needs to become aware of block elements.

* Reworked rendering code.
* Added base class so additional rendering class can be developed, for example a markdown renderer.

### v0.10 - 2017-02-26

* Newline correctly trimmed from final insert. [Bugfix]

### Initial release - 2017-02-25

* Initial release, converts delta inserts into HTML, support four attributes, 
`bold`, `strike`, `italic` and `underline`. The HTML tag to be used for each 
attribute can be set along with the attributes to use for newlines and paragraphs, 
defaults to `br` and `p`.
