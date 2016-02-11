/**
* 2007-2014 Mondial relay
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to Mondial relay so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Mondial relay to newer
* versions in the future. If you wish to customize Mondial relay for your
* needs please refer to Mondial relay for more information.
*
*     @author Mondial relay
*     @copyright  2007-2014 Mondial relay
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Mondial relay
*/

function checkMrSelection() { 
    
    if (PS_MRData.PS_VERSION < '1.5') {
        var selected_carrier_id = $('input[name=id_carrier]:checked').val();
    } else {
        var selected_carrier_id = parseInt($('.delivery_option_radio:checked').val());
    }   
    
    var MR_carrier_selected = (selected_carrier_id==PS_MRSelectedRelayPoint['carrier_id']); // is a mondial relay carrier ?
    var MR_relay_selected = (PS_MRSelectedRelayPoint['relayPointNum']>0);                   // a relay has been selected ?

    if (!MR_carrier_selected) 
        return true; 
    
    if (MR_relay_selected) {
        return true; 
    } else {
        if (!!$.prototype.fancybox && !(PS_MRData.PS_VERSION < '1.5'))
           $.fancybox.open([
            {
                type: 'inline',
                autoScale: true,
                minHeight: 30,
                content: '<p class="fancybox-error">' + PS_MRTranslationList['errorSelection'] + '</p>'
            }],
            {
                padding: 0
            });
        else
            alert(PS_MRTranslationList['errorSelection']);
        
        return false;
    }
}

function isOnePageCheckout(){
  
    if($('.payment_module a').length && typeof(PS_MRData) != 'undefined'){
        return true;
    }
    return false;
}

function setProtectRelaySelected(){
    if(isOnePageCheckout()){
            $('.payment_module a').each(function(){
                if(typeof($(this).attr('onclick'))!='undefined'){
                    $(this).data('onclick', $(this).attr('onclick'));
                    $(this).attr('onclick', '');
                }
            });  
            
            //OPC Validation - Warn user to select a relay point
            $('.payment_module').click(function(event) {
                if(!checkMrSelection()){
                    event.stopPropagation();
                    return false;
                }
                else{
                    var lien= $('a', $(this));
                    if( lien.is("[onclick]") && lien.attr('onclick') == '' ){
                        lien.attr('onclick', lien.data('onclick'));
                        lien.trigger('click');
                    }
                }
            });

            $('form').submit(function(event) {

                if(checkMrSelection()){
                    $(this).off('submit').submit();

                }
                event.stopPropagation();
                return false;
            });	
           
        }
        
        $('#form button[name="processCarrier"], #form input[name="processCarrier"]').click(function(event) {
            if(!checkMrSelection()){
                event.stopPropagation();
                return false;
            }	
        });
        
}

(function($) {
    
    $(function(){  
		$('#cgv').click(function(){
			if($(this).attr('checked')){
				$("body").bind("ajaxComplete", function(e, xhr, settings){
					   setProtectRelaySelected();
					   
				});
				
			}
		});
        
        
    });
})(jQuery);

