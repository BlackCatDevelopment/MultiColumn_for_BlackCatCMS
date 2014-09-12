$(document).ready(function()
{$('.cc_multicolumn_eq .cc_multicolumn_row').each(function()
{var height=0,current_row=$(this);current_row.children('.cc_multicolumn').each(function()
{var current_height=$(this).find('.cc_multicolumn_content').outerHeight();height=height<current_height?current_height:height;});current_row.find('.cc_multicolumn_content').css({height:height});});});