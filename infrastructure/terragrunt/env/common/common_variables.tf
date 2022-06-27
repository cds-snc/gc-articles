variable "account_id" {
  description = "(Required) The account ID to perform actions on."
  type        = string
}

variable "billing_tag_key" {
  description = "(Required) the key we use to track billing"
  type        = string
}

variable "billing_tag_value" {
  description = "(Required) the value we use to track billing"
  type        = string
}

variable "cbs_satellite_bucket_name" {
  description = "(Required) Name of the Cloud Based Sensor S3 satellite bucket"
  type        = string
}

variable "enable_efs" {
  description = "(Required) Enable the shared Elastic File System for the WordPress ECS tasks"
  type        = string
}

variable "enable_waf" {
  description = "(Required) Enable the Web Application Firewall for the WordPress Load balancer"
  type        = bool
  default     = true
}

variable "env" {
  description = "(Required) The current running environment"
  type        = string
}

variable "region" {
  description = "(Required) The region to build infra in"
  type        = string
}
