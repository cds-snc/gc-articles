export type List = {
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
  subscriber_count?: string;
  active?: string;
};

export type Dispatch = (action: Action) => void;

export type ListId = {
  id: string | null;
};

export enum ListType {
  EMAIL = 'email',
  PHONE = 'phone',
}

export type Message = {
  id: string;
  type: string;
  message: string;
};

export type Service = { name: string; service_id: string };

export type ServiceData = Service[] | null;

export type User = {
  hasPhone: boolean;
  hasEmail: boolean;
};

export type State = {
  loading: boolean;
  lists: List[] | [];
  messages: Message[] | [];
  user: User;
  serviceData: ServiceData;
};

export type Action =
  | { type: 'add'; payload: { id: string } }
  | { type: 'delete'; payload: { id: string } }
  | { type: 'load'; payload: List[] }
  | { type: 'reset'; payload: { id: string } };

export type ErrorResponse = {
  detail: [
    {
      loc: [string, keyof List];
      msg: string;
      type: string;
      ctx?: {};
    }
  ];
};

export type FieldError = {
  name: keyof List;
  msg: string;
};

export type ServerErrors = [] | FieldError[];

export type CSVData = {
  email: string;
};

export type ListProviderProps = {
  serviceData: ServiceData;
  user: User;
  children: React.ReactNode;
};

export interface NotifyList {
  id: string;
  label: string;
  subscriber_count?: number;
  list_id?: string;
}

export type TemplateType = {
  id: string;
  name: string;
  subject: string;
  content: string;
};
