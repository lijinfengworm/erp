{%extends file="common/page/layout.tpl"%} 
{%block name="block_head_static"%}
{%require name="theme:static/temp1.less"%}{%/block%}
{%block name="header"%}
{%widget name="common:widget/header/header.tpl"%}{%/block%}
{%block name="content"%}<div class="banner"><img  class="lazy" data-original="{%$data.info.top_image|f_escape_xml%}" alt="{%$data.title|f_escape_xml%}"></div>
<div class="padTop pageTemp">
{%foreach $data.info.floors as $title=>$val%}
<div class="block">
<h2 class="title"><span>{%$title|f_escape_xml%}</span></h2>
<div class="list">
<ul  class="clearfix">
{%foreach from=$val item=list%}
<li class="clearfix"><a href="{%$list.url|f_escape_xml%}">
<div class="imgs">
<img alt="{%$list.title|f_escape_xml%}" class="lazy" data-original="{%$list.pic|f_escape_xml%}" />
</div>
<div class="details">
<div class="name">{%$list.title|f_escape_xml%}</div>
<p class="from">来自{%$list.from|f_escape_xml%}</p>
<div class="price">
<div class="htprc"><p>海淘价：</p><p>¥<span>{%$list.discount_price|f_escape_xml%}</span></p></div>
<div class="gnprc"><p>国内价：</p><p class="pri">¥&nbsp;<span>{%$list.price|f_escape_xml%}</span></p></div>
<div class="vs"></div>
</div>
<div class="btn">
<span>立即购物</span>
</div>
</div>
</a></li>
{%/foreach%}
</ul>
</div>
</div>
{%/foreach%}
</div>
{%script%}
  var imglazy = require("common:widget/ui/imglazy/imglazy.js");
  $(".lazy").lazyload();
  {%/script%}
{%/block%}
{%block name="footer"%}
{%widget name="common:widget/footer/footer.tpl"%}
{%widget name="common:widget/cart/cart.tpl"%}{%require name='theme:page/Theme2.tpl'%}{%/block%}