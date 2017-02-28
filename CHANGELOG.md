
# Changelog

Full changelog for PHP Quill Renderer

## Dev master

### v0.20 - 2017-02-26

* Reworked rendering code.
* Added base class so additional rendering class can be developed, for example a markdown renderer.

I got most of the way through adding basic support for lists and then stumbled on a problem, I need to rework 
 how new lines are handled and tidy up the code, needs to be come aware of block elements.
 
### v0.10 - 2017-02-26

* Newline correctly trimmed from final insert. [Bugfix]

### Initial release - 2017-02-25

* Initial release, converts delta inserts into HTML, support four attributes, 
`bold`, `strike`, `italic` and `underline`. The HTML tag to be used for each 
attribute can be set along with the attributes to use for newlines and paragraphs, 
defaults to `br` and `p`.
