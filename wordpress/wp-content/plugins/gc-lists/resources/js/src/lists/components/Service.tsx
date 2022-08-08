/**
 * External dependencies
 */
import { __ } from "@wordpress/i18n";

/**
 * Internal dependencies
 */
import { ListViewTable } from "./ListViewTable";
import { Messages } from "./Messages";
import { Error } from "./Error";
import { useList } from "../../store";

export const Service = () => {
    const { state: { serviceData } } = useList();

    if (!serviceData) {
        return <Error />;
    }

    return (
        <>
            <h1>{__("Mailing lists", "gc-lists")}</h1>
            <Messages />
            <ListViewTable />
        </>
    )
}

export default Service;
