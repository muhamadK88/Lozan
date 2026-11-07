<?php

namespace App\Support;

use HTMLPurifier;
use HTMLPurifier_Config;

final class HtmlSanitizer
{
    private ?HTMLPurifier $purifier = null;

    /**
     * Clean rich-text editor output before it is stored or rendered.
     *
     * HTMLPurifier removes scripts, event handler attributes, javascript:
     * URLs, dangerous CSS and anything outside the explicit allow-list.
     * YouTube/Vimeo embeds are permitted through HTML.SafeIframe.
     */
    public function clean(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        return $this->purifier()->purify($html);
    }

    private function purifier(): HTMLPurifier
    {
        if ($this->purifier instanceof HTMLPurifier) {
            return $this->purifier;
        }

        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', '%^https?://(www\.)?(youtube\.com/embed/|youtube-nocookie\.com/embed/|player\.vimeo\.com/video/)%');
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
            'mailto' => true,
        ]);
        $config->set('HTML.Allowed', implode(',', [
            'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's',
            'ul', 'ol', 'li', 'blockquote',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'a[href|title|target|rel]',
            'img[src|alt|title|width|height|loading]',
            'iframe[src|width|height|frameborder|allow|allowfullscreen|title]',
            'div', 'span',
            'table', 'thead', 'tbody', 'tr', 'th', 'td',
            'hr', 'pre', 'code',
        ]));
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        $config->set('HTML.TargetBlank', true);
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('URI.Base', (string) config('app.url'));

        // HTMLPurifier targets HTML 4.01 by default, which does not know the
        // HTML5 attributes emitted by the rich-text editor. Registering them
        // on a custom definition keeps the allow-list strict while allowing
        // lazy-loaded images and full-screen YouTube embeds.
        $config->set('HTML.DefinitionID', 'lozan-article-html');
        $config->set('HTML.DefinitionRev', 1);

        if ($definition = $config->maybeGetRawHTMLDefinition()) {
            $definition->addAttribute('img', 'loading', 'Enum#lazy,eager');
            $definition->addAttribute('iframe', 'allow', 'Text');
            $definition->addAttribute('iframe', 'allowfullscreen', 'Bool#allowfullscreen');
        }

        return $this->purifier = new HTMLPurifier($config);
    }
}
