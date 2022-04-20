jQuery(document).ready(
    function($) {

        /* ====== Vars ====== */
        const REQUIRED = 1;
        const RECOMMENDED = 2;
        let errorLevel = REQUIRED; // default to 'required'

        const metaboxID = '#pp_checklist_meta'
        const $itemsContainer = $('#pp-checklists-req-box')
        const $items = $itemsContainer.find('.pp-checklists-req')

        /* ====== Utility functions: for updating components in the publishing interface ====== */

        const isPublished = () => {
            // .editor-post-publish-panel__toggle   = "publish" button for unpublished posts
            // .editor-post-publish-button          = "update" button for already-published posts

            return $('.editor-post-publish-button').length == 1 // if "Update" button exists, post is already published
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

        const hideCustomButtons = () => {
            $('.edit-post-header__settings').children($('#ppc-update, #ppc-publish').attr('style', 'display:none')); // Hide the custom "Update" and "Publish" buttons
        }

        const showPublishButton = () => {
            $('.editor-post-publish-panel__toggle').attr('style', 'display:inline-flex'); // Show the regular "Publish" button
        }

        const hidePublishButton = () => {
            $('.editor-post-publish-panel__toggle').attr('style', 'display:none'); // Hide the regular "Publish" button
        }

        const showUpdateButton = () => {
            $('.editor-post-publish-button').attr('style', 'display:inline-flex'); // Show the regular "Update" button
        }

        const hideUpdateButton = () => {
            $('.editor-post-publish-button').attr('style', 'display:none'); // Hide the regular "Update" button
        }

        const showWarningModal = () => {
            $('.ppc-modal-warn').attr('style', 'display:block'); // Modal warning you before publishing or updating
        }

        const showPreventModal = () => {
            $('.ppc-modal-prevent').attr('style', 'display:block'); // Modal preventing you from publishing or updating
        }

        const hideModals = () => {
            $('.ppc-modal-prevent').add('.ppc-modal-warn').attr('style', 'display:none');
        }

        /**
         * - Open the "settings" panel if it is closed
         * - Switch to the "Post" tab if we are on the "Block" tab
         * - Open the "Checklist" box if it is collapsed
         */
         const openSettingsPanel = () => {
            // Open the "Settings" panel if it is closed
            const $settingsButton = $('.edit-post-header__settings button:is([aria-label="Settings"], [aria-label="Réglages"])')
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
         * Scroll to the checklists metabox and apply temp background colour to draw attention
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

        /* ====== Active functions: for syncing interface based on the "state" of requirements checklists ====== */

        // Function to be executed when the itemlist changes.
        var ppc_checkbox_function = function() {
            // check if all the required && recommended checklists are checked
            numItemsComplete = $items.filter('.status-yes').length

            if ($items.length === numItemsComplete) { // if all checkboxes are complete, ready to publish or update
                hideCustomButtons();
                isPublished() ? showUpdateButton() : showPublishButton()
            } else { // if not all checkboxes are complete, don't publish or update
                // see if 'required' checkboxes are still unchecked
                errorLevel = $items.filter('.pp-checklists-block.status-no').length ? REQUIRED : RECOMMENDED;

                if (isPublished()) {
                    hideUpdateButton();
                    showCustomUpdateButton();
                } else {
                    hidePublishButton();
                    showCustomPublishButton();
                }
            }
        }

        /* ====== Publish / Send to draft ====== */
        const getPostStatus = () => wp.data.select('core/editor').getEditedPostAttribute('status');
        let postStatus = getPostStatus();

        wp.data.subscribe(() => {
            const newPostStatus = getPostStatus();
            if(postStatus !== newPostStatus) {
                // Run when post status changes
                setTimeout(ppc_checkbox_function, 1000);
            }
            postStatus = newPostStatus;
        });

        /* ====== Events ====== */

        /* Observer that triggers whenever a checkbox's state changes */
        const checkboxObserver = new MutationObserver((e) => ppc_checkbox_function());
        checkboxObserver.observe($itemsContainer[0], {
            subtree: true,
            attributeFilter: ['class']}
        );

        // Trigger whenever the custom "publish" or "update" buttons are clicked
        $(document).on('click', "#ppc-update, #ppc-publish", function() {
            errorLevel === REQUIRED ?
                showPreventModal() : // Prevent User from Publishing Page/Post
                showWarningModal() // Warn User Before Publishing
        })

        // Warning modal: click "Publish anyway"
        $(document).on('click', ".ppc-popup-options-publishanyway", function() {

            hideModals(); // Hide the warning modal

            // If it's an update to a post
            if (isPublished()) {
                hideCustomButtons();
                showUpdateButton(); // Show the "Update" button
                $('.editor-post-publish-button').trigger('click'); // Click the "Update" button
                hideUpdateButton(); // Hide the "Update" button
                showCustomUpdateButton(); // Show the custom "Update" button

            // If it's being published
            } else {
                hideCustomButtons();
                showPublishButton(); // Show the real "publish" button
                $('.editor-post-publish-panel__toggle').trigger('click'); // Click the real "publish" button

                // check if a slide-out panel with a publish button appears
                const $publishButton = $('.interface-interface-skeleton__actions').find('.editor-post-publish-panel .editor-post-publish-button');
                if($publishButton.length) {
                    $publishButton.trigger('click'); // Click the "publish" button in the slideout panel
                }
            }
        });

        // Warning modal: click "Don't publish"
        // Prevent modal: click "Okay"
        $(document).on('click', ".ppc-popup-option-dontpublish, .ppc-popup-option-okay", function() {
            openSettingsPanel();
            hideModals(); // Hide the "warning"/"prevent" modal
            scrollToMetabox(metaboxID); // scroll the "pre-publish checklists" metabox into view
        });
    }
);
