<?php

class myTagFilter extends sfFilter
{
    public function execute ($filterChain)
    {
        // execute this filter only once

        // execute next filter
        $filterChain->execute();
    }
}

?>