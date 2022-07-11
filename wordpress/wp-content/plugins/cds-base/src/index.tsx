import * as React from "react";

import "../classes/Modules/Blocks/src/expander";
import "../classes/Modules/Blocks/src/accordion";
import "../classes/Modules/Blocks/src/alert";
import "../classes/Modules/Blocks/src/latestPosts";
import { render } from "@wordpress/element";
import { LoginsPanel } from "../classes/Modules/TrackLogins/src/LoginsPanel";
import { DBInsightsPanel } from "../classes/Modules/DBInsights/src/DBInsights";
import { DBActivityPanel } from "../classes/Modules/DBInsights/src/DBActivity";

import { CollectionsPanel } from "../classes/Modules/UserCollections/src/CollectionsPanel";
import { List } from "./Types";
import { UserForm } from "../classes/Modules/Users/src/UserForm";
import { writeInterstitialMessage } from "util/preview";

declare global {
  interface Window {
    CDS: {
      renderLoginsPanel?: () => void;
      renderCollectionsPanel?: () => void;
      renderUserForm?: ({ isSuperAdmin }, { isSuperAdmin: boolean }) => void;
      writeInterstitialMessage?: () => void;
      renderDBInsightsPanel?: () => void;
      renderDBActivityPanel?: () => void;
    };
    CDS_VARS: {
      rest_url?: string;
      rest_nonce?: string;
      notify_list_ids?: List[];
    }
    wp: any;
  }
}

export const renderLoginsPanel = () => {
  render(<LoginsPanel />, document.getElementById("logins-panel"));
};

export const renderCollectionsPanel = () => {
  render(<CollectionsPanel />, document.getElementById("collections-panel"));
};

export const renderDBInsightsPanel = () => {
  render(<DBInsightsPanel />, document.getElementById("db-insignts-panel"));
};

export const renderDBActivityPanel = () => {
  render(<DBActivityPanel />, document.getElementById("db-activity-panel"));
};

export const renderUserForm = ({ isSuperAdmin }) => {
  render(
    <UserForm isSuperAdmin={isSuperAdmin} />,
    document.getElementById("react-body")
  );
};

window.CDS = window.CDS || {};
window.CDS.renderLoginsPanel = renderLoginsPanel;
window.CDS.renderCollectionsPanel = renderCollectionsPanel;
window.CDS.renderUserForm = renderUserForm;
window.CDS.writeInterstitialMessage = writeInterstitialMessage;
window.CDS.renderDBInsightsPanel = renderDBInsightsPanel;
window.CDS.renderDBActivityPanel = renderDBActivityPanel;

const hiddenBlocks = ['yoast-seo/table-of-contents'];

window.wp.blocks.getBlockTypes().forEach(function (block) {
  if (block.name && hiddenBlocks.includes(block.name)) {
    window.wp.blocks.unregisterBlockType(block.name);
  }
});

window.wp.hooks.addFilter(
  'editor.PostPreview.interstitialMarkup',
  'my-plugin/custom-preview-message',
  () => window.CDS.writeInterstitialMessage()
);


