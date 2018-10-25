
# Changelog

Full changelog for PHP Quill Renderer

## v3.15.0 - 2018-10-25

* Added support for `Color` delta type, it creates a span with a style="color: #xxx" definition, 
thank you (https://github.com/on2)

Notes

The implementation works and does not break anything, however, I will extend the feature in the future. 
It should really be handled in the same way unknown attributes are handled in the plain Insert `delta`. 
The Insert delta only deals with a simple key and value, it needs to be extended to allow complex values, 
that way an attribute like `{"attributes":{"style":{"color": #e60000"}}` could generate the same output 
and allow much more flexibility. I'm happy to leave the `Color` delta in until the next major version as I 
believe it is a feature other people may use. 

## v3.14.3 - 2018-10-25

* New paragraph not correctly being created for final sub insert when insert split on multiple newlines.

## v3.14.2 - 2018-09-11

* New line only inserts no longer ignored by parser, updated a couple of tests because of logic update.
* Updated README, added new credit.

## v3.14.1 - 2018-08-20

* Multiple attributes ignored by the Markdown and the GitHub Markdown parsers, added two tests to catch regressions.
* Minor changes to README and composer.json.

## v3.14.0 - 2018-07-10 

* Initial support for GitHub flavoured Markdown, extends Markdown and adds support for strike through text.
* Added basic escaping for HTML and Markdown.
* Reorganised the tests ready for additional file support and features.

## v3.13.4 - 2018-07-09

* Removed duplication in HTMl and Markdown parser, moved similar logic to the base parser class.
* Updated composer dependencies.
* Added additional Quill info to README.

## v3.13.3 - 2018-07-04

* The Markdown list parsing now correctly using child deltas and matches the HTML parser.
* Added additional list tests, Markdown versions of the HTML list tests.

## v3.13.2 - 2018-07-01

* List parsing code was ignoring previous items that were closed, incorrectly adding them as children.
* Single item lists not correctly ending.
* Lists where the last character or the entire list item was a non insert delta type rendered incorrectly. 
* Deltas can now return their assigned attributes.
* Pass attributes into each HTML delta.
* A slew of tests to test lists.

## v3.13.1 - 2018-06-22

* Lists no longer break if a list item contains formatted text, closes #89.
* Initial support for child deltas.

## v3.13.0 - 2018-06-14

* Added video support to HTML and Markdown parsers.
* Newlines before inserts were being ignore, closes #87. 
* Minor corrections to README.
* Added test coverage.

## v3.12.0 - 2018-06-02

* Added new Interfaces, will simplify additional of new parsers.
* Initial Markdown development, not complete, no escaping yet and not fully tested.
* Reverted requirements to 7.1, turns out there isn't currently a hard requirement for 7.2.
* General refactoring, also, improved list handling.

## v3.11.0 - 2018-05-29

* `load()` and `loadMultple()` now support method chaining, closes #72, required 
a minor change to throw exceptions.
* Generated HTML now has newlines where expected, closes #69
* Updated one test, expected and actual the wrong way around.
* Added a `Options` class.
* Added two tests to catch exceptions.
* Added a trim option to `render`, strips any extra whitespace.

## v3.10.2 - 2018-05-28

* Line breaks missing, added where expected, a couple of tests where updated 
to include the `<br />` where it should have been in the expected output, 
closes #67.

## v3.10.1 - 2018-05-21

* Fixed bugs #64 and #65, empty deltas being created by the preg_match on \n

## v3.10.0 - 2018-05-16

* Added support for passing multiple deltas into the API/parser, limited to multiple of the same type.
* Added support for reusing the renderer, added `load()` method to enable this.
* README updated to show new usage.
* Minor comment and variable corrections.
* Added tests to cover new feature.

## v3.02.0 - 2018-05-13

* Add initial support for loading multiple deltas, very basic and not yet supported through the API, next version.
* Custom attributes can be added to base insert and compound delta. The base insert adds a span around the insert 
text; the compound delta adds the attributes to the outer HTML tag.
* Added code coverage reporting via coveralls.io.
* Increased test coverage, test for thrown exceptions and removed redundant method in Delta class.

## v3.01.0 - 2018-05-10

* `Parser::load()` wasn't resetting the deltas array, thanks [tominventisbe](https://github.com/tominventisbe).
* Added `CompoundImage` delta, `Compound` delta was incorrectly trying to handle images, thanks [tominventisbe](https://github.com/tominventisbe).
* The `CompoundImage` delta now assigns all defined attributes to the `img` tag.
* Renamed the `CompositeTest`, now `CompoundTest`, new name more closely matches what I am testing.
* Added credit to [Mark Davison](https://github.com/markdavison) - Missing in the v3.00.0 release.

## v3.00.0 - 2018-05-08

v3.00.0 is an almost complete rewrite of v2.03.1, the new design is flexible and supports all the features of the 
previous versions without any blatant hacks, no more methods named checkLastItemClosed() or 
removeRedundantParentTags(), the renderer is simpler because it just needs to iterate over a Deltas array.

There was no change to the API. However, if you use it by calling the parser and renderer classes directly I renamed 
one method, `\DBlackborough\Quill\Parser\HTML::content()` is now `\DBlackborough\Quill\Parser\HTML::deltas()`.

## 2.03.1 - 2018-04-15

* Minor bug fix and test thanks to pdiveris (https://github.com/pdiveris), deals with null inserts.
* Updated README, feature list incorrect, added v3.0 and also added a message on v1/v2 development. 

## 2.03.0 - 2018-03-03

* Updated composer.json, added suggest for PHP7.2.
* Removed /example folder and updated .gitignore.
* Added deltas() method to Parser/HTML.php.
* Added parserLoaded() method to Render.php
* Reworked tests.
* Added additional paragraph tests.

Note: The CompositeTest::testMultipleParagraphsWithAttributes test fails, this will pass in version 3.0.

## 2.02.1 - 2017-10-01

* Updated attribute support table in README.
* Removed redundant settings method. 
* Removed redundant construct params.
* Removed commented out code 

## 2.02.0 - 2017-09-18

* Removed settings code, new parser/renderer should be created to change options.
* Refactoring, updated method names to better match the containing logic.
* Updated README, Quill attribute support.

## 2.01.0 - 2017-09-17

* Organised tests by renderer type prior to markdown development.
* Removed settings tests, settings are being stripped, don't make any sense, better option is to create a new renderer.

## 2.00.1 - 2017-09-14

* Removed ./idea/ from .gitignore, should be ignored globally, not locally.

## 2.00.0 - 2017-09-14

* Switched to PHP 7.1 only.
* Updated library code, strict types etc.

## 1.01.1 - 2017-09-11

* If a list follows text the generated HTML is invalid. [Bugfix] (Credit: Carlos https://github.com/sald19 
for finding bug)
* Switched to preg_split, code was looking for two or more newlines but ever only splitting on two.
* Moved newline replacement to last possible step.

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
