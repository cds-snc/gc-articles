jQuery(document).ready(
    function() {
        /* Assigning vars */
        var ppc_publish_flag = 0;
        var ppc_checkboxes = jQuery('.ppc_checkboxes[type="checkbox"]');
        var ppc_checkboxes_length = jQuery('.ppc_checkboxes[type="checkbox"]').length;
        var countCheckedppc_checkboxes = ppc_checkboxes.filter(':checked').length;
        var ppc_percentage_completed = (countCheckedppc_checkboxes / ppc_checkboxes_length) * 100;
        jQuery('.ppc-percentage').attr('style', 'width:' + ppc_percentage_completed + '%');
        jQuery(".ppc-percentage-value").html(Math.round(ppc_percentage_completed) + "%");


        // .editor-post-publish-panel__toggle   = "publish" button for unpublished posts 
        // .editor-post-publish-button          = "update" button for already-published posts

        //function to be executed when the itemlist changes.
        
        var ppc_checkbox_function = function() {
            var ppc_checkboxes = jQuery('.ppc_checkboxes[type="checkbox"]');
            var ppc_checkboxes_length = jQuery('.ppc_checkboxes[type="checkbox"]').length;
            var countCheckedppc_checkboxes = ppc_checkboxes.filter(':checked').length;
            if (ppc_checkboxes_length == countCheckedppc_checkboxes) { // if all the checkboxes are checked (lets publish!!)
                if (jQuery('.editor-post-publish-panel__toggle').length == 1) {
                    jQuery('.edit-post-header__settings').children(jQuery('#ppc-update').attr('style', 'display:none')); // Hide the custom "Update" button
                    jQuery('.edit-post-header__settings').children(jQuery('#ppc-publish').attr('style', 'display:none')); // Hide the custom "Publish" button
                    jQuery('.editor-post-publish-panel__toggle').attr('style', 'display:inline-flex'); // Show the regular "Publish" button
                } else if (jQuery('.editor-post-publish-button').length == 1) {
                    jQuery('.edit-post-header__settings').children(':eq(2)').after(jQuery('#ppc-update').attr('style', 'display:none')); // (I think) Hide the custom "Publish" button
                    jQuery('.editor-post-publish-button').attr('style', 'display:inline-flex'); // Show the regular "Publish" button
                }
            // if not all the checkboxes are checked (lets not publish!!)
            } else if (ppc_checkboxes_length != countCheckedppc_checkboxes) { 
                // if not all the checkboxes are checked (lets not publish!!)
                if (jQuery('.editor-post-publish-panel__toggle').length == 1) {
                    jQuery('#ppc-update').attr('style', 'display:none'); // hide the custom "update" button
                    jQuery('.editor-post-publish-panel__toggle').attr('style', 'display:none'); // hide the "publish" button
                    jQuery('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after(jQuery('#ppc-publish').attr('style', 'display:inline-flex')); // (I think) show the custom "Publish" button
                    // Add the (custom) publish button
                    if (jQuery('#ppc-publish').length == 0) {
                        jQuery('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-publish">Publish...</button>');
                    }
                // I am pretty sure this is the difference between an already published post and a new post
                } else if (jQuery('.editor-post-publish-button').length == 1) {
                    jQuery('.editor-post-publish-button').attr('style', 'display:none');
                    jQuery('.edit-post-header__settings').find('.editor-post-publish-button').after(jQuery('#ppc-update').attr('style', 'display:inline-flex'));
                    if (jQuery('#ppc-update').length == 0) {
                        // Add the (custom) "update" button
                        jQuery('.edit-post-header__settings').children(':eq(2)').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-update">Update</button>');
                    }
                }
            }
        }

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
                    if (ppc_error_level.option != 3 && (ppc_checkboxes_length != countCheckedppc_checkboxes)) {
                        // new article
                        if (jQuery('.editor-post-publish-panel__toggle').length == 1) {
                            jQuery('.editor-post-publish-panel__toggle').attr('style', 'display:none'); // hide "publish" the button
                        } else if (jQuery('.editor-post-publish-button').length == 1) {
                            jQuery('.editor-post-publish-button').attr('style', 'display:none'); // hide the "update" button
                        }
                        if (jQuery('.edit-post-header__settings').find('.editor-post-save-draft').length != 0) { // if "save draft"
                            // add a custom "publish" button
                            jQuery('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-publish">Publish...</button>');
                        } else if (jQuery('.edit-post-header__settings').find('.editor-post-switch-to-draft').length == 1) { // if "switch to draft"
                            // add a custom "update" button
                            jQuery('.edit-post-header__settings').find('.editor-post-publish-button').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-update">Update</button>');
                        } else if (jQuery('.edit-post-header__settings').find('.editor-post-switch-to-draft').length == 0) {  // if no "switch to draft"
                            // add a custom "publish" button
                            jQuery('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-publish">Publish...</button>');
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
            else if (ppc_error_level.option == 3) {
                /* 
                This block of code seems to update the "title" of the publish button or the update button 
                */
                var ppc_checkboxes = jQuery('.ppc_checkboxes[type="checkbox"]');
                var countCheckedppc_checkboxes = ppc_checkboxes.filter(':checked').length;
                ppc_checkboxes.change(
                    function() {
                        var countCheckedppc_checkboxes = ppc_checkboxes.filter(':checked').length;
                        var countCheckedppc_checkboxes = ppc_checkboxes.filter(':checked').length;
                        if (ppc_checkboxes.length == countCheckedppc_checkboxes) {
                            //all checkboxes are checked
                            if (jQuery('.editor-post-publish-panel__toggle').length == 1) {
                                jQuery('.editor-post-publish-panel__toggle').prop('title', 'All items checked ! You are good to publish');
                            } else if (jQuery('.editor-post-publish-button').length == 1) {
                                jQuery('.editor-post-publish-button').prop('title', 'All items checked ! You are good to publish');
                            }
                        } else if (ppc_checkboxes.length != countCheckedppc_checkboxes) {
                            // All ppc_checkboxes are not yet checked                                
                            if (jQuery('.editor-post-publish-panel__toggle').length == 1) {
                                jQuery('.editor-post-publish-panel__toggle').prop('title', 'Pre-Publish-Checklist some items still remaining ');
                            } else if (jQuery('.editor-post-publish-button').length == 1) {
                                jQuery('.editor-post-publish-button').prop('title', 'Pre-Publish-Checklist some items still remaining !');
                            }
                        }
                    }
                );
            }
        }

        //  Warn User Before Publishing
        if (ppc_error_level.option == 2) {
            // Show the "warning" modal
            jQuery(document).on('click', "#ppc-update", function() {
                jQuery('.ppc-modal-warn').attr('style', 'display:block');
            });
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
                jQuery("#ppc_custom_meta_box").html();
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
        // Click "Don't publish" on the "warning" modal
        jQuery(document).on('click', ".ppc-popup-option-dontpublish", function() {
            // click the "Post" tab in the gutenberg side panel (as opposed to the "block" tab)
            jQuery('.edit-post-sidebar__panel-tab').first().trigger('click', 'publish');
            if (jQuery('#ppc_custom_meta_box').attr("class") == 'postbox closed') {
                // Open the "pre-publish checklists" metabox
                jQuery('#ppc_custom_meta_box').attr('class', 'postbox');
            }
            // Hide the "warning" modal
            jQuery('.ppc-modal-warn').attr('style', 'display:none');
            // scroll the "pre-publish checklists" metabox into view
            document.querySelector('#ppc_custom_meta_box').scrollIntoView({
                behavior: 'smooth',
                block: "end",
                inline: "nearest"
            });
            jQuery("#ppc_custom_meta_box").scrollTop += 50;
            // focus the "pre-publish checklists" metabox
            jQuery('#ppc_custom_meta_box').focus();
            // Add class that gives the custom metabox a yellow background which fades
            jQuery('#ppc_custom_meta_box').addClass('ppc-metabox-background');
            setTimeout(function() {
                jQuery('#ppc_custom_meta_box').removeClass('ppc-metabox-background');
            }, 1000)
        });
        // Click "Okay" on the "not allowed to publish" modal
        jQuery(document).on('click', ".ppc-popup-option-okay", function() {
            // click the "Post" tab in the gutenberg side panel (as opposed to the "block" tab)
            jQuery('.edit-post-sidebar__panel-tab').first().trigger('click', 'publish');
            if (jQuery('#ppc_custom_meta_box').attr("class") == 'postbox closed') {
                // Open the "pre-publish checklists" metabox
                jQuery('#ppc_custom_meta_box').attr('class', 'postbox');
            }
            // Hide the "not allowed to publish modal"
            jQuery('.ppc-modal-prevent').attr('style', 'display:none');
            // scroll the "pre-publish checklists" metabox into view
            document.querySelector('#ppc_custom_meta_box').scrollIntoView({
                behavior: 'smooth',
                block: "end",
                inline: "nearest"
            });
            // Add class that gives the custom metabox a yellow background which fades
            jQuery('#ppc_custom_meta_box').addClass('ppc-metabox-background');
            setTimeout(function() {
                jQuery('#ppc_custom_meta_box').removeClass('ppc-metabox-background');
            }, 1000)
        });
    }
);