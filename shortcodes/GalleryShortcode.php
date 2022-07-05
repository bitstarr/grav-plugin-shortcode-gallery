<?php

namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class GalleryShortcode extends Shortcode
{
    private $pluginName = 'shortcode-gallery';

    public function init()
    {
        // gallery
        $this->shortcode->getHandlers()->add( 'gallery', function ( ShortcodeInterface $sc )
        {
            // get default settings
            $pluginConfig = $this->config->get( 'plugins.' . $this->pluginName );

            // overwrite default gallery settings, if set by user
            $type = $sc->getParameter( 'type', $pluginConfig['default'] );
            $type = ( isset( $pluginConfig[$type] ) ) ? $type : $pluginConfig['default'];
            $settings = [
                'type' => $type,
                'link' => $sc->getParameter( 'link', $pluginConfig[$type]['link'] ),
                'captions' => $sc->getParameter( 'captions', $pluginConfig[$type]['captions'] ),
                'thumb_width' => $sc->getParameter( 'thumb_width', $pluginConfig[$type]['thumb_width'] ),
                'thumb_height' => $sc->getParameter( 'thumb_height', $pluginConfig[$type]['thumb_height'] ),
                'target_width' => $sc->getParameter( 'target_width', $pluginConfig['target_width'] ),
                'target_height' => $sc->getParameter( 'target_height', $pluginConfig['target_height'] ),
                'class' => $sc->getParameter( 'class' ),
            ];

            $settings['link'] = filter_var( $settings['link'], FILTER_VALIDATE_BOOLEAN );
            $settings['captions'] = filter_var( $settings['captions'], FILTER_VALIDATE_BOOLEAN );


            // find all images, that a gallery contains
            $content = $sc->getContent();

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
                $this->shortcode->addAssets('css', 'plugin://' . $this->pluginName . '/assets/gallery.css');
            }
            if ( $pluginConfig['built_in_js'] )
            {
                $this->shortcode->addAssets('js', [
                    'plugin://' . $this->pluginName . '/vendor/chocolat/chocolat.min.js',
                        [
                            'loading' => 'defer'
                        ]
                    ]
                );
                $this->shortcode->addAssets('js', [
                    'plugin://' . $this->pluginName . '/assets/gallery.js',
                        [
                            'loading' => 'defer'
                        ]
                    ]
                );
                $this->shortcode->addAssets('css', 'plugin://' . $this->pluginName . '/vendor/chocolat/chocolat.css', );
            }

            return $this->twig->processTemplate('partials/sc-gallery-' . $type . '.html.twig', [
                'page' => $this->grav['page'], // used for image resizing
                // gallery settings
                'settings' => $settings,
                // images
                'images' => $images_final,
            ]);
        });
    }
}
