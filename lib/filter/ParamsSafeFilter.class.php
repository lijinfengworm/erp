<?php

/*
 * 检查参数，防止恶意攻击
 */

class ParamsSafeFilter extends sfFilter {

    const GET_FILTER = "'|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    const POST_FILTER = "\\b(and)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    const COOKIE_FILTER = "\\b(and)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";

    public function initialize($context, $parameters = array()) {
        parent::initialize($context, $parameters);
        $this->request = $context->getRequest();
    }

    public function execute($filterChain) {

        foreach ($_GET as $key => $value) {
            $this->StopAttack($key, $value, ParamsSafeFilter::GET_FILTER);
        }

        foreach ($_POST as $key => $value) {
            $this->StopAttack($key, $value, ParamsSafeFilter::POST_FILTER);
        }

        foreach ($_COOKIE as $key => $value) {
            $this->StopAttack($key, $value, ParamsSafeFilter::COOKIE_FILTER);
        }

        $filterChain->execute();
    }

    function StopAttack($StrFiltKey, $StrFiltValue, $ArrFiltReq) {

        if (is_array($StrFiltValue)) {
            $StrFiltValue = $this->implode_multiArr($StrFiltValue, '');
            //$StrFiltValue = implode('',$StrFiltValue); 
        }
        if (preg_match("/" . $ArrFiltReq . "/is", $StrFiltValue) == 1) {
            header("Status: 400 Bad Request");
            exit();
        }
    }

    function implode_multiArr($array, $mode) {
        $dataStr = '';
        foreach ($array as $keys => $values) {
            if (is_array($values)) {
                $dataStr .= $this->implode_multiArr($values, $mode);
            } else {
                $dataStr .= $values . $mode;
            }
        }
        return $dataStr;
    }

}
