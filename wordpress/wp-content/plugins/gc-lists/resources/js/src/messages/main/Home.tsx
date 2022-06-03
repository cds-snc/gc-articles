import { ListTemplates } from "../components/ListTemplates";
import { SendingHistory } from "../components/SendingHistory";

export const Home = () => {
    return (
        <>
            <ListTemplates />
            <SendingHistory allLink={true} />
        </>
    )
}

export default Home;
