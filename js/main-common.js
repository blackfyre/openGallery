/**
 * Created with JetBrains PhpStorm.
 * User: Galicz Miklós
 * Date: 2013.09.09.
 * Time: 10:29
 */

navSelector = $(".nav a[href='"+top.location.href.replace('http://'+location.hostname,'')+"']");

navSelector.parent("li").addClass('active');
navSelector.parent("li").parent("ul").parent('li').addClass('active');

