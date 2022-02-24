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

export type Message = {
  id: string;
  type: string;
  message: string;
};

export type State = {
  loading: boolean;
  lists: List[] | [];
  messages: Message[] | [];
};

export type Action =
  | { type: 'add'; payload: { id: string } }
  | { type: 'delete'; payload: { id: string } }
  | { type: 'load'; payload: List[] }
  | { type: 'reset'; payload: { id: string } };

export type ListProviderProps = { children: React.ReactNode };
