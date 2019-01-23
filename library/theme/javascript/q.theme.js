/**
Plugin:     Q Theme
Version:    0.1.5
Date:       22/01/2019 06:57:07 am
*/
$q_modal_hash_value=!1;$q_modal_key=!1;$q_modal_args=!1;if(typeof jQuery!=='undefined'){jQuery(document).ready(function(){jQuery(window).bind('hashchange',function(e){e.preventDefault();q_modal_toggle($q_modal_args)})})}
function q_modal($args)
{$args=$args||!1;if(!$args){return!1}
$q_modal_args=$args;q_modal_toggle($q_modal_args)}
function q_modal_hash($q_modal_args)
{$q_modal_key=q_get_hash_value_from_key('modal');return $q_modal_key}
var q_modal_close=function q_modal_close_function(e){q_modal_do_close(e)}
var q_modal_get_device=function(){$dev=jQuery('body').first().hasClass('device-mobile')?'handheld':'desktop';jQuery('.featherlight-content').parent().addClass('modal-'+$dev)}
function q_modal_do_close(e){$hash='';if($close=jQuery('[data-modal-key="'+$q_modal_key+'"]').find('span').attr('data-modal-close')){$hash=$close}
if($scroll=jQuery('[data-modal-key="'+$q_modal_key+'"]').find('span').attr('data-modal-scroll')){if(jQuery('[data-scroll="'+$scroll+'"]').length){$hash=$hash+'/scroll/'+$scroll}}
jQuery('html').removeClass('modal-open');jQuery('.featherlight').removeClass('modal-'+$q_modal_key);window.location.hash=$hash;return!1}
function q_modal_toggle($q_modal_args)
{$q_modal_key=q_modal_hash();jQuery('.featherlight').remove();if(!$q_modal_key){return!1}
q_modal_callback($q_modal_args,$q_modal_key);jQuery('html').addClass('modal-open');$content=jQuery('[data-modal-key="'+$q_modal_key+'"]').html();$q_modal_close=jQuery.featherlight($content,{type:'html',beforeOpen:q_modal_get_device,afterClose:q_modal_close,afterOpen:q_do_lazy,variant:'modal-'+$q_modal_key})}
function q_html_decode(input)
{var doc=new DOMParser().parseFromString(input,"text/html");return doc.documentElement.textContent}
function q_modal_callback($q_modal_args,$q_modal_key){$q_modal_args=$q_modal_args||!1;$q_modal_key=$q_modal_key||!1
if(!$q_modal_args||!$q_modal_key){return!1}
if($q_modal_args.callback){_function=$q_modal_args.callback;if(window[_function]){window[_function]($q_modal_key)}else{}}else{}}
