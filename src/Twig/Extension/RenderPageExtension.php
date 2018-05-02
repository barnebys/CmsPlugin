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
use BitBag\CmsPlugin\Repository\PageRepositoryInterface;
use BitBag\CmsPlugin\Repository\BlockRepositoryInterface;
use \Sylius\Component\Locale\Context\LocaleContextInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 */
final class RenderPageExtension extends \Twig_Extension
{
    const PAGE_CONTENT_TEMPLATE = 'BitBagCmsPlugin:Page:content.html.twig';

    const BLOCK_PATTERN = '/\[([\w-_]+)([^\]]*)?\](?:(.+?)?\[\/\1\])?/';

    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var LoggerInterface
     */
    private $logger;


    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * RenderPageExtension constructor.
     * @param PageRepositoryInterface $pageRepository
     * @param BlockRepositoryInterface $blockRepository
     * @param LocaleContextInterface $localeContext
     * @param LoggerInterface $logger
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        BlockRepositoryInterface $blockRepository,
        LocaleContextInterface $localeContext,
        LoggerInterface $logger
    )
    {

        $this->locale = $localeContext->getLocaleCode();
        $this->pageRepository = $pageRepository;
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
            new \Twig_SimpleFunction('bitbag_render_page', [$this, 'renderPage'], ['needs_environment' => true, 'is_safe' => ['html'],]),
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

                if(empty($v)) {
                    return false;
                }

                $params = array_map(function($v) {
                    [$key, $value] = explode("=", $v);
                    return [$key => str_replace(['"', '\'',], '', $value)];
                }, explode(" ", trim($matches[2])));

                return $block->renderBlock($twigEnvironment, $matches[1], $params);
            },
            $content
        );

        return $content;
    }

    public function renderPage(\Twig_Environment $twigEnvironment, $code)
    {

        $page = $this->pageRepository->findOneByLocaleAndCode($this->locale, $code);

        if (false === $page instanceof PageInterface) {

            $this->logger->warning(sprintf(
                'Page with "%s" code was not found in the database.',
                $code
            ));

            return null;
        }

        return $twigEnvironment->render(self::PAGE_CONTENT_TEMPLATE, ['resource' => $page]);
    }
}