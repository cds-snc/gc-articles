export type Inputs = {
  id: string;
  name: string;
  language: string;
  service_id: string;
  subscribe_email_template_id?: string;
  unsubscribe_email_template_id?: string;
  subscribe_phone_template_id?: string;
  unsubscribe_phone_template_id?: string;
  subscribe_redirect_url?: string;
  confirm_redirect_url?: string;
  unsubscribe_redirect_url?: string;
};
