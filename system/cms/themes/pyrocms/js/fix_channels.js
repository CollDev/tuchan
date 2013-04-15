// JavaScript Document

var bkg=$("section.title").find("h4").attr("background");
var nam=$("section.title").find("h4").html();
//console.log(bkg);
$(".channel_item").css("background",bkg);
$(".channel_item").find("a").before("<span>"+nam+"</span>");

