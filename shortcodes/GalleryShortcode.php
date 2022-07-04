<?php

namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class GalleryShortcode extends Shortcode
{
    private $pluginName = 'shortcode-gallery';

    public function init()
    {
        // gallery
        $this->shortcode->getHandlers()->add( 'gallery', function ( ShortcodeInterface $shortcode )
        {
            // get default settings
            $pluginConfig = $this->config->get( 'plugins.' . $this->pluginName );

            // get the current page in process (i.e. the page where the shortcode is being processed)
            // warning, it can be different from $this->grav['page'], if ever we browse a collection
            // this is exactly what the Feed plugin does
            $currentPage = $this->grav['plugins']->getPlugin( $this->pluginName )->getCurrentPage();

            // values to check if we are in a feed (RSS, Atom, JSON)
            $type = $this->grav['uri']->extension(); // Get current page extension
            $feed_config = $this->grav['config']->get('plugins.feed');
            $feed_types = array('rss','atom');
            if ($feed_config && $feed_config['enable_json_feed'])
                $feed_types[] = 'json';

            // check if the rendered page will be cached or not
            $renderingCacheDisabled = isset($currentPage->header()->cache_enable)
                                    && !$currentPage->header()->cache_enable
                                    || !$this->grav['config']->get('system.cache.enabled');

            // check if we are in a feed (RSS, Atom, JSON)
            // we also check that the page will not be cached once rendered (otherwise the gallery will not be generated on the normal page)
            if ( $renderingCacheDisabled &&                       // if the current page does not cache its rendering
                $feed_config && $feed_config['enabled'] &&       // and the Feed plugin is enabled
                isset($this->grav['page']->header()->content) && // and the current page has a collection
                $feed_types && in_array($type, $feed_types) ) {  // and the Feed plugin handles it
                return $shortcode->getContent(); // return unprocessed content (because in RSS, Javascripts don't work)
            }


            // overwrite default gallery settings, if set by user
            $rowHeight = $shortcode->getParameter('rowHeight', $pluginConfig['gallery']['rowHeight']);
            $margins = $shortcode->getParameter('margins', $pluginConfig['gallery']['margins']);
            $lastRow = $shortcode->getParameter('lastRow', $pluginConfig['gallery']['lastRow']);
            $captions = $shortcode->getParameter('captions', $pluginConfig['gallery']['captions']);
            $border = $shortcode->getParameter('border', $pluginConfig['gallery']['border']);
            $resizeFactor = $shortcode->getParameter('resizeFactor', $pluginConfig['gallery']['resizeFactor']);


            // find all images, that a gallery contains
            $content = $shortcode->getContent();

            // check validity
            if ( strpos($content, '<pre>' ) !== false )
            {
                $msg = "<p style='padding: 1ex;background: #fff;color: #600; font-weight: 700'>[Shortcode Gallery]:<br>";
                $msg .= $this->twig->processString( '{{ "PLUGIN_SHORTCODE_GALLERY.FORMAT_ERROR"|t
                |e }}' );
                $msg .= '</p>';
                return $msg;
            }

            // remove <p> tags
            // $content = preg_replace('(<p>|</p>)', '', $content);
            $content = strip_tags( $content, '<img>' );
            // split up images to arrays of img links
            preg_match_all( '|<img.*?>|', $content, $images );

            $images_final = [];
            foreach ( $images[0] as $image ) {
                // get src attribute
                preg_match( '|src="(.*?)"|', $image, $links );

                // get alt attribute
                preg_match( '|alt="(.*?)"|', $image, $alts );

                // get title attribute - and strip html from it
                // e.g.:    "<strong>Title 1</strong><br />Example 1<br/>More description<br>Bla bla"
                // becomes: "Title 1 | Example 1 | More description | Bla bla"
                preg_match('/title="(.*?)"/', $image, $titles);
                if (!empty($titles)) {
                    // replace br tags with " | "
                    $title_clean = preg_replace('/<br *\/*>/', ' | ', html_entity_decode($titles[1]));
                    // strip html
                    $title_clean = strip_tags(html_entity_decode($title_clean));
                    // set as new title
                    $image = preg_replace('/title=".*?"/', "title=\"$title_clean\"", $image);
                } else {
                    $titles[1] = null;
                }

                // combine
                array_push($images_final, [
                    // full
                    "image" => $image,
                    "src" => $links[1],
                    "alt" => $alts[1],
                    "title" => $titles[1],
                    ]);
            }

            // add JS and CSS if enabled
            if ( $pluginConfig['built_in_css'] )
            {
                $this->shortcode->addAssets('css', 'plugin://shortcode-gallery/assets/gallery.css');
            }
            if ( $pluginConfig['built_in_js'] )
            {
                $this->shortcode->addAssets('js', 'plugin://shortcode-gallery/assets/gallery.js');
            }

            return $this->twig->processTemplate('partials/gallery.html.twig', [
                'page' => $this->grav['page'], // used for image resizing
                // gallery settings
                'rowHeight' => $rowHeight,
                'margins' => $margins,
                'lastRow' => $lastRow,
                'captions' => $captions,
                'border' => $border,
                'resizeFactor' => $resizeFactor,
                // images
                'images' => $images_final,
            ]);
        });
    }

}
