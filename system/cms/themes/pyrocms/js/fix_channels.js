// JavaScript Document
var bkg=$("section.title").find("h4").css("background");
var nam=$("section.title").find("h4").html();
//alert(bkg);
console.log(bkg);
$("h2.channel_item").css("background",bkg);
$("h2.channel_item").append(nam);

