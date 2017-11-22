<?php
/**
 * 采集esbn球员大头像  并入库
 *
 */
class updatePlayerBigPhotoTask extends sfBaseTask
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

        $this->namespace = 'gamespace';
        $this->name = 'updatePlayerBigPhoto';
        $this->briefDescription = '采集球员大头像';
        $this->detailedDescription = <<<EOF
The [gamespace:updatePlayerBigPhoto|INFO] task does things.
Call it with:

  [php symfony gamespace:updatePlayerBigPhoto|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        set_time_limit(0);
        sfContext::createInstance($this->configuration);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $taskConfig = sfConfig::get('app_tasks');


        $players = HoopPlayerTable::getInstance()->createQuery()
           // ->where('team_id !=0')

            ->execute();

        foreach ($players as $player) {

            $espnId = $player->getEspnId();
            $uploadDir = sfConfig::get('sf_upload_dir') . '/gamespace/players/' . (($player->getId()) % 10); //上传目录 不带斜杠

            $fileName = $player->getId() . '_' . rand(100, 999) . '_big.jpg';

            $newFile = $uploadDir . '/' . $fileName; //  绝对地址 1_xxx_big.jpg


            $fileUri = '/' . basename(sfConfig::get('sf_upload_dir')) . '/gamespace/players/' . (($player->getId()) % 10) . '/' . $fileName;

            if (!empty($espnId)) {


                $data = common::Curl('http://a.espncdn.com/combiner/i?img=/i/headshots/nba/players/full/' . $espnId . '.png&w=350&h=254');


                $source = @imagecreatefromstring($data);


                if ($source !== false) {

                    $w = imagesx($source);
                    $h = imagesy($source);
                    $white = imagecreatetruecolor($w, $h);
                    $bg = imagecolorallocate($white, 255, 255, 255);
                    imagefill($white, 0, 0, $bg);
                    imagecopyresized($white, $source, 0, 0, 0, 0, $w, $h, $w, $h);

                    $this->RecursiveMkdir(dirname($newFile), 0755);

                    imagejpeg($white, $newFile);

                    imagedestroy($source);
                    imagedestroy($white);

                    $player->setBigPhoto($fileUri);
                    $player->save();


                } else {

                    $teamMsg = $this->getTeamMsg($player);
                    $msg = sprintf('%-5d%-40s%-20s%-20s', $player->getId(), $player->getName(), $teamMsg, 'espn error');


                    //$msg = $player->getId() . ' ' . $player->getName() . ' ' . $teamMsg . '  espn错误';
                    $this->log($msg);


                }


            } else {
                $teamMsg = $this->getTeamMsg($player);

                $msg = sprintf('%-5d%-40s%-20s%-20s', $player->getId(), $player->getName(), $teamMsg, 'no espnid');
                //$msg = $player->getId() . ' ' . $player->getName() . ' ' . $teamMsg . ' 没有espnid';

                $this->log($msg);
            }
        }

    }


    public function  RecursiveMkdir($path, $mode)
    {

        if (!file_exists($path)) { // The file is not exist.
            $this->RecursiveMkdir(dirname($path), $mode); // Call itself.
            if (mkdir($path, $mode)) { // Call mkdir() to create the last directory, and the result is true.
                return true;
            } else { // Call mkdir() to create the last directory, and the result is false.
                return false;
            }
        } else { // The file is already exist.
            return true;
        }
    }

    public function getTeamMsg($player)
    {
        if ($taemId = ($player->getTeamId()) == 0) {

            return $teamName = 'no team';
        } else {
            $team = HoopTeamTable::getOne($player->getTeamId());
            return $teamName = $team->getName();
        }

    }


}