@props([
    'language',
    'slot',
])
<pre class="highlight-web-component-wrapper"><code>{!!
    (new \Tempest\Highlight\Highlighter())->parse(preg_replace('/<!--(.|\s)*?-->/', '', $slot), $language)
!!}</code></pre>
