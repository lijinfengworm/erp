<?php

class Release101 extends Doctrine_Migration_Base
{
  public function up()
  {
      $this->addColumn('hoopPlayers', 'espn_id', 'integer');
      $this->addColumn('hoopPlayers', 'espn_name', 'string', 100);
  }

  public function down()
  {
      $this->removeColumn('hoopPlayers', 'espn_name');
      $this->removeColumn('hoopPlayers', 'espn_id');
  }
}
