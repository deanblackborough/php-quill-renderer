
# Changelog

Full changelog for PHP Quill Renderer

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
