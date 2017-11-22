<?php
/**
 *  更新当天比赛用户预测结果
 * 
 */
class updateUserPredictInfoTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'gamespace'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'gamespace'),
      // add your own options here
    ));

    $this->namespace        = 'gamespace';
    $this->name             = 'updateUserPredictInfo';
    $this->briefDescription = '更新当天比赛用户预测结果';
    $this->detailedDescription = <<<EOF
The [gamespace:updateUserPredictInfo|INFO] task does things.
Call it with:

  [php symfony gamespace:updateUserPredictInfo|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);
        Doctrine_Manager::getInstance()->setCurrentConnection('gamespace');
        $connection = Doctrine_Manager::connection();

        $sql='select id, (home_score > away_score ) as win from hoopMatches where status=1  and season =2013 and china_time > curdate()';

        $statement = $connection->execute($sql);
        $info =  $statement->fetchAll(Doctrine_Core::FETCH_ASSOC);
        //var_dump($info);
        if(!empty($info)){
            foreach($info as $v){
                if($v['win']){
                    //home win
                    $sql = 'update hoopMatchPredicts set status= vote_team_id where match_id= '. $v['id'] ;
                }else{
                    $sql = 'update hoopMatchPredicts set status= 3-vote_team_id  where match_id= '. $v['id'];
                }

                $statement = $connection->execute($sql);
                usleep(10000);
                //var_dump($statement);
            }
        }





    }



}