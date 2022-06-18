import { SendingHistory, ListTemplates  } from "../components";

export const Home = () => {
    return (
        <>
            <ListTemplates />
            <SendingHistory allLink={true} />
        </>
    )
}

export default Home;
