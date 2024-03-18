variable "client_vpn_access_group_id" {
  description = "IAM Identity Center group ID that will be allowed access to the VPN."
  type        = string
  sensitive   = true
}

variable "client_vpn_saml_metadata" {
  description = "IAM Identity Center application SAML metadata.  Users that want to connect to the VPN must be granted access to this app."
  type        = string
  sensitive   = true
}
