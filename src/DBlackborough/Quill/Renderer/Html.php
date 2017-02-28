<?php

namespace DBlackborough\Quill\Renderer;

/**
 * Quill renderer, converts quill delta inserts into html
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Html extends \DBlackborough\Quill\Renderer
{
    /**
     * @var string
     */
    protected $html;

    /**
     * Renderer constructor.
     *
     * @param array @options Options data array, if empty default options are used
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

    }

    /**
     * Default options
     *
     * @return array
     */
    protected function defaultOptions()
    {
        return array(
            'attributes' => array(
                'bold' => 'strong',
                'italic' => 'em',
                'underline' => 'u',
                'strike' => 's',
                'list' => array(
                    'bullet' => array('ul', 'li'),
                    'ordered' => array('ol', 'li')
                )
            ),
            'container' => 'p',
            'newline' => 'br'
        );
    }

    /**
     * Get attribute tag(s)
     *
     * @param string $attribute
     * @param string $value
     *
     * @return string|null
     */
    protected function getAttributeTag($attribute, $value)
    {
        switch ($attribute)
        {
            case 'bold':
            case 'italic':
            case 'underline':
            case 'strike':
                return $this->options['attributes'][$attribute];
                break;

            case 'list':
                return $this->options['attributes']['list'][$value];
                break;

            default:
                // Do nothing, valid already set to false
                return null;
                break;
        }
    }

    /**
     * Convert new lines into containers and newlines
     *
     * @param string $subject
     * @param integer $i Content array index
     * @return array Two indexes, subject and tags
     */
    protected function convertNewlines($subject, $i)
    {
        $tags = array();

        if (preg_match("/[\n]{2,} */", $subject) === true) {
            $tags[] = array(
                'open' => null,
                'close' => '</' . $this->options['container'] . '>'
            );
            $tags[] = array(
                'open' => '<' . $this->options['container'] . '>',
                'close' => null,
            );

        }
        $subject = preg_replace("/[\n]{2,} */", '</' . $this->options['container'] . '><' . $this->options['container'] . '>', $subject);

        $closing_block_element = false;
        if ($i > 0) {
            $previous_tags = $this->content[$i-1]['tags'];
            foreach ($previous_tags as $tag) {
                if ($tag['close'] === '</ul>' || $tag['close'] === '</ol>') {
                    $closing_block_element = true;
                }
            }
        }

        if ($closing_block_element === false) {
            $subject = preg_replace("/[\n]{1}/", '<' . $this->options['newline'] . ' />', $subject);
        } else {
            $subject = preg_replace("/[\n]{1}/", '<' . $this->options['container'] . '>', rtrim($subject), 1);
        }

        return array(
            'tags' => $tags,
            'subject' => $subject
        );
    }

    /**
     * @return string
     */
    protected function parseDeltas()
    {
        if ($this->json_valid === true && array_key_exists('ops', $this->deltas) === true) {

            $inserts = count($this->deltas['ops']);

            $i = 0;

            $list = false;
            $list_item = 0;

            foreach ($this->deltas['ops'] as $k => $insert) {

                $this->content[$i] = array(
                    'content' => null,
                    'tags' => array()
                );

                $tags = array();
                $has_tags = false;

                if (array_key_exists('attributes', $insert) === true && is_array($insert['attributes']) === true) {
                    foreach ($insert['attributes'] as $attribute => $value) {
                        if ($this->isAttributeValid($attribute, $value) === true) {
                            $tag = $this->getAttributeTag($attribute, $value);
                            if ($tag !== null) {
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
                        if (is_array($tag) === false) {
                            $this->content[$i]['tags'][] = array(
                                'open' => '<' . $tag . '>',
                                'close' => '</' . $tag . '>'
                            );
                        } else {
                            if ($tag[1] === 'li') {
                                if ($list === false) {
                                    $list = true;
                                    $list_item = 0;
                                }

                                if ($list_item === 0) {
                                    $this->content[$i-1]['tags'][] = array(
                                        'open' => '<' . $tag[0] . '>',
                                        'close' => null
                                    );
                                }
                            }
                            $this->content[$i-1]['tags'][] = array(
                                'open' => '<' . $tag[1] . '>',
                                'close' => '</' . $tag[1] . '>'
                            );

                            // Remove previous closing tag if exists
                            if($i > 1) {
                                $previous_tags = $this->content[$i - 2]['tags'];
                                $new_previous_tags = array();
                                foreach ($previous_tags as $previous_tag_item) {
                                    if (array_key_exists('close', $previous_tag_item) === true &&
                                        $previous_tag_item['close'] !== '</' . $tag[0] . '>'
                                    ) {
                                        $new_previous_tags = $previous_tag_item;
                                    }
                                }

                                $this->content[$i - 2]['tags'] = $new_previous_tags;
                            }

                            // Add closing tag to list, removed if we loop again
                            $this->content[$i]['tags'][] = array(
                                'open' => null,
                                'close' => '</' . $tag[0] . '>'
                            );

                            if ($list === true) {
                                $list_item++;
                            }
                        }
                    }
                }

                if (array_key_exists('insert', $insert) === true && strlen(trim($insert['insert'])) > 0) {
                    $content = $this->convertNewlines($insert['insert'], $i);
                    if (count($content['tags']) > 0) {
                        foreach($content['tags'] as $tag) {
                            $this->content[$i]['tags'][] = $tag;
                        }
                    }
                    $this->content[$i]['content'] = $content['subject'];
                }

                if ($k === ($inserts-1)) {
                    $this->content[$i]['content'] = rtrim($this->content[$i]['content'], '<' . $this->options['newline'] . ' />');
                }

                $i++;
            }

            if (count($this->content) > 0) {

                // Check to see if first item a block element, if not add container tag
                $this->firstItemBlockElement();

                $this->LastItemBlockElement();
            }
        }

        $this->content_valid = true;
    }

    /**
     * Generate the html
     *
     * @return string
     */
    public function render()
    {
        $this->parseDeltas();

        if ($this->content_valid === true) {
            foreach ($this->content as $content) {
                foreach ($content['tags'] as $tag) {
                    if (array_key_exists('open', $tag) === true && $tag['open'] !== null) {
                        $this->html .= $tag['open'];
                    }
                }

                $this->html .= $content['content'];

                foreach (array_reverse($content['tags']) as $tag) {
                    if (array_key_exists('close', $tag) === true && $tag['close'] !== null) {
                        $this->html .= $tag['close'];
                    }
                }
            }
        }

        return $this->html;
    }

    /**
     * Check to see if the last content item is a block element, if not add container
     */
    protected function lastItemBlockElement()
    {
        $last_item = count($this->content) - 1;
        $assigned_tags = $this->content[$last_item]['tags'];
        $block = false;

        if (count($assigned_tags) > 0) {
            foreach ($assigned_tags as $assigned_tag) {
                /**
                 * @todo This should check the tags defined in list options, not ul and ol directly
                 */
                if ($assigned_tag['close'] === '</ol>' || $assigned_tag['close'] === '</ul>') {
                    $block = true;
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
                'close' => '</' . $this->options['container'] . '>',
            );
        }
    }

    /**
     * Check to see if the ifrst content item is a block element, if not add container
     */
    protected function firstItemBlockElement()
    {
        $assigned_tags = $this->content[0]['tags'];
        $block = false;

        if (count($assigned_tags) > 0) {
            foreach ($assigned_tags as $assigned_tag) {
                /**
                 * @todo This should check the tags defined in list options, not ul and ol directly
                 */
                if ($assigned_tag['open'] === '<ol>' || $assigned_tag['open'] === '<ul>') {
                    $block = true;
                }
            }
        }

        if ($block === false) {
            $this->content[0]['tags'][] = array(
                'open' => '<' . $this->options['container'] . '>',
                'close' => null
            );
            foreach ($assigned_tags as $assigned_tag) {
                $this->content[0]['tags'][] = $assigned_tag;
            }
        }
    }
}
