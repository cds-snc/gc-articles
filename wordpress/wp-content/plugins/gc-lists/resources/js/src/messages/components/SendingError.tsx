/**
 * Internal dependencies
 */
import { ErrorSummary } from "."

export const SendingError = () => {
    const error = { location: "test", message: "something happened" };
    return (
        <ErrorSummary errors={[error]} />
    )
}