<?php
/*爬虫*/
class newsAction extends sfAction
{

    public function execute($request)
    {
        $count  = 5000;
        $page_num = 50;

        $page = $request->getParameter('page',1);
        $page = ($page < 1) ? 1 : $page;
        $offset = ($page - 1)*$page_num;

        $this->youhui = TrdNewsTable::getInstance()->createQuery()->select('id,publish_date')->orderBy('publish_date desc')->offset($offset)->limit(60)->execute();
        $this->daigou = TrdProductAttrTable::getInstance()->createQuery()->select('id,publish_date')->orderBy('publish_date desc')->offset($offset)->limit(60)->execute();
        $this->find  =  TrdItemAllTable::getInstance()->createQuery()->select('id,publish_date')->orderBy('publish_date desc')->offset($offset)->limit(60)->execute();

        //分页配置
        $page_params = array(
            'total_rows'=>$count,
            'method'    =>'html',
            'parameter' =>"http://www.shihuo.cn/api/news/page/*",
            'now_page'  =>$page,
            'list_rows' =>$page_num, #(可选) 默认为15
        );
        $Core_Lib_Page = new Core_Lib_Page($page_params);
        $this->page = $Core_Lib_Page->show(1);
    }
}