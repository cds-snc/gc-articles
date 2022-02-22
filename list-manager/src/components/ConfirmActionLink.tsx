import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";

export const ConfirmActionLink = ({path = '', text = ''}:{path: string, text: string}) => {
    let navigate = useNavigate();
    // eslint-disable-next-line jsx-a11y/anchor-is-valid
    return (<a href="#" onClick={async () => {
        let result = await ConfirmDialog();

        if (result) {
            navigate(path)
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
