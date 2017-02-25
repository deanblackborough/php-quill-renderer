<?php

namespace DBlackborough\Quill;

/**
 * Quill renderer, converts quill delta inserts into html
 *
 * @todo Validate options
 * @todo Validate $deltas
 * @todo Tests for each attribute
 * @todo Tests for each container supported
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Renderer
{
    /**
     * Delta inserts
     *
     * @var array
     */
    private $deltas;

    /**
     * Valid inserts json array
     *
     * @param boolean
     */
    private $json_valid = false;

    /**
     * Options data array
     *
     * @param array
     */
    private $options = array();

    /**
     * @var string
     */
    private $html;

    /**
     * Renderer constructor.
     *
     * @param array @options Options data array, if empty default options are used
     */
    public function __construct(array $options = array())
    {
        if (count($options) === 0) {
            $options = $this->defaultOptions();
        }

        $this->setOptions($options);
    }

    /**
     * Default options
     *
     * @return array
     */
    private function defaultOptions()
    {
        return array(
            'attributes' => array(
                'bold' => 'strong',
                'italic' => 'em',
                'underline' => 'u',
                'strike' => 's'
            ),
            'container' => 'p',
            'newline' => 'br'
        );
    }

    /**
     * Default options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the options for the renderer
     *
     * @param array $options
     * @return \DBlackborough\Quill\Renderer
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param string $deltas JSON inserts string
     *
     * @return boolean
     */
    public function load($deltas)
    {
        $this->deltas = json_decode($deltas, true);

        if ($this->deltas !== null && count($this->deltas) > 0) {
            $this->json_valid = true;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Convert new lines
     *
     * @param string $subject
     * @return string
     */
    private function convertNewlines($subject)
    {
        $patterns = array(
            "/[\n]{2,}/",
            "/[\n]{1}/"
        );
        $replacements = array(
            '</' . $this->options['container'] . '><' . $this->options['container'] . '>',
            '<' . $this->options['newline'] . '/>',
        );

        return preg_replace($patterns, $replacements, $subject);
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $this->html = null;

        if ($this->json_valid === true && array_key_exists('ops', $this->deltas) === true) {

            $inserts = count($this->deltas['ops']);

            foreach ($this->deltas['ops'] as $k => $insert) {

                /*echo $k;
                var_dump($insert);*/

                if ($k === 0) {
                    $this->html .= '<' . $this->options['container'] . '>';
                }

                if (array_key_exists('insert', $insert) === true) {
                    $this->html .= $this->convertNewlines($insert['insert']);
                }

                if ($k === ($inserts-1)) {
                    $this->html = rtrim($this->html, '<' . $this->options['newline'] . '/>');
                    $this->html .= '</' . $this->options['container'] . '>';
                }
            }
        }

        return $this->html;
    }
}
