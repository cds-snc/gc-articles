import * as React from "react";
import { __ } from "@wordpress/i18n";
import { FormRepeater } from "./components/FormRepeater";
import { ListValuesForm } from "./forms/ListValuesForm";
import { NotifyServicesForm } from "./forms/NotifyServicesForm";

enum ListType {
  EMAIL = "email",
  SMS = "sms"
}
export interface ListItem {
  itemId?: string,
  id: string,
  name: string,
  listId: string,
  type: ListType
}

export const ListValuesRepeaterForm = ({ defaultState }: { defaultState: [ListItem] }) => {
  const emptyItem: ListItem = { id: "", name: "", listId: "", type: ListType.EMAIL };
  return (
    <FormRepeater
      addLabel={__("Add List", "cds-snc")}
      formType={ListValuesForm}
      emptyItem={emptyItem}
      defaultState={defaultState}
    />
  )
};

export interface ServiceItem {
  itemId?: string,
  id: string,
  label: string,
  apiKey: string
}

export const NotifyServicesRepeaterForm = ({ defaultState }: { defaultState: [ServiceItem] }) => {
  const emptyItem: ServiceItem = { id: "", label: "", apiKey: "" };
  return (
    <FormRepeater
      addLabel={__("Add Service", "cds-snc")}
      formType={NotifyServicesForm}
      emptyItem={emptyItem}
      defaultState={defaultState}
    />
  )
};