<?php

class hcJSCSSCompilerTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            //new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            //new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('jsdir', null, sfCommandOption::PARAMETER_REQUIRED, 'The subdir name'),
            new sfCommandOption('cssdir', null, sfCommandOption::PARAMETER_REQUIRED, 'The csssubdir name'),
            new sfCommandOption(
                    'jscomplevel',
                    null,
                    sfCommandOption::PARAMETER_OPTIONAL,
                    'JS Compilation Levels,could be: WHITESPACE_ONLY | SIMPLE_OPTIMIZATIONS | ADVANCED_OPTIMIZATIONS',
                    'ADVANCED_OPTIMIZATIONS'
            ),
        ));

        $this->namespace = 'hupu';
        $this->name = 'cssjscompiler';
        $this->briefDescription = 'generate minify version of css/js';
        $this->detailedDescription = <<<EOF
The [hoopchina:cssjscompiler|INFO] task does things.
js compiler:

  [php symfony hupu:cssjscompiler --jsdir=/ --cssdir=/|INFO]
  
  [php symfony hupu:cssjscompiler --jsdir=/ --cssdir=/ --jscomplevel=ADVANCED_OPTIMIZATIONS|INFO]
  



EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        //var_dump(sfConfig::getAll());
        //combine js
        if (!empty($options['jsdir'])) {
            $list = sfFinder::type('file')
                    ->not_name('*.min.js')
                    ->name('*.js')
                    ->prune('/tinymce/')
                    ->prune('/plugins/')
                    ->in(sfConfig::get('sf_web_dir') . '/js/' . $options['jsdir']);
            foreach ($list as $k => $v) {
                echo exec('java -jar ' . sfConfig::get('sf_data_dir') . '/bin/compiler.jar'
                        . ' ' . $v
                        . ' --charset=UTF-8'
//                        . ' --compilation_level=' . $options['jscomplevel']
                        . ' --js_output_file='
                        . preg_replace('/^(.*?)\.js/', '${1}.min.js', $v)
                        //.'/tmp/'.$k.'.min.js'
                );
            }
        }

        //combine css
        if (!empty($options['cssdir'])) {
            $list = sfFinder::type('file')
                    ->not_name('*.min.css')
                    ->name('*.css')
                    ->in(sfConfig::get('sf_web_dir') . '/css/' . $options['cssdir']);
            foreach ($list as $k => $v) {
                echo exec('java -jar ' . sfConfig::get('sf_data_dir') . '/bin/yuicompressor-2.4.7.jar'
                        . ' ' . $v
                        . ' -o '
                        . preg_replace('/^(.*?)\.css/', '${1}.min.css', $v)
                        //.'/tmp/'.$k.'.min.css'
                );
            }
        }
    }

}
