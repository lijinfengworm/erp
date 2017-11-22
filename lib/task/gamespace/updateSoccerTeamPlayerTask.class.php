<?php

class updateSoccerTeamPlayerTask extends sfBaseTask
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
        $this->name = 'updateSoccerTeamPlayer';
        $this->briefDescription = '更新足球球队详细信息和球员详细信息';
        $this->detailedDescription = <<<EOF
The [gamespace:updateSoccerTeamPlayer|INFO] task does things.
Call it with:

  [php symfony gamespace:updateSoccerTeamPlayer|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $taskConfig = sfConfig::get('app_tasks');

        $max = SoccerTeamsTable::getInstance()->createQuery()->select("max(id) as max")->fetchArray();

        for ($id = 1; $id < $max[0]['max']; $id++)
        {
            $team = SoccerTeamsTable::getInstance()->find($id);
            if (!$team)
                continue;
            $league = $team->getLeague();
            $teamId = $team->getTeamId();

            $url = soccerInterfaceUrl::getTeamDetail($league, $teamId);
            $str = soccerInterfaceUrl::getContents($url);
            $teamInfo = json_decode($str, TRUE);

            $data = $this->_formatTeamInfo($teamInfo['Team'], $league);
            $team->fromArray($data);
            $team->save();

            $url = soccerInterfaceUrl::getPlayers($teamId);
            $str = soccerInterfaceUrl::getContents($url);
            $playerInfo = json_decode($str, TRUE);

            if (empty($playerInfo['PlayerList']))
                continue;

            $ids = array();
            foreach ($playerInfo['PlayerList'] as $player)
            {
                $data = $this->_formatPlayer($player, $teamId);

                //保存球员信息
                $player = SoccerPlayersTable::getInstance()->find($data['player_id']);
                if ($player)
                {
                    $player->fromArray($data);
                    $player->save();
                } else
                {
                    $player = new SoccerPlayers();
                    $player->fromArray($data);
                    $player->save();
                }
                $ids[] = $data['player_id'];
            }
            //删除非本队球员
            SoccerPlayersTable::getInstance()->createQuery()->delete()->whereNotIn('player_id', $ids)->AndWhere('teamid = ?', $teamId)->execute();
            echo $team->getTeam()."已经更新\n";
        }
    }

  

    /**
     * 格式化球队信息
     * @param type $beitai_data
     * @param type $league
     * @return type
     */
    private function _formatTeamInfo($beitai_data, $league)
    {
        $data = array(
            'team_id' => intval($beitai_data['TeamID']),
            'team' => $beitai_data['TeamCNAlias'],
            'full_name' => $beitai_data['TeamCNName'],
            'team_eng_name' => $beitai_data['TeamENAlias'],
            'eng_full_name' => $beitai_data['TeamENName'],
            'stadium' => $beitai_data['Stadium'],
            'eng_stadium' => $beitai_data['StadiumENName'],
            'found' => $beitai_data['FoundDate'],
            'champions' => $beitai_data['ChampionsNumber'],
            'coach' => $beitai_data['CoachName'],
            'coach_age' => $beitai_data['CoachAge'],
            'coach_year' => $beitai_data['CoachingYear'],
            'league' => $league
        );

        return $data;
    }

    private function _formatPlayer($beitai_info, $team_id)
    {
        $data = array(
            'player_id' => addslashes($beitai_info['PlayerID']),
            'name' => addslashes($beitai_info['PlayerCNAlias']),
            'country' => addslashes($beitai_info['CountryName']),
            'full_name' => addslashes($beitai_info['PlayerCNName']),
            'eng_name' => addslashes($beitai_info['FirstName'] . '.' . $beitai_info['LastName']),
            'number' => intval($beitai_info['Number']),
            'position' => addslashes($beitai_info['Position']),
            'red_card' => intval($beitai_info['RedCards']),
            'yellow_card' => intval($beitai_info['YellowCards']),
            'goal' => intval($beitai_info['Goals']),
            'injury' => intval($beitai_info['InjuryStatus']),
            'teamid' => $team_id,
        );
        return $data;
    }

}
