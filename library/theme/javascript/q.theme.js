/**
Plugin:     Q Theme
Version:    0.1.6
Date:       23/01/2019 09:00:54 pm
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
if(typeof jQuery!=='undefined'){jQuery(window).bind("load",function(){if($the_hash=q_tab_hash()){q_tab($the_hash)}else{q_tab_default()}});jQuery(document).ready(function(){jQuery(window).bind('hashchange',function(e){history.navigationMode='compatible';e.preventDefault();$the_hash=q_tab_hash();if($the_hash)q_tab($the_hash)});jQuery('select.tab-navigation').on('change',function(){$trigger=jQuery(this).find('option:selected').attr("data-tab-trigger");q_tab($trigger);$trigger='#tab/'+$trigger
if(history.pushState){history.pushState(null,null,$trigger)}
else{location.hash=$trigger}})});function q_tab_default(){jQuery('.q-tab-target').hide().addClass('q-tab-hidden').removeClass('q-tab-current');jQuery('.q-tab-trigger').removeClass('q-tab-current');jQuery('.q-tab-trigger:first-child').addClass('q-tab-current');jQuery('.q-tab-target:first-child').removeClass('q-tab-hidden').addClass('q-tab-current').show()}
function q_tab(data_id){$target=jQuery("[data-tab-target='"+data_id+"']");if($target.length){jQuery('.q-tab-target').each(function(){jQuery(this).hide().addClass('q-tab-hidden').removeClass('q-tab-current')});jQuery(".q-tab-trigger").removeClass('q-tab-current');$target.show().addClass('q-tab-current').removeClass('q-tab-hidden');jQuery("[data-tab-trigger='"+data_id+"']").addClass('q-tab-current')}}
function q_tab_hash(){var $hash=q_get_hash_value_from_key('tab');if(!$hash){return!1}
return $hash}}
