import * as React from 'react';

import '../classes/Modules/Blocks/src/expander';
import '../classes/Modules/Blocks/src/alert';
import {render} from '@wordpress/element';
import {LoginsPanel} from '../classes/Modules/TrackLogins/src/LoginsPanel';
import {NotifyPanel} from '../classes/Modules/Notify/src/NotifyPanel';
import {List} from '../classes/Modules/Notify/src/Types';
import {handleSubscribe} from "../classes/Modules/Subscribe/src/handler"

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
        };
        CDS_VARS: {
            rest_url?: string;
            rest_nonce?: string;
            notify_list_ids?: List[];
        };
    }
}

export const renderLoginsPanel = () => {
    render(<LoginsPanel/>, document.getElementById('logins-panel'));
};

export const renderNotifyPanel = ({
                                      sendTemplateLink,
                                  }: {
    sendTemplateLink: boolean;
}) => {
    render(
        <NotifyPanel sendTemplateLink={sendTemplateLink}/>,
        document.getElementById('notify-panel')
    );
};

window.CDS = window.CDS || {};
window.CDS.Notify = {renderPanel: renderNotifyPanel};
window.CDS.renderLoginsPanel = renderLoginsPanel;
