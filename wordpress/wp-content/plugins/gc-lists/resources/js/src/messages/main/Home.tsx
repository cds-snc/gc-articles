import { ListTemplates } from "../components/ListTemplates";
import { SendingHistory } from "../components/SendingHistory";
import { __ } from '@wordpress/i18n';

export const Home = () => {
    return (
        <>
            <ListTemplates />
            <h2>{__("Sending history", "cds-snc")}</h2>
            <SendingHistory />
        </>
    )
}

export default Home;
