/**
 * External dependencies
 */
import Swal from "sweetalert2";

export const ConfirmActionLink = ({ text = '', isConfirmedHandler }: { text: string, isConfirmedHandler: () => void }) => {
    // @todo add aria labels to avoid duplicate text
    // eslint-disable-next-line jsx-a11y/anchor-is-valid
    return (<a href="#" className="button action" onClick={async (e) => {
        e.preventDefault();
        let result = await ConfirmDialog();

        if (result) {
            await isConfirmedHandler();
        }
    }}>{text}</a>)
}

const ConfirmDialog = async () => {
    let result = await Swal.fire({
        title: 'Warning!',
        text: 'This is a destructive action. Are you sure you want to continue?',
        icon: 'error',
        confirmButtonText: 'Yes I\'m sure',
        showCancelButton: true
    });

    if (result.isConfirmed) {
        return true;
    }

    return false;
}
