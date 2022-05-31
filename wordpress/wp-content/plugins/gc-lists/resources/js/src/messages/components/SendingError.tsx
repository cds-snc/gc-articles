
import { ErrorSummary } from "./FieldError"

export const SendingError = () => {
    const error = { location: "test", message: "something happened" };
    return (
        <ErrorSummary errors={[error]} />
    )
}