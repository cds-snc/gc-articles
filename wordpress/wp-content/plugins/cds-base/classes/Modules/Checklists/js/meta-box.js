jQuery(document).ready(    
    function($) {

        /* Assigning vars: Their vars (get rid of these) */
        var ppc_checkboxes = jQuery('.ppc_checkboxes[type="checkbox"]');
        var ppc_checkboxes_length = jQuery('.ppc_checkboxes[type="checkbox"]').length;
        var countCheckedppc_checkboxes = ppc_checkboxes.filter(':checked').length;

        /* ~TESTED */

        /* Assigning vars: Our vars */
        const ppc_error_level = {option: 1}; // 1 is "required", 2 is "recommended"
        const $items = $('.pp-checklists-req')
        const numItems = $items.length
        const $requiredItems = $items.filter('.pp-checklists-block')
        const numRequiredItems = $requiredItems.length
        const $recommendedItems = $items.filter('.pp-checklists-warning')
        const numRecommendedItems = $recommendedItems.length
        const metaboxID = '#pp_checklist_meta'

        console.log('items', $items)
        console.log('numItems', numItems)

        // .editor-post-publish-panel__toggle   = "publish" button for unpublished posts 
        // .editor-post-publish-button          = "update" button for already-published posts

        //function to be executed when the itemlist changes.
        
        var ppc_checkbox_function = function() {
            // check if all the required && recommended checklists are checked
            console.log('ppc_checkbox_function called')
            // TODO: this length is off, it happens too quickly
            numCheckedItems = $items.filter('.status-yes').length
            console.log('numCheckedItems', numCheckedItems)

            /* ~END TESTED */

            if (numItems === numCheckedItems) { // if all the checkboxes are checked (lets publish!!)
                console.log('all items are checked')
                if (jQuery('.editor-post-publish-panel__toggle').length == 1) {
                    jQuery('.edit-post-header__settings').children(jQuery('#ppc-update').attr('style', 'display:none')); // Hide the custom "Update" button
                    jQuery('.edit-post-header__settings').children(jQuery('#ppc-publish').attr('style', 'display:none')); // Hide the custom "Publish" button
                    jQuery('.editor-post-publish-panel__toggle').attr('style', 'display:inline-flex'); // Show the regular "Publish" button
                } else if (jQuery('.editor-post-publish-button').length == 1) {
                    jQuery('.edit-post-header__settings').children(':eq(2)').after(jQuery('#ppc-update').attr('style', 'display:none')); // (I think) Hide the custom "Publish" button
                    jQuery('.editor-post-publish-button').attr('style', 'display:inline-flex'); // Show the regular "Publish" button
                }
            // if not all the checkboxes are checked (lets not publish!!)
            } else if (numItems !== numCheckedItems) { 
                console.log('all items are NOT checked')

                // if not all the checkboxes are checked (lets not publish!!)
                if (jQuery('.editor-post-publish-panel__toggle').length == 1) {
                    console.log('toggle length == 1')

                    jQuery('#ppc-update').attr('style', 'display:none'); // hide the custom "update" button
                    jQuery('.editor-post-publish-panel__toggle').attr('style', 'display:none'); // hide the "publish" button
                    jQuery('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after(jQuery('#ppc-publish').attr('style', 'display:inline-flex')); // (I think) show the custom "Publish" button
                    // Add the (custom) publish button
                    if (jQuery('#ppc-publish').length == 0) {
                        jQuery('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-publish">Publish…</button>');
                    }
                // I am pretty sure this is the difference between an already published post and a new post
                } else if (jQuery('.editor-post-publish-button').length == 1) {
                    /* ~TESTED */
                    jQuery('.editor-post-publish-button').attr('style', 'display:none');
                    jQuery('.edit-post-header__settings').find('.editor-post-publish-button').after(jQuery('#ppc-update').attr('style', 'display:inline-flex'));
                    if (jQuery('#ppc-update').length == 0) {
                        // Add the (custom) "update" button
                        jQuery('.edit-post-header__settings').children(':eq(2)').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-update">Update…</button>');
                    }
                }
            }
        }


        setTimeout(ppc_checkbox_function, 1000);
        /* ~END TESTED */

        // Click "switch to draft" button (unpublish a post)
        jQuery(document).on('click', '.editor-post-switch-to-draft', function() {
            // remove the custom "update" button, show the custom "publish" button
            jQuery('#ppc_update').attr('style', 'display:none');
            jQuery('#ppc_publish').attr('style', 'display:inline-block');
        });


        // not sure what this is
        if (jQuery('#publish').length !== 1) {
            setTimeout(
                function() {
                    if (ppc_checkboxes_length != countCheckedppc_checkboxes) {
                        // new article
                        if (jQuery('.editor-post-publish-panel__toggle').length == 1) {
                            jQuery('.editor-post-publish-panel__toggle').attr('style', 'display:none'); // hide "publish" the button
                        } else if (jQuery('.editor-post-publish-button').length == 1) {
                            jQuery('.editor-post-publish-button').attr('style', 'display:none'); // hide the "update" button
                        }
                        if (jQuery('.edit-post-header__settings').find('.editor-post-save-draft').length != 0) { // if "save draft"
                            // add a custom "publish" button
                            jQuery('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-publish">Publish…</button>');
                        } else if (jQuery('.edit-post-header__settings').find('.editor-post-switch-to-draft').length == 1) { // if "switch to draft"
                            // add a custom "update" button
                            jQuery('.edit-post-header__settings').find('.editor-post-publish-button').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-update">Update…</button>');
                        } else if (jQuery('.edit-post-header__settings').find('.editor-post-switch-to-draft').length == 0) {  // if no "switch to draft"
                            // add a custom "publish" button
                            jQuery('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-publish">Publish…</button>');
                        }
                    }
                }, 10
            );

            /**
             * "ppc_error_level" is in the plugin settings
             * 
             * - 1: Prevent User from Publishing Page/Post
             * - 2: Warn User Before Publishing
             * - 3: Do Nothing and Publish
             */ 
            if (ppc_error_level.option == 1 || ppc_error_level.option == 2) {
                // run "ppc_checkbox_function" when the checkboxes are updated 
                ppc_checkboxes.change(ppc_checkbox_function);
            }
        }

        //  Warn User Before Publishing
        if (ppc_error_level.option == 2) {
            // Show the "warning" modal
            /* ~TESTED */
            jQuery(document).on('click', "#ppc-update", function() {
                jQuery('.ppc-modal-warn').attr('style', 'display:block');
            });
            /* ~END TESTED */
            jQuery(document).on('click', "#ppc-publish", function() {
                jQuery('.ppc-modal-warn').attr('style', 'display:block');
            });
        // Prevent User from Publishing Page/Post
        } else if (ppc_error_level.option == 1) {
            // Show the "prevent publishing" modal
            jQuery(document).on('click', "#ppc-update", function() {
                jQuery('.ppc-modal-prevent').attr('style', 'display:inline-block');
            });
            jQuery(document).on('click', "#ppc-publish", function() {
                jQuery('.ppc-modal-prevent').attr('style', 'display:block');
            });
        }
        // Click "Publish anyway" on the "warning" modal
        jQuery(document).on('click', ".ppc-popup-options-publishanyway", function() {
            // Hide the warning modal
            jQuery('.ppc-modal-warn').attr('style', 'display:none');
            // If it's a new post
            if (jQuery('.editor-post-publish-panel__toggle').length == 1) {
                // Hide custom "publish" button
                jQuery('#ppc-publish').attr('style', 'display:none');
                // Hide custom "update" button
                jQuery('#ppc-update').attr('style', 'display:none');
                // Show the real "publish" button
                jQuery('.editor-post-publish-panel__toggle').attr('style', 'display:inline-flex');
                // Click the real "publish" button
                jQuery('.editor-post-publish-panel__toggle').trigger('click', 'publish');
            // If it's an update to a post
            } else if (jQuery('.editor-post-publish-button').length == 1) {
                // Hide custom "update" button
                jQuery('#ppc-update').attr('style', 'display:none');
                // Show the real "Update" button
                jQuery('.editor-post-publish-button').attr('style', 'display:inline-flex');
                // Click the real "Update" button
                jQuery('.editor-post-publish-button').trigger('click', 'update');
                // Hide the real "Update" button
                jQuery('.editor-post-publish-button').attr('style', 'display:none');
                // Show the custom "Update" button
                jQuery('#ppc-update').attr('style', 'display:inline-block');
            } else {
                // Hide the modal, and click the publish button
                ppc_publish_flag = 1;
                jQuery('.ppc-modal-warn').attr('style', 'display:none');
                jQuery('#publish').trigger('click', 'publish');
            }
        });
        
        /* ~TESTED */
        const scrollToMetabox = (_metaboxID) => {
            document.querySelector(_metaboxID).scrollIntoView({
                behavior: 'smooth',
                block: 'end',
                inline: 'nearest'
            });
            $(_metaboxID).scrollTop += 50;
            // focus the metabox
            $(_metaboxID).focus();
            // Add class that gives the metabox a yellow background which fades
            $(metaboxID).addClass('ppc-metabox-background');
            setTimeout(function() {
                $(metaboxID).removeClass('ppc-metabox-background');
            }, 1000)
        }

        // Click "Don't publish" on the "warning" modal
        jQuery(document).on('click', ".ppc-popup-option-dontpublish", function() {
            // click the "Post" tab in the gutenberg side panel (as opposed to the "block" tab)
            jQuery('.edit-post-sidebar__panel-tab').first().trigger('click', 'publish');
            if (jQuery(metaboxID).attr("class") === 'postbox closed') {
                // Open the "pre-publish checklists" metabox
                jQuery(metaboxID).attr('class', 'postbox');
            }
            // Hide the "warning" modal
            jQuery('.ppc-modal-warn').attr('style', 'display:none');
            // scroll the "pre-publish checklists" metabox into view
            scrollToMetabox(metaboxID);
        });

        // Click "Okay" on the "not allowed to publish" modal
        jQuery(document).on('click', ".ppc-popup-option-okay", function() {
            // click the "Post" tab in the gutenberg side panel (as opposed to the "block" tab)
            jQuery('.edit-post-sidebar__panel-tab').first().trigger('click', 'publish');
            if (jQuery(metaboxID).attr("class") === 'postbox closed') {
                // Open the "pre-publish checklists" metabox
                jQuery(metaboxID).attr('class', 'postbox');
            }
            // Hide the "not allowed to publish modal"
            jQuery('.ppc-modal-prevent').attr('style', 'display:none');
            // scroll the "pre-publish checklists" metabox into view
            scrollToMetabox(metaboxID);
        });
    }
);
/* ~END TESTED */
