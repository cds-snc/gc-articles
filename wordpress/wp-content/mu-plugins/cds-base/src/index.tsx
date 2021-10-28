import * as React from "react";

import "../classes/Modules/Blocks/src/expander";
import "../classes/Modules/Blocks/src/alert";
import { render } from "@wordpress/element";
import { LoginsPanel } from "../classes/Modules/TrackLogins/src/LoginsPanel";
import { CollectionsPanel } from "../classes/Modules/UserCollections/src/CollectionsPanel";
import { NotifyPanel } from "../classes/Modules/Notify/src/NotifyPanel";
import { List } from "../classes/Modules/Notify/src/Types";
import { UserForm } from "../classes/Modules/Users/src/UserForm"
import { writeInterstitialMessage } from "util/preview"

declare global {
  interface Window {
    CDS: {
      Notify?: {
        renderPanel: ({
          sendTemplateLink,
        }: {
          sendTemplateLink: boolean;
        }) => void;
      };
      renderLoginsPanel?: () => void;
      renderCollectionsPanel?: () => void;
      renderUserForm?: () => void;
      writeInterstitialMessage?: () => void;
    };
    CDS_VARS: {
      rest_url?: string;
      rest_nonce?: string;
      notify_list_ids?: List[];
    }
    wp:any;
  }
}

export const renderLoginsPanel = () => {
  render(<LoginsPanel />, document.getElementById("logins-panel"));
};

export const renderCollectionsPanel = () => {
  render(<CollectionsPanel />, document.getElementById("collections-panel"));
};

export const renderNotifyPanel = ({
  sendTemplateLink,
}: {
  sendTemplateLink: boolean;
}) => {
  render(
    <NotifyPanel sendTemplateLink={sendTemplateLink} />,
    document.getElementById("notify-panel")
  );
};


export const renderUserForm = () => {
  render(
    <UserForm />,
    document.getElementById("react-body")
  );
};


window.CDS = window.CDS || {};
window.CDS.Notify = { renderPanel: renderNotifyPanel };
window.CDS.renderLoginsPanel = renderLoginsPanel;
window.CDS.renderCollectionsPanel = renderCollectionsPanel;
window.CDS.renderUserForm = renderUserForm;
window.CDS.writeInterstitialMessage =  writeInterstitialMessage;

window.wp.hooks.addFilter(
	'editor.PostPreview.interstitialMarkup',
	'my-plugin/custom-preview-message',
	() => window.CDS.writeInterstitialMessage()
);


