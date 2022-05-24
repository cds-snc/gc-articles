import { SendingHistory } from "./SendingHistory";

export const AllSendingHistory = () => {
    return (
        <>
            <SendingHistory perPage={10} pageNav={true} />
        </>

    )
}

export default AllSendingHistory;