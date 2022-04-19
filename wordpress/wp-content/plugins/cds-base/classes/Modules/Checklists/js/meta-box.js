jQuery(document).ready(
    function($) {

        /**
         * "ppc_error_level.option" is in the plugin settings
         *
         * - 1: Prevent User from Publishing Page/Post
         * - 2: Warn User Before Publishing
         * - 3: Do Nothing and Publish
         */

        /* Assigning vars: Our vars */
        const REQUIRED = {option: 1};
        const RECOMMENDED = {option: 2};
        let ppc_error_level = REQUIRED; // default to 'required'

        const metaboxID = '#pp_checklist_meta'
        const $itemsContainer = $('#pp-checklists-req-box')
        const $items = $itemsContainer.find('.pp-checklists-req')
        const numItems = $items.length

        // .editor-post-publish-panel__toggle   = "publish" button for unpublished posts 
        // .editor-post-publish-button          = "update" button for already-published posts

        //function to be executed when the itemlist changes.
        var ppc_checkbox_function = function() {
            // check if all the required && recommended checklists are checked
            numCheckedItems = $items.filter('.status-yes').length

            // if all the checkboxes are checked (lets publish!!)
            if (numItems === numCheckedItems) {
                if ($('.editor-post-publish-panel__toggle').length == 1) {
                    $('.edit-post-header__settings').children($('#ppc-update').attr('style', 'display:none')); // Hide the custom "Update" button
                    $('.edit-post-header__settings').children($('#ppc-publish').attr('style', 'display:none')); // Hide the custom "Publish" button
                    $('.editor-post-publish-panel__toggle').attr('style', 'display:inline-flex'); // Show the regular "Publish" button
                } else if ($('.editor-post-publish-button').length == 1) { // if "Update"
                    $('.edit-post-header__settings').children(':eq(2)').after($('#ppc-update').attr('style', 'display:none')); // (I think) Hide the custom "Update" button
                    $('.editor-post-publish-button').attr('style', 'display:inline-flex'); // Show the regular "Update" button
                }

            // if not all the checkboxes are checked (lets not publish!!)
            } else if (numItems !== numCheckedItems) {

                // check number of required checkboxes still unchecked
                ppc_error_level = $items.filter('.pp-checklists-block.status-no').length ? REQUIRED : RECOMMENDED;

                // if NOT all the checkboxes are checked (don't publish!!)
                if ($('.editor-post-publish-panel__toggle').length == 1) {
                    $('#ppc-update').attr('style', 'display:none'); // hide the custom "update" button
                    $('.editor-post-publish-panel__toggle').attr('style', 'display:none'); // hide the "publish" button
                    $('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after($('#ppc-publish').attr('style', 'display:inline-flex')); // (I think) show the custom "Publish" button
                    // Add the (custom) publish button
                    if ($('#ppc-publish').length == 0) {
                        $('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-publish">Publish…</button>');
                    }
                // I am pretty sure this is the difference between an already published post and a new post
                } else if ($('.editor-post-publish-button').length == 1) {
                    $('.editor-post-publish-button').attr('style', 'display:none');
                    $('.edit-post-header__settings').find('.editor-post-publish-button').after($('#ppc-update').attr('style', 'display:inline-flex'));
                    if ($('#ppc-update').length == 0) {
                        // Add the (custom) "update" button
                        $('.edit-post-header__settings').children(':eq(2)').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-update">Update…</button>');
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


        $(document).on('click', "#ppc-update, #ppc-publish", function() {
            if(ppc_error_level.option == REQUIRED.option) {
                // Prevent User from Publishing Page/Post
                $('.ppc-modal-prevent').attr('style', 'display:block');
            }
            else {
                // Warn User Before Publishing
                $('.ppc-modal-warn').attr('style', 'display:block');
            }
        })

        // Click "Publish anyway" on the "warning" modal
        $(document).on('click', ".ppc-popup-options-publishanyway", function() {
            // Hide the warning modal
            $('.ppc-modal-warn').attr('style', 'display:none');
            // If it's a new post
            if ($('.editor-post-publish-panel__toggle').length == 1) {
                // Hide custom "publish" button
                $('#ppc-publish').attr('style', 'display:none');
                // Hide custom "update" button
                $('#ppc-update').attr('style', 'display:none');
                // Show the real "publish" button
                $('.editor-post-publish-panel__toggle').attr('style', 'display:inline-flex');
                // Click the real "publish" button
                $('.editor-post-publish-panel__toggle').trigger('click');

                // check if "interface-interface-skeleton__actions" contains a "publish" button
                const $publishButton = $('.interface-interface-skeleton__actions').find('.editor-post-publish-panel .editor-post-publish-button');
                if($publishButton.length && $publishButton.text() === 'Publish') {
                    // Click the "publish" button in the slideout panel
                    $publishButton.trigger('click');
                }

            // If it's an update to a post
            } else if ($('.editor-post-publish-button').length == 1) {
                // Hide custom "update" button
                $('#ppc-update').attr('style', 'display:none');
                // Show the real "Update" button
                $('.editor-post-publish-button').attr('style', 'display:inline-flex');
                // Click the real "Update" button
                $('.editor-post-publish-button').trigger('click');
                // Hide the real "Update" button
                $('.editor-post-publish-button').attr('style', 'display:none');
                // Show the custom "Update" button
                $('#ppc-update').attr('style', 'display:inline-block');
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
        $(document).on('click', ".ppc-popup-option-dontpublish", function() {
            openPanel();

            // Hide the "warning" modal
            $('.ppc-modal-warn').attr('style', 'display:none');

            // scroll the "pre-publish checklists" metabox into view
            scrollToMetabox(metaboxID);
        });

        // Click "Okay" on the "not allowed to publish" modal
        $(document).on('click', ".ppc-popup-option-okay", function() {
            openPanel();

            // Hide the "not allowed to publish modal"
            $('.ppc-modal-prevent').attr('style', 'display:none');

            // scroll the "pre-publish checklists" metabox into view
            scrollToMetabox(metaboxID);
        });
    }
);
