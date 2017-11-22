<?php
Class kaluliTagSearch{
    /*索引创建*/
    public function create($id,$close_link = false){
        return $this->update($id,$close_link);
    }


    /*索引更新*/
    public function update($id,$close_link = false){
        $id = explode('_',$id);

        if($id && $id[0] == 2){
            $kaluliArticleSearch = new kaluliArticleSearch();
            $kaluliArticleSearch->update($id[1],true);
        }else if($id && $id[0] == 1){
            $kaluliItemSearch = new kaluliItemSearch();
            $kaluliItemSearch->update($id[1],true);
        }
    }

    /*索引删除*/
    public function delete($id,$close_link = false){
        $id = explode('_',$id);

        if($id && $id[0] == 2){
            $kaluliArticleSearch = new kaluliArticleSearch();
            $kaluliArticleSearch->update($id[1],true);
        }else if($id && $id[0] == 1){
            $kaluliItemSearch = new kaluliItemSearch();
            $kaluliItemSearch->update($id[1],true);
        }
    }
}













