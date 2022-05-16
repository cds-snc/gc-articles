import { __, sprintf } from "@wordpress/i18n";
export const MessageSent = ({ count }: { count: number }) => {
    return (
        <>
            <h1>{__("Message sent", "cds-snc")}</h1>
            <p>{__("The message was sent to the the subscribers in the list below.", "cds-snc")}</p>
            <p>{sprintf(`Newsletter sendout (%s subscribers)`, count, "cds-snc")}</p>
        </>
    )
}