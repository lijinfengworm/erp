<?php
Class daigouMarketSearch
{
    /*索引创建*/
    public function create($id)
    {
        $daigouSearch = new daigouSearch();
        $daigouSearch->update($id);
    }


    /*索引更新*/
    public function update($id)
    {
        $daigouSearch = new daigouSearch();
        $daigouSearch->update($id);
    }

    /*索引删除*/
    public function delete($id)
    {
        $daigouSearch = new daigouSearch();
        $daigouSearch->update($id);
    }
}
