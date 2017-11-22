<?php
class tradeAddDescriptionAndPicTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'AddDescriptionAndPicTask';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:AddDescriptionAndPicTask|INFO] task does things.
Call it with:

  [php symfony trade:AddDescriptionAndPicTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);
        set_time_limit(0);
        $time_start = time();
        $trdItemAll = TrdItemAllTable::getInstance();
        $items = $trdItemAll->createQuery()
            ->select('id, item_id')
            ->where('children_id = 8')
            ->addwhere('mart = "淘宝"')
            ->addOrderBy('publish_date desc')
            ->limit(1000)
            ->execute();
        foreach ($items as $item) {
            $id = $item->getId();
            if (true) {
                $taobaoId = $item->getItemId();
                echo $id, '==', $taobaoId, PHP_EOL;
                $url = 'http://hws.m.taobao.com/cache/wdetail/5.0/?id=' . $taobaoId;
                $result = file_get_contents($url);
                $content = json_decode($result, true);
                if ('SUCCESS::调用成功' == $content['ret'][0]) {
                    $fullDescUrl = $content['data']['descInfo']['fullDescUrl'];
                    $fullDesc = file_get_contents($fullDescUrl);
                    $fullDescRes = json_decode($fullDesc, true);
                    if ('SUCCESS::接口调用成功' == $fullDescRes['ret'][0]) {
                        $data = $fullDescRes['data']['desc'];
                        $data = str_replace(array('<html>', '</html>', '<head></head>', '<body>', '</body>'), '', $data);
                        $trdItemAll->createQuery()->update()
                            ->set('description', '?', trim($data))
                            ->where('id = ?', $id)
                            ->execute();
                        TrdBaoliaoTable::getInstance()->createQuery()->update()
                            ->set('description', '?', trim($data))
                            ->where('item_id = ?', $taobaoId)
                            ->execute();
                    }
                }
            }
        }
        $time_end = time();
        $time = $time_end - $time_start;
        echo "time_cost: {$time}s \r\n";
        unset($clients);
        exit;
    }
}