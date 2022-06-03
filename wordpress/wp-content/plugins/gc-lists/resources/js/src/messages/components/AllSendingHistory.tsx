import { SendingHistory } from "./SendingHistory";

export const AllSendingHistory = () => {
    return (
        <>
            <SendingHistory perPage={10} pageNav={true} allLink={false} />
        </>

    )
}

export default AllSendingHistory;
