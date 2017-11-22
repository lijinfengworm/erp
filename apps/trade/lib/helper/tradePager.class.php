<?php
class tradePager extends sfDoctrinePager {
    public function init()
    {
        $this->resetIterator();

        $query = $this->getQuery();
        $query
            ->offset(0)
            ->limit(0)
            ;   

        if (0 == $this->getPage() || 0 == $this->getMaxPerPage() || 0 == $this->getNbResults())
        {   
            $this->setLastPage(0);
        }   
        else
        {   
            $offset = ($this->getPage() - 1) * $this->getMaxPerPage();

            $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));

            $query
                ->offset($offset)
                ->limit($this->getMaxPerPage())
                ;   
        }   
    }

    public function getNbResults() {
        if(!isset($this->total_results)) {
            $this->total_results = 0;
        }
        return $this->total_results;
    }
    
    public function setNbResults($count = 0) {
        $this->total_results = $count;
    }
}
