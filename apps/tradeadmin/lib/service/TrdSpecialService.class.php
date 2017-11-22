<?php
/**
 * 专题逻辑服务
 */
class TrdSpecialService  {


    private $form = array();

    //表名
    private $table = 'trd_special';

    public $_var = array();
    //插入的数组
    private $bind = array();

    private $create = array();

    private $error_flag = false;

    private $model = null;
    /**
     * 连接后台用户服务
     * @staticvar \Admin\Service\Cache $systemHandier
     */
    static public function getInstance() {
        static $handier = NULL;
        if (empty($handier)) {
            $handier = new self();
        }
        return $handier;
    }


    /**
     * 添加 form
     */
    public function addForm($key,$val) {
        if(empty($this->form[$key])) {
            $this->form[$key] = $val;
        }
    }


    /**
     * 添加专题
     */
    public function addSpecial($type,$request,$find  = '') {
        //如果是修改
        if(!empty($find)) {
            $this->model = $find;
        }
        //验证主字段是否有问题
        $this->create($request);
        $fun = '_add_special_'.$type;
        if (method_exists($this, $fun)) $this->$fun($request);

        if($this->error_flag) throw new sfException('有错误');

        //开始添加
        $_new = $this->form['specialForm']->save();
        $_new->setInfo(json_encode($this->create));
        $_new->save();
        return $_new->getId();
    }


    /*
     * 添加主字段
     */
    public function create($request) {
        $_post[$this->table] = $request->getParameter($this->table);
        $this->form['specialForm']->bind($_post[$this->table]);
        if(!$this->form['specialForm']->isValid()) {
            $this->error_flag = true;
        }
    }



    /*
     * 专题第一个模板 添加
     */
    public function _add_special_1($request) {
        $_post[$this->table] = $request->getParameter($this->table);
        $_post['one'] = $request->getParameter('one');
        /*  专题一 头部  */
        $this->bind['attr']['specialimageinput'] = $_post['one']['specialimageinput'];  //头图 图片地址
        $this->bind['attr']['top_bg'] = $_post['one']['top_bg'];  //头图 背景色
        $this->bind['attr']['top_link'] = $_post['one']['top_link'];  //头图 链接
        $this->bind['attr']['foot_show'] = isset($_post['one']['foot_show']) ? $_post['one']['foot_show'] : '';
        $this->bind['attr']['css'] = $_post['one']['css'];
        $this->bind['attr']['js'] = $_post['one']['js'];
        /* 专题一 楼层 */
        $catetitle    = $request->getParameter("catetitle",array());
        $catebarcolor = $request->getParameter("catebarcolor",array());
        $catebuttoncolor = $request->getParameter("catebuttoncolor",array());
        $cateitemnum = $request->getParameter("cateitemnum",array());
        $attrcates = array();
        foreach($catetitle as $key=>$val) {
            $name = "catetitle[".$key."]";
            $this->form['oneForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['oneForm']->setValidator($name, new sfValidatorString(array( 'required' => true ,'max_length'=>20), array( 'required' => '请填写','max_length'=>'最长20个字符')));
            $this->bind['attr'][$name] = isset($catetitle[$key])?$catetitle[$key]:"";
            $attrcates['attr']['catetitle'][] = isset($catetitle[$key])?$catetitle[$key]:"";

            $name = "catebarcolor[".$key."]";
            $this->form['oneForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['oneForm']->setValidator($name, new sfValidatorString(array( 'required' => true), array( 'required' => '请填写')));
            $this->bind['attr'][$name] =  isset($catebarcolor[$key])?$catebarcolor[$key]:"";
            $attrcates['attr']['catebarcolor'][] = isset($catebarcolor[$key])?$catebarcolor[$key]:"";

            ////TODO 暂时留空 以后如果需求改变了再写
            $attrcates['attr']['catekey'][] = $key;

            $name = "catebuttoncolor[".$key."]";
            $this->form['oneForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['oneForm']->setValidator($name, new sfValidatorString(array( 'required' => true), array( 'required' => '请填写')));
            $this->bind['attr'][$name] = isset($catebuttoncolor[$key])?$catebuttoncolor[$key]:"";
            $attrcates['attr']['catebuttoncolor'][] = isset($catebuttoncolor[$key])?$catebuttoncolor[$key]:"";

            $name = "cateitemnum[".$key."]";
            $this->form['oneForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['oneForm']->setValidator($name, new sfValidatorString(array( 'required' => true), array( 'required' => '请填写')));
            $this->bind['attr'][$name] =  isset($cateitemnum[$key])?$cateitemnum[$key]:"";
            $attrcates['attr']['cateitemnum'][] = isset($cateitemnum[$key])?$cateitemnum[$key]:"";
        }
        $this->form['oneForm']->bind($this->bind['attr']);
        if(!$this->form['oneForm']->isValid()) {
            $this->_var['catetitle'] = $catetitle;
            $this->error_flag = true;
        } else {
            if(!empty($this->model)) {
                $infojson = $this->model->getInfo();
                $this->create = json_decode($infojson, true);
            }
            $this->create['attr']['specialimage'] = $this->bind['attr']['specialimageinput'];
            $this->create['attr']['seotitle'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['seokeyword'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['seodes'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['gobtncolor'] = ''; //TODO 暂时留空 以后如果需求改变了再写
            $this->create['attr']['mtitle'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['top_bg'] = $this->bind['attr']['top_bg'];
            $this->create['attr']['top_link'] = $this->bind['attr']['top_link'];
            $this->create['attr']['foot_show'] = $this->bind['attr']['foot_show'];
            $this->create['attr']['cates'] = $attrcates['attr'];
            $this->create['attr']['css'] = $this->bind['attr']['css'];
            $this->create['attr']['js'] = $this->bind['attr']['js'];

            //判断往期回顾
            if($this->create['attr']['foot_show']  == 3) {
                $this->create['attr']['foot_item'] = $_post['one']['manual_fill'];
            }
        }
    }



    /*
  * 专题第二个模板 添加
  */
    public function _add_special_2($request) {
        $_post[$this->table] = $request->getParameter($this->table);
        $_post['two'] = $request->getParameter('two');
        /*  专题一 头部  */
        $this->bind['attr']['specialimageinput'] = $_post['two']['specialimageinput'];  //头图 图片地址
        $this->bind['attr']['top_bg'] = $_post['two']['top_bg'];  //头图 背景色
        $this->bind['attr']['top_link'] = $_post['two']['top_link'];  //头图 链接
        $this->bind['attr']['foot_show'] = isset($_post['two']['foot_show']) ? $_post['two']['foot_show'] : '';

        /* 专题一 楼层 */
        $catetitle    = $request->getParameter("catetitle",array());
        $catebarcolor = $request->getParameter("catebarcolor",array());
        $catebuttoncolor = $request->getParameter("catebuttoncolor",array());
        $attrcates = array();
        foreach($catetitle as $key=>$val) {
            $name = "catetitle[".$key."]";
            $this->form['twoForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['twoForm']->setValidator($name, new sfValidatorString(array( 'required' => true ,'max_length'=>20), array( 'required' => '请填写','max_length'=>'最长20个字符')));
            $this->bind['attr'][$name] = isset($catetitle[$key])?$catetitle[$key]:"";
            $attrcates['attr']['catetitle'][] = isset($catetitle[$key])?$catetitle[$key]:"";

            $name = "catebarcolor[".$key."]";
            $this->form['twoForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['twoForm']->setValidator($name, new sfValidatorString(array( 'required' => true), array( 'required' => '请填写')));
            $this->bind['attr'][$name] =  isset($catebarcolor[$key])?$catebarcolor[$key]:"";
            $attrcates['attr']['catebarcolor'][] = isset($catebarcolor[$key])?$catebarcolor[$key]:"";

            ////TODO 暂时留空 以后如果需求改变了再写
            $attrcates['attr']['catekey'][] = $key;

            $name = "catebuttoncolor[".$key."]";
            $this->form['twoForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['twoForm']->setValidator($name, new sfValidatorString(array( 'required' => true), array( 'required' => '请填写')));
            $this->bind['attr'][$name] = isset($catebuttoncolor[$key])?$catebuttoncolor[$key]:"";
            $attrcates['attr']['catebuttoncolor'][] = isset($catebuttoncolor[$key])?$catebuttoncolor[$key]:"";
        }

        $this->form['twoForm']->bind($this->bind['attr']);
        if(!$this->form['twoForm']->isValid()) {
            $this->_var['catetitle'] = $catetitle;
            $this->error_flag = true;
        } else {
            if(!empty($this->model)) {
                $infojson = $this->model->getInfo();
                $this->create = json_decode($infojson, true);
            }
            $this->create['attr']['specialimage'] = $this->bind['attr']['specialimageinput'];
            $this->create['attr']['seotitle'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['seokeyword'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['seodes'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['gobtncolor'] = ''; //TODO 暂时留空 以后如果需求改变了再写
            $this->create['attr']['mtitle'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['top_bg'] = $this->bind['attr']['top_bg'];
            $this->create['attr']['top_link'] = $this->bind['attr']['top_link'];
            $this->create['attr']['foot_show'] = $this->bind['attr']['foot_show'];
            $this->create['attr']['cates'] = $attrcates['attr'];
            //判断往期回顾
            if($this->create['attr']['foot_show']  == 3 ) {
                $this->create['attr']['foot_item'] = $_post['two']['manual_fill'];
            }
        }
    }



    /*
       * 专题第4个模板 添加
       */
    public function _add_special_4($request) {
        $_post[$this->table] = $request->getParameter($this->table);
        $_post['four'] = $request->getParameter('four');

        $this->bind['attr']['show_comments'] = isset($_post['four']['show_comments']) ? $_post['four']['show_comments'] : '';
        $this->bind['attr']['content'] = isset($_post['four']['content']) ? $_post['four']['content'] : '';
        $this->bind['attr']['foot_show'] = isset($_post['four']['foot_show']) ? $_post['four']['foot_show'] : '';

        $this->form['fourForm']->bind($this->bind['attr']);
        if(!$this->form['fourForm']->isValid()) {
            $this->error_flag = true;
        } else {
            if(!empty($this->model)) {
                $infojson = $this->model->getInfo();
                $this->create = json_decode($infojson, true);
            }
            $this->create['show_comments'] = $this->bind['attr']['show_comments'];
            $this->create['foot_show'] = $this->bind['attr']['foot_show'];
            $this->create['content'] = $this->bind['attr']['content'];
            //判断往期回顾
            if($this->create['foot_show']  == 3) {
                $this->create['foot_item'] = $_post['four']['manual_fill'];
            }
        }
    }

    /*
     * 专题第五个模板 添加
     */
    public function _add_special_5($request) {
        $_post[$this->table] = $request->getParameter($this->table);
        $_post['five'] = $request->getParameter('five');
        /*  专题一 头部  */
        $this->bind['attr']['specialimageinput'] = $_post['five']['specialimageinput'];  //头图 图片地址
        $this->bind['attr']['top_bg'] = $_post['five']['top_bg'];  //头图 背景色
        $this->bind['attr']['top_link'] = $_post['five']['top_link'];  //头图 链接
        $this->bind['attr']['foot_show'] = isset($_post['five']['foot_show']) ? $_post['five']['foot_show'] : '';
        $this->bind['attr']['num'] = isset($_post['five']['num']) ? $_post['five']['num'] : '';

        $this->form['fiveForm']->bind($this->bind['attr']);
        if(!$this->form['fiveForm']->isValid()) {
            $this->error_flag = true;
        } else {
            if(!empty($this->model)) {
                $infojson = $this->model->getInfo();
                $this->create = json_decode($infojson, true);
            }
            $this->create['attr']['specialimage'] = $this->bind['attr']['specialimageinput'];
            $this->create['attr']['seotitle'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['seokeyword'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['seodes'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['gobtncolor'] = ''; //TODO 暂时留空 以后如果需求改变了再写
            $this->create['attr']['mtitle'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['top_bg'] = $this->bind['attr']['top_bg'];
            $this->create['attr']['top_link'] = $this->bind['attr']['top_link'];
            $this->create['attr']['foot_show'] = $this->bind['attr']['foot_show'];
            $this->create['attr']['num'] = $this->bind['attr']['num'];
            //判断往期回顾
            if($this->create['attr']['foot_show']  == 3) {
                $this->create['attr']['foot_item'] = $_post['five']['manual_fill'];
            }
        }
    }




    /*
   * 专题第六个模板 添加
   */
    public function _add_special_6($request) {
        $_post[$this->table] = $request->getParameter($this->table);
        $_post['six'] = $request->getParameter('six');

        /*  专题六 头部  */
        $this->bind['attr']['specialimageinput'] = $_post['six']['specialimageinput'];  //头图 图片地址
        $this->bind['attr']['top_bg'] = $_post['six']['top_bg'];  //头图 背景色
        $this->bind['attr']['top_bg_img'] = $_post['six']['top_bg_img'];  //头图 背景图
        $this->bind['attr']['top_link'] = $_post['six']['top_link'];  //头图 链接
        $this->bind['attr']['foot_show'] = isset($_post['six']['foot_show']) ? $_post['six']['foot_show'] : '';

        /* 专题六 楼层 */
        $catetitle    = $request->getParameter("catetitle",array());
        $catename = $request->getParameter("catename",array());
        $catebarcolor = $request->getParameter("catebarcolor",array());
        $catebuttoncolor = $request->getParameter("catebuttoncolor",array());
        $cateitemnum = $request->getParameter("cateitemnum",array());
        $attrcates = array();
        foreach($catetitle as $key=>$val) {
            $name = "catetitle[".$key."]";
            $this->form['sixForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['sixForm']->setValidator($name, new sfValidatorString(array( 'required' => true ,'max_length'=>20), array( 'required' => '请填写','max_length'=>'最长20个字符')));
            $this->bind['attr'][$name] = isset($catetitle[$key])?$catetitle[$key]:"";
            $attrcates['attr']['catetitle'][] = isset($catetitle[$key])?$catetitle[$key]:"";

            $name = "catename[".$key."]";
            $this->form['sixForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['sixForm']->setValidator($name, new sfValidatorString(array( 'required' => true), array( 'required' => '请填写')));
            $this->bind['attr'][$name] =  isset($catename[$key])?$catename[$key]:"";
            $attrcates['attr']['catename'][] = isset($catename[$key])?$catename[$key]:"";

            $name = "catebarcolor[".$key."]";
            $this->form['sixForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['sixForm']->setValidator($name, new sfValidatorString(array( 'required' => true), array( 'required' => '请填写')));
            $this->bind['attr'][$name] =  isset($catebarcolor[$key])?$catebarcolor[$key]:"";
            $attrcates['attr']['catebarcolor'][] = isset($catebarcolor[$key])?$catebarcolor[$key]:"";

            $attrcates['attr']['catekey'][] = $key;

            $name = "catebuttoncolor[".$key."]";
            $this->form['sixForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['sixForm']->setValidator($name, new sfValidatorString(array( 'required' => true), array( 'required' => '请填写')));
            $this->bind['attr'][$name] = isset($catebuttoncolor[$key])?$catebuttoncolor[$key]:"";
            $attrcates['attr']['catebuttoncolor'][] = isset($catebuttoncolor[$key])?$catebuttoncolor[$key]:"";


            $name = "cateitemnum[".$key."]";
            $this->form['sixForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['sixForm']->setValidator($name, new sfValidatorString(array( 'required' => true), array( 'required' => '请填写')));
            $this->bind['attr'][$name] =  isset($cateitemnum[$key])?$cateitemnum[$key]:"";
            $attrcates['attr']['cateitemnum'][] = isset($cateitemnum[$key])?$cateitemnum[$key]:"";

            ////TODO 暂时留空 以后如果需求改变了再写
            $attrcates['attr']['catekey'][] = $key;
        }

        $this->form['sixForm']->bind($this->bind['attr']);

        if(!$this->form['sixForm']->isValid()) {
            $this->_var['catetitle'] = $catetitle;
            $this->error_flag = true;
        } else {
            if(!empty($this->model)) {
                $infojson = $this->model->getInfo();
                $this->create = json_decode($infojson, true);
            }
            $this->create['attr']['specialimage'] = $this->bind['attr']['specialimageinput'];
            $this->create['attr']['seotitle'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['seokeyword'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['seodes'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['gobtncolor'] = ''; //TODO 暂时留空 以后如果需求改变了再写
            $this->create['attr']['mtitle'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['top_bg'] = $this->bind['attr']['top_bg'];
            $this->create['attr']['top_bg_img'] = $this->bind['attr']['top_bg_img'];
            $this->create['attr']['top_link'] = $this->bind['attr']['top_link'];
            $this->create['attr']['foot_show'] = $this->bind['attr']['foot_show'];
            $this->create['attr']['cates'] = $attrcates['attr'];
            //判断往期回顾
            if($this->create['attr']['foot_show']  == 3 ) {
                $this->create['attr']['foot_item'] = $_post['six']['manual_fill'];
            }
        }
    }


    /*
      * 专题第七个模板 添加
      */
    public function _add_special_7($request) {
        $_post[$this->table] = $request->getParameter($this->table);
        $_post['seven'] = $request->getParameter('seven');

        /*  专题七 头部  */
        $this->bind['attr']['specialimageinput'] = $_post['seven']['specialimageinput'];  //头图 图片地址
        $this->bind['attr']['top_bg'] = $_post['seven']['top_bg'];  //头图 背景色
        $this->bind['attr']['top_link'] = $_post['seven']['top_link'];  //头图 链接
        $this->bind['attr']['foot_show'] = isset($_post['seven']['foot_show']) ? $_post['seven']['foot_show'] : '';
        $this->bind['attr']['num'] = isset($_post['seven']['num']) ? $_post['seven']['num'] : '';

        $this->form['sevenForm']->bind($this->bind['attr']);
        if(!$this->form['sevenForm']->isValid()) {
            $this->error_flag = true;
        } else {
            if(!empty($this->model)) {
                $infojson = $this->model->getInfo();
                $this->create = json_decode($infojson, true);
            }
            $this->create['attr']['specialimage'] = $this->bind['attr']['specialimageinput'];
            $this->create['attr']['seotitle'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['seokeyword'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['seodes'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['gobtncolor'] = ''; //TODO 暂时留空 以后如果需求改变了再写
            $this->create['attr']['mtitle'] = $_post[$this->table]['name']. ' - 识货专题';
            $this->create['attr']['top_bg'] = $this->bind['attr']['top_bg'];
            $this->create['attr']['top_link'] = $this->bind['attr']['top_link'];
            $this->create['attr']['foot_show'] = $this->bind['attr']['foot_show'];
            $this->create['attr']['num'] = $this->bind['attr']['num'];
            //判断往期回顾
            if($this->create['attr']['foot_show']  == 3) {
                $this->create['attr']['foot_item'] = $_post['seven']['manual_fill'];
            }
        }
    }

}