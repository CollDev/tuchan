// JavaScript Document
var logo=$("section.title").find(".logo_canal").find("img").attr("src");
$("section.title").find(".logo_canal").find("img").attr("height",40);
$("section.title").find(".logo_canal").find("img").attr("width",40);
var mimg=$("section.title").find(".logo_canal");

var url="background:url('"+logo+"');";
var nam=$("section.title").find("h4").html();
$("section.title").find("h4").hide();

$("h2.channel_item").find("a").before(mimg);

$("h2.channel_item").find("a").prepend(nam);
$("h2.channel_item").find("a").attr("float","left");
$("h2.channel_item").attr("background","none");
$("h2.channel_item").attr("style","padding-left:0px !important;background:none;");
$(".logo_canal").attr("style","width:40px;display: block;float:left;margin-right: 15px;");

