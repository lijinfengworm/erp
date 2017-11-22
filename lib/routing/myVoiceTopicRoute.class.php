<?php
/*
 * 
 */
class myVoiceTopicRoute extends sfRoute {

    /**
     * Compiles the current route instance.
     */
    protected function compileForVariable($separator, $name, $variable) {
        if (!isset($this->requirements[$variable])) {
            $this->requirements[$variable] = $this->options['variable_content_regex'];
        }
        $slugs = liangleMemcache::getVoiceTopicSlugs();
        $reg_str = '';
        foreach($slugs as $v){
            $reg_str .= $reg_str == '' ? $v['slug'] : '|'.$v['slug'];
        }
        $this->requirements['slug'] = '('. $reg_str .')';

        $this->segments[] = preg_quote($separator, '#') . '(?P<' . $variable . '>' . $this->requirements[$variable] . ')';
        $this->variables[$variable] = $name;

        if (!isset($this->defaults[$variable])) {
            $this->firstOptional = count($this->segments);
        }
    }

    public function compile() {
        if ($this->compiled) {
            return;
        }

        $this->initializeOptions();
        $this->fixRequirements();
        $this->fixDefaults();
        $this->fixSuffix();

        $this->compiled = true;
        $this->firstOptional = 0;
        $this->segments = array();

        $this->preCompile();

        $this->tokenize();

        // parse
        foreach ($this->tokens as $token) {
            call_user_func_array(array($this, 'compileFor' . ucfirst(array_shift($token))), $token);
        }

        $this->postCompile();

        $separator = '';
        if (count($this->tokens)) {
            $lastToken = $this->tokens[count($this->tokens) - 1];
            $separator = 'separator' == $lastToken[0] ? $lastToken[2] : '';
        }
        $this->regex = "#^" . implode("", $this->segments) . "" . preg_quote($separator, '#') . "$#is";

    }

}