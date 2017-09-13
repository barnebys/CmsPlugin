<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace BitBag\CmsPlugin\Twig\Extension;

use BitBag\CmsPlugin\Entity\PageInterface;
use BitBag\CmsPlugin\Entity\BlockInterface;
use BitBag\CmsPlugin\Exception\TemplateTypeNotFound;
use BitBag\CmsPlugin\Repository\PageRepositoryInterface;
use BitBag\CmsPlugin\Repository\BlockRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 */
final class RenderPageExtension extends \Twig_Extension
{

    const BLOCK_PATTERN = '/\[([^\]]*)\]/';

    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;


    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @param BlockRepositoryInterface $blockRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        BlockRepositoryInterface $blockRepository,
        LoggerInterface $logger
    )
    {
        $this->blockRepository = $blockRepository;
        $this->logger = $logger;
    }

    /**
     * @param \Twig_Environment $environment
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('bitbag_render_content', [$this, 'renderContent'], ['needs_environment' => true, 'is_safe' => ['html'],]),
        ];
    }

    /**
     * @param \Twig_Environment $twigEnvironment
     * @param string $content
     *
     * @return string|null
     */
    public function renderContent(\Twig_Environment $twigEnvironment, $content)
    {
        $block = new RenderBlockExtension($this->blockRepository, $this->logger);

        $content = preg_replace_callback(
            self::BLOCK_PATTERN,
            function($matches) use ($block, $twigEnvironment) {
                return $block->renderBlock($twigEnvironment, $matches[1]);
            },
            $content
        );

        return $content;
    }
}