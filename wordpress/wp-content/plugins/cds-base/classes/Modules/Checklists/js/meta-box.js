jQuery(document).ready(    
    function($) {

        // TODOs:
        // - ✅ Open settings if closed
        // - ✅ Detect when a checkbox is clicked
        // - ✅ Detect when a condition is met
        // - ❌ Detect when unpublished
        // - Check when checkboxes are required or recommended


        /* Assigning vars: Our vars */
        const ppc_error_level = {option: 1}; // 1 is "required", 2 is "recommended"
        const metaboxID = '#pp_checklist_meta'
        const $itemsContainer = $('#pp-checklists-req-box')
        const $items = $itemsContainer.find('.pp-checklists-req')
        const numItems = $items.length

        // .editor-post-publish-panel__toggle   = "publish" button for unpublished posts 
        // .editor-post-publish-button          = "update" button for already-published posts

        //function to be executed when the itemlist changes.
        var ppc_checkbox_function = function() {
            // check if all the required && recommended checklists are checked

            // TODO: this length is off, it happens too quickly
            numCheckedItems = $items.filter('.status-yes').length
            console.log('numCheckedItems', numCheckedItems)

            if (numItems === numCheckedItems) { // if all the checkboxes are checked (lets publish!!)
                console.log('all items are checked')
                if (jQuery('.editor-post-publish-panel__toggle').length == 1) {
                    jQuery('.edit-post-header__settings').children(jQuery('#ppc-update').attr('style', 'display:none')); // Hide the custom "Update" button
                    jQuery('.edit-post-header__settings').children(jQuery('#ppc-publish').attr('style', 'display:none')); // Hide the custom "Publish" button
                    jQuery('.editor-post-publish-panel__toggle').attr('style', 'display:inline-flex'); // Show the regular "Publish" button
                } else if (jQuery('.editor-post-publish-button').length == 1) { // if "Update"
                    jQuery('.edit-post-header__settings').children(':eq(2)').after(jQuery('#ppc-update').attr('style', 'display:none')); // (I think) Hide the custom "Update" button
                    jQuery('.editor-post-publish-button').attr('style', 'display:inline-flex'); // Show the regular "Update" button
                }

            // if not all the checkboxes are checked (lets not publish!!)
            } else if (numItems !== numCheckedItems) { 
                console.log('all items are NOT checked')

                // if NOT all the checkboxes are checked (don't publish!!)
                if (jQuery('.editor-post-publish-panel__toggle').length == 1) {
                    jQuery('#ppc-update').attr('style', 'display:none'); // hide the custom "update" button
                    jQuery('.editor-post-publish-panel__toggle').attr('style', 'display:none'); // hide the "publish" button
                    jQuery('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after(jQuery('#ppc-publish').attr('style', 'display:inline-flex')); // (I think) show the custom "Publish" button
                    // Add the (custom) publish button
                    if (jQuery('#ppc-publish').length == 0) {
                        jQuery('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-publish">Publish…</button>');
                    }
                // I am pretty sure this is the difference between an already published post and a new post
                } else if (jQuery('.editor-post-publish-button').length == 1) {
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

        /* Observer that triggers whenever a checkbox's state changes */
        const checkboxObserver = new MutationObserver((e) => ppc_checkbox_function());
        checkboxObserver.observe($itemsContainer[0], {
            subtree: true,
            attributeFilter: ['class']}
        );

        /**
         * "ppc_error_level.option" is in the plugin settings
         *
         * - 1: Prevent User from Publishing Page/Post
         * - 2: Warn User Before Publishing
         * - 3: Do Nothing and Publish
         */
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
                jQuery('.editor-post-publish-panel__toggle').trigger('click');

                // check if "interface-interface-skeleton__actions" contains a "publish" button
                const $publishButton = jQuery('.interface-interface-skeleton__actions').find('.editor-post-publish-panel .editor-post-publish-button');
                if($publishButton.length && $publishButton.text() === 'Publish') {
                    // Click the "publish" button in the slideout panel
                    $publishButton.trigger('click');
                }

            // If it's an update to a post
            } else if (jQuery('.editor-post-publish-button').length == 1) {
                // Hide custom "update" button
                jQuery('#ppc-update').attr('style', 'display:none');
                // Show the real "Update" button
                jQuery('.editor-post-publish-button').attr('style', 'display:inline-flex');
                // Click the real "Update" button
                jQuery('.editor-post-publish-button').trigger('click');
                // Hide the real "Update" button
                jQuery('.editor-post-publish-button').attr('style', 'display:none');
                // Show the custom "Update" button
                jQuery('#ppc-update').attr('style', 'display:inline-block');
            }
        });
        
        /**
         * Open the "settings" panel if it is closed
         * Switch to the "Post" tab if we are on the "Block" tab
         * Open the "Checklist" bix if it is collapsed
         */
        const openPanel = () => {
            // Open the "Settings" panel if it is closed
            const $settingsButton = $('.edit-post-header__settings button[aria-label="Settings"]')
            if($settingsButton.attr('aria-expanded') === 'false') {
                $settingsButton.trigger('click');
            }

            // click the "Post" tab in the gutenberg side panel (as opposed to the "block" tab)
            $('.edit-post-sidebar__panel-tab').first().trigger('click', 'publish');
            if ($(metaboxID).attr("class") === 'postbox closed') {
                // Open the "pre-publish checklists" metabox
                $(metaboxID).attr('class', 'postbox');
            }
        }

        /**
         * Scroll to the metabox and apply temp background colour to draw attention
         *
         * @param string _metaboxID The id attr of the "Checklists" metabox
         */
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
            openPanel();

            // Hide the "warning" modal
            jQuery('.ppc-modal-warn').attr('style', 'display:none');
            // scroll the "pre-publish checklists" metabox into view
            scrollToMetabox(metaboxID);
        });

        // Click "Okay" on the "not allowed to publish" modal
        jQuery(document).on('click', ".ppc-popup-option-okay", function() {
            openPanel();

            // Hide the "not allowed to publish modal"
            jQuery('.ppc-modal-prevent').attr('style', 'display:none');
            // scroll the "pre-publish checklists" metabox into view
            scrollToMetabox(metaboxID);
        });
    }
);
