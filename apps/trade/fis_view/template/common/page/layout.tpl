<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
{%html framework="common:static/js/mod-amd.js" %}
{%head%}
<meta http-equiv="X-UA-Compatible" content="IE=8">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
<title>{%$_Seo.title|f_escape_xml%}</title>
<meta name="keywords" content="{%$_Seo.keywords|f_escape_xml%}">
<meta name="description" content="{%$_Seo.description|f_escape_xml%}">
{%require name="common:static/css/common.css"%}
<link href="http://kaluli.hoopchina.com.cn/css/trade/haitao/cart.css" media="screen" type="text/css" rel="stylesheet">
<script type="text/javascript" src="http://b1.hoopchina.com.cn/common/jquery-1.8.js"></script>
<script src="http://b3.hoopchina.com.cn/web/module/dace/1.0.0/dace.js"></script>{%block name="block_head_static"%}{%/block%}
{%/head%}
{%body%}
{%block name="header"%}{%/block%}{%block name="content"%}{%/block%}{%block name="footer"%}{%/block%}
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_30089914'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "w.cnzz.com/c.php%3Fid%3D30089914' type='text/javascript'%3E%3C/script%3E"));</script>
<script type="text/javascript" src="http://goto.hupu.com/js/c/77.js"></script>
{%require name='common:page/layout.tpl'%}{%/body%}
{%/html%}