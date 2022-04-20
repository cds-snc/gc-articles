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

        const isPublished = () => {
            // .editor-post-publish-panel__toggle   = "publish" button for unpublished posts 
            // .editor-post-publish-button          = "update" button for already-published posts

            return $('.editor-post-publish-button').length == 1 // if "Update" button exists, post is already published
        }

        const hideCustomButtons = () => {
            $('.edit-post-header__settings').children($('#ppc-update, #ppc-publish').attr('style', 'display:none')); // Hide the custom "Update" and "Publish" buttons
        }

        showCustomPublishButton = () => {
            $('#ppc-update').attr('style', 'display:none'); // Hide custom "Update" button
            $('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after($('#ppc-publish').attr('style', 'display:inline-flex')); // Show the custom "Publish" button

            // if "Publish" button doesn't exist yet, add it
            if ($('#ppc-publish').length == 0) {
                $('.edit-post-header__settings').find('.editor-post-publish-panel__toggle').after('<button type="button" class="components-button is-button is-primary ppc-publish" id="ppc-publish">Publish…</button>');
            }
        }

        showCustomUpdateButton = () => {
            $('#ppc-publish').attr('style', 'display:none'); // Hide custom "Publish" button
            $('.edit-post-header__settings').find('.editor-post-publish-button').after($('#ppc-update').attr('style', 'display:inline-flex')); // Show the custom "Update" button

            // if "Update" button doesn't exist yet, add it
            if ($('#ppc-update').length == 0) {
                $('.edit-post-header__settings').children(':eq(2)').after('<button type="button" class="components-button  is-button is-primary ppc-publish" id="ppc-update">Update…</button>');
            }
        }

        const showPublishButton = () => {
            $('.editor-post-publish-panel__toggle').attr('style', 'display:inline-flex'); // Show the regular "Publish" button
        }

        const hidePublishButton = () => {
            $('.editor-post-publish-panel__toggle').attr('style', 'display:none'); // Hide the regular "Publish" button
        }

        const showUpdateButton = () => {
            $('.editor-post-publish-button').attr('style', 'display:inline-flex'); // Show the regular "Publish" button
        }

        const hideUpdateButton = () => {
            $('.editor-post-publish-button').attr('style', 'display:none'); // Show the regular "Publish" button
        }

        const showWarningModal = () => {
            $('.ppc-modal-warn').attr('style', 'display:block');
        }

        const showPreventModal = () => {
            $('.ppc-modal-prevent').attr('style', 'display:block');
        }

        const hideModals = () => {
            $('.ppc-modal-prevent').add('.ppc-modal-warn').attr('style', 'display:none');
        }

        //function to be executed when the itemlist changes.
        var ppc_checkbox_function = function() {
            // check if all the required && recommended checklists are checked
            numItemsComplete = $items.filter('.status-yes').length

            // if all the checkboxes are checked (lets publish!!)
            if (numItems === numItemsComplete) {
                hideCustomButtons();

                isPublished() ? showUpdateButton() : showPublishButton()

            // if not all the checkboxes are checked (lets not publish!!)
            } else {
                // check number of required checkboxes still unchecked
                ppc_error_level = $items.filter('.pp-checklists-block.status-no').length ? REQUIRED : RECOMMENDED;

                if (isPublished()) {
                    hideUpdateButton();
                    showCustomUpdateButton();
                } else {
                    hidePublishButton();
                    showCustomPublishButton();
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
            ppc_error_level.option == REQUIRED.option ?
                showPreventModal() : // Prevent User from Publishing Page/Post
                showWarningModal() // Warn User Before Publishing
        })

        // Click "Publish anyway" on the "warning" modal
        $(document).on('click', ".ppc-popup-options-publishanyway", function() {

            hideModals(); // Hide the warning modal

            // If it's an update to a post
            if (isPublished()) {
                hideCustomButtons();
                showUpdateButton(); // Show the real "Update" button
                $('.editor-post-publish-button').trigger('click');
                hideUpdateButton(); // Hide the real "Update" button
                showCustomUpdateButton(); // Show the custom "Update" button

            // If it's being published
            } else {
                hideCustomButtons();
                showPublishButton(); // Show the real "publish" button
                $('.editor-post-publish-panel__toggle').trigger('click'); // Click the real "publish" button

                // check if a slide-out panel with a publish button appears
                const $publishButton = $('.interface-interface-skeleton__actions').find('.editor-post-publish-panel .editor-post-publish-button');
                if($publishButton.length && $publishButton.text() === 'Publish') {
                    $publishButton.trigger('click'); // Click the "publish" button in the slideout panel
                }
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

        // Click "Don't publish" on the "warning" modal, or click "Okay" on the "not allowed to publish" modal
        $(document).on('click', ".ppc-popup-option-dontpublish, .ppc-popup-option-okay", function() {
            openPanel();
            hideModals(); // Hide the "warning"/"prevent" modal
            scrollToMetabox(metaboxID); // scroll the "pre-publish checklists" metabox into view
        });
    }
);
