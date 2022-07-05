<?php

namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use Grav\Common\Page\Page;

/**
 * Class ShortcodeGallery
 * @package Grav\Plugin
 */
class ShortcodeGalleryPlugin extends Plugin
{
    const SLUG = 'shortcode-gallery';
    private $currentPage = null;

    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized'  => ['onPluginsInitialized', 0],
            'onShortcodeHandlers'   => ['onShortcodeHandlers', 0],
            'onTwigTemplatePaths'   => ['onTwigTemplatePaths', 0],
        ];
    }

    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
            $this->enable([
                'onAssetsInitialized' => ['onAssetsInitialized', 0],
            ]);
        }
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Initialize configuration
     */
    public function onShortcodeHandlers()
    {
        $this->grav['shortcode']->registerAllShortcodes(__DIR__ . '/shortcodes');
    }

    /**
     * [onAssetsInitialized]
     *
     * @return void
     */
    public function onAssetsInitialized()
    {
        $page = $this->grav['admin']->page();
        if( $page->isPage() )
        // $this->grav['debugger']->addMessage( $page->published() );
        {
            // $trigger = $this->config->get('plugins.' . self::SLUG . '.route');
            $assets = $this->grav['assets'];
            // $assets->addInlineJs( 'const draft_preview_route = "' . $trigger . '";' );
            $assets->addCss( 'plugin://' . self::SLUG . '/assets/gallery.css' );
            $assets->addCss( 'plugin://' . self::SLUG . '/assets/editor.css' );
        }
    }
}
