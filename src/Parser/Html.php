<?php

namespace DBlackborough\Quill\Parser;

use \Exception;

/**
 * Parser for HTML, parses the deltas to generate a content array for  deltas into a html redy array
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Html extends Parse
{
    /**
     * Renderer constructor.
     *
     * @param array $options Options data array, if empty default options are used
     * @param string $block_element
     */
    public function __construct(array $options = array(), $block_element = null)
    {
        parent::__construct($options, $block_element);
    }

    /**
     * Default block element attribute
     */
    protected function defaultBlockElementOption()
    {
        return array(
            'tag' => 'p',
            'type' => 'block'
        );
    }

    /**
     * Default attribute options for the HTML renderer/parser
     *
     * @return array
     */
    protected function defaultAttributeOptions()
    {
        return array(
            'bold' => array(
                'tag' => 'strong',
                'type' => 'inline',
            ),
            'header' => array(
                '1' => array(
                    'tag' => 'h1',
                    'type' => 'block',
                    'assign' => 'previous'
                ),
                '2' => array(
                    'tag' => 'h2',
                    'type' => 'block',
                    'assign' => 'previous'
                ),
                '3' => array(
                    'tag' => 'h3',
                    'type' => 'block',
                    'assign' => 'previous'
                ),
                '4' => array(
                    'tag' => 'h4',
                    'type' => 'block',
                    'assign' => 'previous'
                ),
                '5' => array(
                    'tag' => 'h5',
                    'type' => 'block',
                    'assign' => 'previous'
                ),
                '6' => array(
                    'tag' => 'h6',
                    'type' => 'block',
                    'assign' => 'previous'
                ),
                '7' => array(
                    'tag' => 'h7',
                    'type' => 'block',
                    'assign' => 'previous'
                )
            ),
            'italic' => array(
                'tag' => 'em',
                'type' => 'inline'
            ),
            'link' => array(
                'tag' => 'a',
                'type' => 'inline',
                'attributes' => array(
                    'href' => null
                )
            ),
            'list' => array(
                'ordered' => array(
                    'tag' => 'li',
                    'type' => 'block',
                    'assign' => 'previous',
                    'parent_tag' => 'ol'
                ),
                'bullet' => array(
                    'tag' => 'li',
                    'type' => 'block',
                    'assign' => 'previous',
                    'parent_tag' => 'ul'
                )
            ),
            'script' => array(
                'sub' => array(
                    'type' => 'inline',
                    'tag' => 'sub'
                ),
                'super' => array(
                    'type' => 'inline',
                    'tag' => 'sup'
                )
            ),
            'strike' => array(
                'tag' => 's',
                'type' => 'inline'
            ),
            'underline' => array(
                'tag' => 'u',
                'type' => 'inline'
            ),
        );
    }

    /**
     * Get the tag(s) and attributes/values that have been defined for the quill attribute.
     *
     * @param string $attribute
     * @param string $value
     *
     * @return array|false
     */
    private function getTagAndAttributes($attribute, $value)
    {
        switch ($attribute)
        {
            case 'bold':
            case 'italic':
            case 'strike':
            case 'underline':
                return $this->options['attributes'][$attribute];
                break;

            case 'header':
            case 'list':
            case 'script':
                return $this->options['attributes'][$attribute][$value];
                break;

            case 'link':
                $result = $this->options['attributes'][$attribute];
                $result['attributes']['href'] = $value;
                return $result;
                break;

            default:
                // Do nothing, valid already set to false
                return false;
                break;
        }
    }

    /**
     * Check to see if the last item in the content array is a closed block element
     *
     * @return void
     */
    private function lastItemClosed()
    {
        $last_item = count($this->content) - 1;
        $assigned_tags = $this->content[$last_item]['tags'];
        $block = false;

        if (count($assigned_tags) > 0) {
            foreach ($assigned_tags as $assigned_tag) {
                // Block element check
                if ($assigned_tag['close'] !== null && $assigned_tag['type'] === 'block') {
                    $block = true;
                    continue;
                }
            }
        }

        if ($block === false) {
            $this->content[$last_item]['tags'] = array();
            foreach ($assigned_tags as $assigned_tag) {
                $this->content[$last_item]['tags'][] = $assigned_tag;
            }
            $this->content[$last_item]['tags'][] = array(
                'open' => null,
                'close' => '</' . $this->options['block']['tag'] . '>',
                'type' => 'block'
            );
        }
    }

    /**
     * Split the inserts if multiple newlines are found and generate a new insert
     *
     * @return void
     */
    private function splitDeltas()
    {
        $deltas = $this->deltas;
        $this->deltas = array();

        foreach ($deltas as $k => $delta) {
            if (array_key_exists('insert', $delta) === true &&
                is_array($delta['insert']) === false &&
                preg_match("/[\n]{2,}/", $delta['insert']) !== 0) {

                foreach (preg_split("/[\n]{2,}/", $delta['insert']) as $match) {
                    $new_delta = [
                        'insert' => str_replace("\n", '', $match),
                        'break' => true
                    ];

                    $this->deltas[] = $new_delta;
                }
            } else {
                $this->deltas[] = $delta;
            }
        }
    }

    /**
     * List hack
     *
     * Looks to see if the next item is the start of a list, if this item contains a new line we need
     * to split it.
     */
    private function listHack()
    {
        $deltas = $this->deltas['ops'];
        $this->deltas = array();

        foreach ($deltas as $k => $delta) {
            if (array_key_exists('insert', $delta) === true &&
                is_array($delta['insert']) === false &&
                preg_match("/[\n]{1}/", $delta['insert']) !== 0 &&
                array_key_exists(($k+1), $deltas) === true &&
                array_key_exists('attributes', $deltas[($k+1)]) === true &&
                array_key_exists('list', $deltas[($k+1)]['attributes']) === true) {

                foreach (preg_split("/[\n]{1}/", $delta['insert']) as $match) {
                    $new_delta = [
                        'insert' => $match
                    ];

                    $this->deltas[] = $new_delta;
                }
            } else {
                $this->deltas[] = $delta;
            }
        }
    }

    /**
     * Assign the relevant HTML tags based upon the defined quill attribute
     *
     * @return void
     */
    private function assignTags()
    {
        $i = 0;

        foreach ($this->deltas as $insert) {

            $this->content[$i] = array(
                'content' => null,
                'tags' => array(),
            );

            if (array_key_exists('break', $insert) === true) {
                $this->content[$i]['break'] = true;
            }

            $tags = array();
            $has_tags = false;

            if (array_key_exists('attributes', $insert) === true && is_array($insert['attributes']) === true) {
                foreach ($insert['attributes'] as $attribute => $value) {
                    if ($this->isAttributeValid($attribute, $value) === true) {
                        $tag = $this->getTagAndAttributes($attribute, $value);
                        if ($tag !== false) {
                            $tags[] = $tag;
                        }
                    }
                }
            }

            if (count($tags) > 0) {
                $has_tags = true; // Set bool so we don't need to check array size again
            }

            if ($has_tags === true) {
                foreach ($tags as $tag) {
                    if (array_key_exists('assign', $tag) === false) {
                        $assign_tag_to_current_element = true;
                    } else {
                        $assign_tag_to_current_element = false;
                    }

                    $open = '<' . $tag['tag'];
                    if (array_key_exists('attributes', $tag) === true) {
                        $open .= ' ';
                        foreach ($tag['attributes'] as $attribute => $value) {
                            $open .= $attribute . '="' . $value . '"';
                        }
                    }
                    $open .= '>';

                    if ($assign_tag_to_current_element === true) {
                        $tag_counter = $i;
                    } else {
                        $tag_counter = $i - 1;
                    }

                    $parent_tags = array('open' => null, 'close' => null);
                    if (array_key_exists('parent_tag', $tag) === true) {
                        $parent_tags = array (
                            'open' => '<' . $tag['parent_tag'] . '>',
                            'close' => '</' . $tag['parent_tag'] . '>',
                        );
                    }

                    $this->content[$tag_counter]['tags'][] = array(
                        'open' => $open,
                        'close' => '</' . $tag['tag'] . '>',
                        'type' => $tag['type'],
                        'parent_tags' => $parent_tags
                    );
                }
            }

            if (array_key_exists('insert', $insert) === true) {
                if (is_array($insert['insert']) === false && strlen(trim($insert['insert'])) > 0) {
                    $this->content[$i]['content'] = $insert['insert'];
                } else {
                    if (is_array($insert['insert']) === true &&
                        array_key_exists('image', $insert['insert']) === true) {
                        $this->content[$i]['content'] = [ 'image' => $insert['insert']['image'] ];
                    }
                }
            }

            $i++;
        }
    }

    /**
     * Open paragraphs
     *
     * @return void
     */
    private function openParagraphs()
    {
        $open_paragraph = false;
        $opened_at = null;

        foreach ($this->content as $i => $content) {

            if (count($content['tags']) === 0 && $open_paragraph === false) {
                $open_paragraph = true;

                $content['tags'][] = array(
                    'open' => '<' . $this->options['block']['tag'] . '>',
                    'close' => null,
                    'type' => 'block'
                );

                $this->content[$i]['tags'] = $content['tags'];
            }
        }
    }

    /**
     * Convert breaks into new paragraphs
     *
     * @return void
     */
    private function convertBreaks()
    {
        foreach ($this->content as $i => $content) {
            if (array_key_exists('break', $content) === true) {
                foreach ($content['tags'] as $tag) {

                    if (count($content['tags']) === 1) {
                        if ($tag['open'] === null && $tag['close'] === '</' . $this->options['block']['tag'] . '>') {
                            $this->content[$i]['tags'][0]['open'] = '<' . $this->options['block']['tag'] . '>';
                        }
                        if ($tag['open'] === '<' . $this->options['block']['tag'] . '>' && $tag['close'] === null) {
                            $this->content[$i]['tags'][0]['close'] = '</' . $this->options['block']['tag'] . '>';
                        }
                    }
                }
            }
        }
    }

    /**
     * Loops through the deltas object and generate the contents array
     *
     * @todo Not keen on the close*() and remove*() methods, I need to go through the logic and try to
     * remove the need for them by moving the logic into the assign tags function
     *
     * @return boolean
     */
    public function parse()
    {
        if ($this->json_valid === true && array_key_exists('ops', $this->deltas) === true) {

            $this->listHack();

            $this->splitDeltas();

            $this->assignTags();

            $this->removeEmptyElements();

            $this->openParagraphs();

            $this->closeOpenParagraphs();

            $this->lastItemClosed();

            $this->convertBreaks();

            $this->removeRedundantParentTags();

            return true;
        } else {
            return false;
        }
    }

    public function content()
    {
        return $this->content;
    }

    /**
     * Remove empty elements from the contents array, occurs when a tag is assigned to any earlier element
     *
     * @return void
     */
    private function removeEmptyElements()
    {
        $existing_content = $this->content;
        $this->content = array();
        foreach ($existing_content as $content) {
            if (is_array($content['content']) === true || strlen($content['content']) !== 0) {
                $this->content[] = $content;
            }
        }
    }

    /**
     * Check to see if there are any open paragraphs followed by a block element
     *
     * @return void
     */
    private function closeOpenParagraphs()
    {
        $open_paragraph = false;
        $opened_at = null;

        foreach ($this->content as $i => $content) {
            if (array_key_exists('tags', $content) === true && count($content['tags']) === 1 &&
                $content['tags'][0]['open'] === '<p>' && $content['tags'][0]['close'] === null) {

                $open_paragraph = true;
                $opened_at = $i;
            }

            if ($open_paragraph === true && $i !== $opened_at) {
                if (array_key_exists('tags', $content) === true) {
                    foreach ($content['tags'] as $tags) {
                        if ($tags['type'] == 'block') {
                            $open_paragraph = false;
                            $this->content[$i-1]['tags'][] = array(
                                'open' => null,
                                'close' => '</' . $this->options['block']['tag'] . '>',
                                'type' => 'block'
                            );
                        }
                    }
                }
            }
        }
    }

    /**
     * Set all the attribute options for the parser/renderer
     *
     * @param array $options
     *
     * @return void
     */
    public function setAttributeOptions(array $options)
    {
        $this->options['attributes'] = $options;
    }

    /**
     * Set the block element for the parser/renderer
     *
     * @param array $options Block element options
     *
     * @return void
     */
    public function setBlockOptions(array $options)
    {
        $this->options['block'] = $options;
    }

    /**
     * Validate the option request and set the value
     *
     * @param string $option Attribute option to replace
     * @param mixed $value New Attribute option value
     *
     * @return true
     * @throws \Exception
     */
    private function validateAndSetAttributeOption($option, $value)
    {
        if (is_array($value) === true &&
            array_key_exists('tag', $value) === true &&
            array_key_exists('type', $value) === true &&
            in_array($value['type'], array('inline', 'block')) === true) {

            $this->options['attributes'][$option] = $value;

            return true;
        } else if (is_string($value) === true) {
            $this->options['attributes'][$option]['tag'] = $value;

            return true;
        } else {
            if (is_array($value) === true) {
                throw new \Exception('setAttributeOption() value should be an array with two indexes, tag and type');
            } else {
                throw new \Exception('setAttributeOption() value should be an array with two indexes, tag and type');
            }
        }
    }

    /**
     * Set a new attribute option
     *
     * @param string $option Attribute option to replace
     * @param mixed $value New Attribute option value
     *
     * @return boolean
     * @throws \Exception
     */
    public function setAttributeOption($option, $value)
    {
        switch ($option) {
            case 'bold':
            case 'italic':
            case 'script':
            case 'strike':
            case 'underline':
                return $this->validateAndSetAttributeOption($option, $value);
                break;
            case 'header':
            case 'link':
            case 'list':
                return false;
                break;

        }
    }

    /**
     * Remove any redundant parent tags. Using lists as an example, each LI will have open and close
     * parent tags assigned to it.
     *
     * @return void
     */
    private function removeRedundantParentTags()
    {
        $existing_content = $this->content;
        $this->content = array();
        foreach ($existing_content as $k => $content) {
            if ($k !== 0 &&
                array_key_exists('tags', $content) === true &&
                count($content['tags']) === 1 &&
                array_key_exists('parent_tags', $content['tags'][0]) === true) {
                /**
                 * Check the preview content item, if it has parent tags, remove the close and
                 * remove the parent tag open from the current item before assigning back to the
                 * new content array
                 */
                if (array_key_exists('tags', $this->content[($k-1)]) === true &&
                    count($this->content[($k-1)]['tags']) === 1 &&
                    array_key_exists('parent_tags', $this->content[($k-1)]['tags'][0]) === true) {

                    $this->content[($k-1)]['tags'][0]['parent_tags']['close'] = null;
                    $content['tags'][0]['parent_tags']['open'] = null;
                }
            }

            $this->content[] = $content;
        }
    }
}
