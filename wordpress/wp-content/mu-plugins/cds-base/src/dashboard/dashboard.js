import { NotifyPanel, findListById, getData } from "./NotifyPanel"
import { LoginsPanel } from "./LoginsPanel";

const { render } = wp.element;

/*  https://javascriptforwp.com/adding-react-to-a-wordpress-theme-tutorial/ */

export const renderNotifyPanel = ({ sendTemplateLink }) => {
    render(<NotifyPanel sendTemplateLink={sendTemplateLink} />, document.getElementById("notify-panel"));
}

export const renderLoginsPanel = () => {
    render(<LoginsPanel />, document.getElementById("logins-panel"))
}

export { findListById as findListById }
export { getData as getData }
