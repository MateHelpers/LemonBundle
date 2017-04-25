<?php
/**
 * This file is part of the MateLemonBundle package.
 *
 * (c) Mohamed Radhi Guennichi <https://www.mate.tn> <https://github.com/MateHelpers/LemonBundle>
 *
 * Email: rg@mate.tn - contact@mate.tn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mate\LemonBundle\Generator;


use Mate\LemonBundle\Element\ElementInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

abstract class GeneratorAbstraction
{
    /** @var array */
    protected $pattern = array();

    /** @var ElementInterface */
    protected $element;

    /** @var string */
    private $content;

    /**
     * @return array
     */
    abstract protected function buildPattern();

    final public function build(Filesystem $filesystem)
    {
        $this->buildPattern();

        if (!$this->pattern) {
            throw new \RuntimeException('You should define a pattern to generate your file.');
        }

        $this->generateContent();
        $this->generateFile($filesystem);
    }

    final private function generateContent()
    {
        // get Template file, then replace content using the pattern ex:
        // $content = generate($template, $pattern)
        $this->content = $this->element->getTemplate();

        if (!$this->content) {
            throw new \RuntimeException('The template file is empty or not found, please check the content.');
        }

        foreach ($this->pattern as $key => $value) {
            $keyToReplace = '<' . $key . '>';
            $this->content = str_replace($keyToReplace, $value, $this->content);
        }
    }

    final private function generateFile(Filesystem $filesystem)
    {
        try {
            $filesystem->dumpFile($this->element->getPath(), $this->content);
        } catch (IOException $e) {
            throw new \RuntimeException(sprintf('Error while writing file.'));
        }
    }
}