import { __ } from "@wordpress/i18n";
import { SendingHistory } from "./SendingHistory";

export const AllSendingHistory = () => {
    return (
        <>
            <h2>{__("Sending history", "cds-snc")}</h2>
            <SendingHistory perPage={10} pageNav={true} />
        </>

    )
}

export default AllSendingHistory;