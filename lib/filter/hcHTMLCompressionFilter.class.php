<?php

class hcHTMLCompressionFilter extends sfFilter {

//    public function initialize($context, $parameters = array()) {
//
//    }
    protected $compress_css = true;
    protected $compress_js = false;
    protected $info_comment = true;
    protected $remove_comments = true;
    // Variables
    protected $html;

    public function execute($filterChain) {
        $filterChain->execute();
        $start = microtime(true);
        $size_before_compression = strlen($this->context->getResponse()->getContent());
        if ($size_before_compression > 0) {
            $compress_content = $this->minifyHTML($this->context->getResponse()->getContent());

            $size_after_compression = strlen($compress_content);
            $savings = ($size_before_compression - $size_after_compression) / $size_before_compression * 100;
            $savings = round($savings, 2);

            $this->context->getResponse()->setContent($compress_content);
            sfContext::getInstance()->getLogger()->info('hcHTMLCompressionFilter::this document by ' . $savings . '% but costs ' . (round((microtime(true) - $start), 5) * 1000) . ' millisecond . The file was ' . $size_before_compression . ' bytes, but is now ' . $size_after_compression . ' bytes');
        }
    }

    protected function minifyHTML($html) {
        $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';

        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);

        $overriding = false;
        $raw_tag = false;

        // Variable reused for output
        $html = '';

        foreach ($matches as $token) {
            $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

            $content = $token[0];

            if (is_null($tag)) {
                if (!empty($token['script'])) {
                    $strip = $this->compress_js;
                } else if (!empty($token['style'])) {
                    $strip = $this->compress_css;
                } else if ($content == '<!--wp-html-compression no compression-->') {
                    $overriding = !$overriding;

                    // Don't print the comment
                    continue;
                } else if ($this->remove_comments) {
                    if (!$overriding && $raw_tag != 'textarea') {
                        // Remove any HTML comments, except MSIE conditional comments
                        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
                    }
                }
            } else {
                if ($tag == 'pre' || $tag == 'textarea') {
                    $raw_tag = $tag;
                } else if ($tag == '/pre' || $tag == '/textarea') {
                    $raw_tag = false;
                } else {
                    if ($raw_tag || $overriding) {
                        $strip = false;
                    } else {
                        $strip = true;
                        // Remove any empty attributes, except:
                        // action, alt, content, src
                        $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
                        // Remove any space before the end of self-closing XHTML tags
                        // JavaScript excluded
                        $content = str_replace(' />', '/>', $content);
                    }
                }
            }
            if ($strip) {
                $content = $this->removeWhiteSpace($content);
            }
            $html .= $content;
        }
        return $html;
    }

    protected function removeWhiteSpace($str) {
        $str = str_replace("\t", ' ', $str);
        $str = str_replace("\n", '', $str);
        $str = str_replace("\r", '', $str);

        while (stristr($str, '  ')) {
            $str = str_replace('  ', ' ', $str);
        }

        return $str;
    }

}