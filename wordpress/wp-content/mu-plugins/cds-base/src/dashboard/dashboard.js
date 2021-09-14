import { NotifyPanel } from "./NotifyPanel"
const { render } = wp.element;


/*  https://javascriptforwp.com/adding-react-to-a-wordpress-theme-tutorial/ */

export const renderNotifyPanel = () => {
    render(<NotifyPanel />, document.getElementById("notify-panel"));
}

