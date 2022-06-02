import { ListTemplates } from "../components/ListTemplates";
import { SendingHistory } from "../components/SendingHistory";
import { __ } from '@wordpress/i18n';

export const Home = () => {
    return (
        <>
            <ListTemplates />
            <SendingHistory />
        </>
    )
}

export default Home;
