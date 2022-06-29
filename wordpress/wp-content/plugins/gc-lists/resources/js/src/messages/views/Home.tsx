/**
 * External dependencies
 */
import * as React from 'react';

import { SendingHistory, ListDrafts  } from "../components";

export const Home = () => {
    return (
        <>
            <ListDrafts />
            <SendingHistory allLink={true} />
        </>
    )
}

export default Home;
