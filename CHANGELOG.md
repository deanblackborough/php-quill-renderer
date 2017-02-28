
# Changelog

Full changelog for PHP Quill Renderer

## Dev master

### v0.20 - In progress

* Added basic support for lists, `<ul>` and `<ol>`, at this time there is no guarantee attributes will work for 
content within the `<li>`s and nested lists are not supported. [Feature]

### v0.10 - 2017-02-26

* Newline correctly trimmed from final insert. [Bugfix]

### Initial release - 2017-02-25

* Initial release, converts delta inserts into HTML, support four attributes, 
`bold`, `strike`, `italic` and `underline`. The HTML tag to be used for each 
attribute can be set along with the attributes to use for newlines and paragraphs, 
defauls to `br` and `p`.
