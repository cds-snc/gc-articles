import Swal from 'sweetalert2';
import { __ } from '@wordpress/i18n';

export const confirmSend = async (text) => {

  const result = await Swal.fire({
    title: __('Are you sure you want to send?', 'cds-snc'),
    text: text ? text : __('You won’t be able to revert this!', 'cds-snc'),
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#2271b1',
    cancelButtonColor: '#d63638',
    confirmButtonText: __('Yes, send it!', 'cds-snc')
  });

  if (result.isConfirmed) {
    return true;
  }
  return false;
};